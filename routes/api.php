<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BloqueoKgMaterialController;
use App\Http\Controllers\BloqueoCamionMaterialController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TransporteController;
use App\Http\Controllers\TipoProveedorController;
use App\Http\Controllers\TipoCamionController;
use App\Http\Controllers\TipoMuelleController;
use App\Http\Controllers\TipoMaterialController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RestriccionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioMuelleController;
use App\Http\Controllers\ControlMaterialMuelleController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\AuthController;

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the ReservaCamions Localhost API',
        'ip' => $request->getClientIp(),
    ], 200);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function(){
    return 'exitos';
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login2'])->middleware('guest');
// Route::post('/login', [AuthController::class, 'login']);

// TERMPORALMENT #############################################
Route::post('/register', [AuthController::class, 'register']);
// ###########################################################
// 'auth:sanctum'
// Route::middleware(['web'])->group(function () {
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // API CRUD RESOURCES
    Route::post('controlCamion', [ControlMaterialMuelleController::class, 'materialCamioMuelle']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('proveedores', ProveedorController::class);
    Route::apiResource('transportistas', TransporteController::class);
    Route::apiResource('tipoproveedor', TipoProveedorController::class);
    Route::apiResource('tipocamion', TipoCamionController::class);
    Route::apiResource('tipomuelle', TipoMuelleController::class);
    Route::apiResource('tipomaterial', TipoMaterialController::class);
    Route::apiResource('materiales', MaterialController::class);
    Route::apiResource('roles', RolController::class);
    Route::apiResource('restricciones', RestriccionController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('bloqueo/camion/material', BloqueoCamionMaterialController::class);
    Route::apiResource('bloqueo/camion/Kg', BloqueoKgMaterialController::class);
    Route::apiResource('empresa', EmpresaController::class);
    Route::apiResource('muelle/horarios', HorarioMuelleController::class);
    Route::apiResource('muelle', MuelleController::class);
    Route::apiResource('reserva', ReservaController::class);
    Route::apiResource('privilegios', PrivilegioController::class);
// });
