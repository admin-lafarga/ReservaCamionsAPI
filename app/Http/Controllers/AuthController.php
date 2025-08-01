<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);

        $email = strtolower($request->input('email'));
        $ip = $request->ip();

        // Clau rate limiting
        $keyIpEmail = hash('sha256', $ip . '|' . $email);
        $keyEmailOnly = hash('sha256', 'login:email:' . $email);

        $maxAttempts = 5;
        $decaySeconds = 60;

        // Rate limiting per IP+Email
        if (RateLimiter::tooManyAttempts($keyIpEmail, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($keyIpEmail);
            return response()->json(['message' => "Masses intents (IP+email). Torna-ho a intentar en {$seconds} segons."], 429);
        }

        // Rate limiting per Email sol
        if (RateLimiter::tooManyAttempts($keyEmailOnly, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($keyEmailOnly);
            return response()->json(['message' => "Masses intents (email). Torna-ho a intentar en {$seconds} segons."], 429);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            RateLimiter::hit($keyIpEmail, $decaySeconds);
            RateLimiter::hit($keyEmailOnly, $decaySeconds);
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }

        // Verificar que l'usuari està actiu
        if (!User::where('email', $email)->where('estado', 1)->exists()) {
            RateLimiter::hit($keyIpEmail, $decaySeconds);
            RateLimiter::hit($keyEmailOnly, $decaySeconds);
            Auth::logout(); // logout per seguretat
            return response()->json(['message' => 'Credencials incorrectes'], 401);
        }

        // Si tot correcte, netejar intents
        RateLimiter::clear($keyIpEmail);
        RateLimiter::clear($keyEmailOnly);

        $user = Auth::user();

        $request->session()->regenerate();


        return response()->json([
            'message' => 'Login correcte',
            'user' => $user,
        ]);
    }

    public function login2(Request $request) {
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


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada'
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'apellidos' => 'required|string|max:255',
            'PIN' => 'required|string',
            'NIF' => 'required|string|unique:users,NIF',
            'tel1' => 'required|string|max:15|unique:users,tel1',
            'estado' => 'required|boolean',
            'rol_id' => 'required|exists:roles,rol_id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'apellidos' => $validated['apellidos'],
            'PIN' => $validated['PIN'],
            'NIF' => $validated['NIF'],
            'tel1' => $validated['tel1'],
            'estat' => $validated['estado'],
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
        return response()->json(auth()->check());
    }
}
