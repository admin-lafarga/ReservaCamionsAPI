<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaLfycsController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\BloqueoMuelleController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TipoProveedorController;
use App\Http\Controllers\TipoCamionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RestriccionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioMuelleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BloqueoGrupoMaterialController;
use App\Http\Controllers\BloqueoGrupoMaterialDetalleController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\TransportistaController;
use Illuminate\Support\Facades\Mail;
use App\Models\Reserva;
use App\Mail\ConfirmationMail;
use Mockery\Generator\Parameter;

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the ReservaCamions Localhost API',
        'ip' => $request->getClientIp(),
    ], 200);
});
//Ruta para probar la conexión de la bbdd
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['message' => 'Database connection is successful.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Database connection failed: ' . $e->getMessage()], 500);
    }
});

Route::get('test', function(){
    return 'exitos';
})->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/authenticated', [AuthController::class, 'authenticated']);

// TERMPORALMENT #############################################
Route::post('/register', [AuthController::class, 'register']);
// ###########################################################
// 'auth:sanctum'
Route::middleware('auth:sanctum')->group(function () {
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // API CRUD RESOURCES
    Route::get('getFile/{path}', [ReservaController::class, 'getPrivateFile'])->where('path', '.*');
    Route::get('file/name/{path}', [ReservaController::class, 'getPrivateFileName'])->where('path', '.*');
    Route::delete('file/name/{id}', [ReservaController::class, 'deletePrivateFile']);
    Route::get('/reservas/calendar', [ReservaController::class, 'indexCalendar']);
    Route::apiResource('muelle/bloqueos', BloqueoMuelleController::class)->parameters(['bloqueos' => 'bloqueoMuelle']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('proveedores', ProveedorController::class)->parameter('proveedores', 'proveedor');
    Route::apiResource('transportistas', TransportistaController::class);
    Route::apiResource('tipoproveedores', TipoProveedorController::class)->parameters(['tipoproveedores' => 'tipoProveedor']);
    Route::apiResource('tipocamiones', TipoCamionController::class);
    Route::apiResource('materiales', MaterialController::class)->Parameter('materiales', 'material');
    Route::apiResource('roles', RolController::class);
    Route::delete('restricciones/bulk-delete', [RestriccionController::class, 'bulkDelete']);
    Route::apiResource('restricciones', RestriccionController::class)->parameters(['restricciones' => 'restriccion']);
    Route::apiResource('status', EstadoController::class);
    // Route::apiResource('bloqueo/camion/material', BloqueoCamionMaterialController::class);
    Route::apiResource('empresas_lfycs', EmpresaLfycsController::class)->parameter('empresas_lfycs', 'empresa');
    Route::apiResource('muelle/horarios', HorarioMuelleController::class);
    Route::apiResource('muelles', MuelleController::class);
    Route::apiResource('reserva', ReservaController::class);
    Route::apiResource('permisos', PermisoController::class);
    Route::get('bloqueo/grupos/material/{materialId}', [BloqueoGrupoMaterialController::class, 'getByMaterial']);
    Route::apiResource('bloqueo/grupos', BloqueoGrupoMaterialController::class);
    Route::apiResource('bloqueo/grupo/detalles', BloqueoGrupoMaterialDetalleController::class);
    Route::get('config/claves', [ParametroController::class, 'getParametrosByKeys']);
    Route::put('config/claves', [ParametroController::class, 'storeParametrosByKeys']);
    Route::apiResource('config', ParametroController::class);
    Route::get('reservas/informe', [ReservaController::class, 'informeReservas']);
    Route::post('report', [ReservaController::class, 'generateReport']);

    //Esto es para obtener las columnas de una tabla dinámicamnete desde el frontend y hay que permitirlas para que no de error de sql injection
    Route::get('/columns/{table}', function ($table) {
        $allowedTables = ['materiales', 'usuarios', 'proveedores','bloqueo/grupos','transportistas','empresas_lfycs','muelles','users','tipo_camiones','estados','reservas','horarios_muelles','tipo_proveedores', 'restricciones', 'bloqueo_muelles'];
        if (!in_array($table, $allowedTables)) {
            return response()->json(['error' => 'Taula no permesa'], 403);
        }
        try {
            $columns = DB::select("SHOW COLUMNS FROM `$table`");

            if($table == 'transportistas' || $table == 'proveedores') {
                $columns = array_merge($columns, DB::select("SHOW COLUMNS FROM entidades"));
            };

            return response()->json($columns);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error carregant columnes: ' . $e->getMessage()], 500);
        }
    });

    // Route::post('/send-test', function () {
    //     $order = Reserva::first();
    //     Mail::to('hassan.abbas@lafarga.es')->send(new ConfirmationMail($order));
    //     return response()->json(['status' => 'ok', 'message' => 'Correo enviado']);
    // });
 });

 Route::post('/send-test', function () {
        $order = Reserva::first();
        Mail::to('hassan.abbas@lafarga.es')->send(new ConfirmationMail($order));
        return response()->json(['status' => 'ok', 'message' => 'Correo enviado']);
    });

 
