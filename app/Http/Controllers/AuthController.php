<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $ip = $request->ip();

        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        // Clau rate limiting
        $keyIpLogin = hash('sha256', $ip . '|' . strtolower($login));
        $keyLoginOnly = hash('sha256', 'login:email:' . strtolower($login));

        $maxAttempts = 5;
        $decaySeconds = 60;

        // // Rate limiting per IP+Email
        // if (RateLimiter::tooManyAttempts($keyIpLogin, $maxAttempts)) {
        //     $seconds = RateLimiter::availableIn($keyLoginOnly);
        //     return response()->json(['message' => "Masses intents (IP+email). Torna-ho a intentar en {$seconds} segons."], 429);
        // }

        // // Rate limiting per Email sol
        // if (RateLimiter::tooManyAttempts($keyLoginOnly, $maxAttempts)) {
        //     $seconds = RateLimiter::availableIn($keyLoginOnly);
        //     return response()->json(['message' => "Masses intents (email). Torna-ho a intentar en {$seconds} segons."], 429);
        // }


        // Buscar usuario por email o username
        $user = $isEmail
            ? User::where('email', $login)->first()
            : User::where('username', $login)->first();


        if ($user === null) {
            $user = Entidad::where('nombre', $login)->first();
        }

        if (!$user) {
            // RateLimiter::hit($keyIpLogin, $decaySeconds);
            // RateLimiter::hit($keyLoginOnly, $decaySeconds);
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }


        $loginExitoso = false;
        // ---------------------------------------------------------
        // PASO 1: Intentar validación moderna (Laravel Standard)
        // ---------------------------------------------------------
        if (!empty($user->contraseña || !empty($user->pin))) {
            try {
                // Intentamos verificar. Si el hash en BBDD no es Bcrypt, esto fallará con Excepción.
                if (Hash::check($password, $user->contraseña) || $password === $user->pin) {
                    $loginExitoso = true;
                }
            } catch (\RuntimeException $e) {
                // Capturamos el error "This password does not use the Bcrypt algorithm".
                // No hacemos nada, simplemente dejamos que el flujo continúe al Paso 2 (Legacy).
            }
        }


        // ---------------------------------------------------------
        // PASO 2: Intentar validación Legacy (Sistema Antiguo)
        // ---------------------------------------------------------
        // Solo entramos si el paso 1 falló o dio error
        if (!$loginExitoso && $user instanceof User) {

            // Tu lógica de encriptación antigua: sha1(md5($pass))
            $legacyHash = sha1(md5($password));

            if (!empty($user->contraseña_antigua) && $user->contraseña_antigua === $legacyHash) {

                // ¡ÉXITO! El usuario introdujo su contraseña vieja correctamente.
                // MIGRACIÓN AUTOMÁTICA:

                // 1. Encriptamos la contraseña con el estándar actual de Laravel
                $user->contraseña = Hash::make($password);

                // 2. Opcional: Borramos la antigua para evitar este chequeo en el futuro
                $user->contraseña_antigua = null;

                $user->save();

                $loginExitoso = true;
            }
        }


        // ---------------------------------------------------------
        // PASO 3: Resultado Final
        // ---------------------------------------------------------
        if ($loginExitoso) {
            RateLimiter::clear($keyIpLogin);
            RateLimiter::clear($keyLoginOnly);
            

            if ($user instanceof User) {
                Auth::guard('web')->login($user);
            } elseif ($user instanceof Entidad) {
                Auth::guard('entidad')->login($user);
            }

            $request->session()->regenerate();
            
            return response()->json([
                'message' => 'Login correcte',
                'user' => $user,
                'instance' => $user instanceof User ? 'usuario' : 'entidad',
                'proveedor' => $user->proveedor ?? null,
                'transportista' => $user->Carrier ?? null,
                'logged' => true,
            ]);
        } else {
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }
    }

    public function login2(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(['message' => __('Welcome!')]);
        }

        throw ValidationException::withMessages([
            'email' => __('Bad credentials!'),
        ]);
    }


    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('entidad')->check()) {
            Auth::guard('entidad')->logout();
        }

        // 3. Limpieza de seguridad de la sesión de PHP
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sesión cerrada'
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contraseña' => 'required|string|min:6',
            'apellidos' => 'required|string|max:255',
            'PIN' => 'required|string',
            'NIF' => 'required|string|unique:users,NIF',
            'tel1' => 'required|string|max:15|unique:users,tel1',
            'rol_id' => 'required|exists:roles,rol_id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contraseña' => Hash::make($validated['password']),
            'apellidos' => $validated['apellidos'],
            'PIN' => $validated['PIN'],
            'NIF' => $validated['NIF'],
            'tel1' => $validated['tel1'],
            'rol_id' => $validated['rol_id'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function authenticated(Request $request)
    {
        $user = null;
        $instance = null;

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $instance = 'usuario';
        } elseif (Auth::guard('entidad')->check()) {
            $user = Auth::guard('entidad')->user();
            $instance = 'entidad';
        }

        return response()->json([
            'user' => $user,
            'instance' => $instance,
            'proveedor' => $user->proveedor ?? null,
            'transportista' => $user->Carrier ?? null,
            'logged' => $user !== null,
        ]);
    }
}