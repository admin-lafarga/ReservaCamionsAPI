<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Bloqueo_Kg_MaterialController;
use App\Http\Controllers\Bloqueo_Camion_MaterialController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MuelleController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\TransporteController;
use App\Http\Controllers\Tipo_ProveedorController;
use App\Http\Controllers\Tipo_CamionController;
use App\Http\Controllers\Tipo_MuelleController;
use App\Http\Controllers\Tipo_MaterialController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RestriccionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Horarios_MuelleController;
use App\Http\Controllers\ControlMaterialMuelleController;
use App\Http\Controllers\PrivilegioController;
use App\Http\Controllers\AuthController;

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the ReservaCamions Localhost API',
        'ip' => $request->getClientIp(),
    ], 200);
});

Route::post('/login', [AuthController::class, 'login']);

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
    Route::apiResource('tipo_proveedor', Tipo_ProveedorController::class);
    Route::apiResource('tipo_camion', Tipo_CamionController::class);
    Route::apiResource('tipo_muelle', Tipo_MuelleController::class);
    Route::apiResource('tipo_material', Tipo_MaterialController::class);
    Route::apiResource('materiales', MaterialController::class);
    Route::apiResource('roles', RolController::class);
    Route::apiResource('restricciones', RestriccionController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('bloqueo/camion/material', Bloqueo_Camion_MaterialController::class);
    Route::apiResource('bloqueo/camion/Kg', Bloqueo_Kg_MaterialController::class);
    Route::apiResource('empresa', EmpresaController::class);
    Route::apiResource('muelle/horarios', Horarios_MuelleController::class);
    Route::apiResource('muelle', MuelleController::class);
    Route::apiResource('reserva', ReservaController::class);
    Route::apiResource('privilegios', PrivilegioController::class);
// });
