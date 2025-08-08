<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\UserController;
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
use App\Http\Controllers\BloqueoGrupoController;
use App\Http\Controllers\BloqueoGrupoDetalleController;

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Welcome to the ReservaCamions Localhost API',
        'ip' => $request->getClientIp(),
    ], 200);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->middleware('web');
Route::get('/authenticated', [AuthController::class, 'authenticated']);

// TERMPORALMENT #############################################
Route::post('/register', [AuthController::class, 'register']);
// ###########################################################
// 'auth:sanctum'
Route::middleware(['auth:sanctum','web'])->group(function () {
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout']);

    // API CRUD RESOURCES
    Route::post('controlCamion', [ControlMaterialMuelleController::class, 'materialCamioMuelle']);
    Route::get('getFile/{path}', [ReservaController::class, 'getPrivateFile'])->where('path', '.*');
    Route::get('file/name/{path}', [ReservaController::class, 'getPrivateFileName'])->where('path', '.*');
    Route::delete('file/name/{id}', [ReservaController::class, 'deletePrivateFile']);
    Route::get('/materials/restrictions', [MaterialController::class, 'getMaterials']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('proveedores', ProveedorController::class);
    Route::apiResource('transportistas', TransporteController::class);
    Route::apiResource('tipoproveedores', TipoProveedorController::class);
    Route::apiResource('tipocamiones', TipoCamionController::class);
    Route::apiResource('tipomuelles', TipoMuelleController::class);
    Route::apiResource('tipomateriales', TipoMaterialController::class);
    Route::apiResource('materiales', MaterialController::class);
    Route::apiResource('roles', RolController::class);
    Route::apiResource('restricciones', RestriccionController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('bloqueo/camion/material', BloqueoCamionMaterialController::class);
    Route::apiResource('empresas', EmpresaController::class);
    Route::apiResource('muelle/horarios', HorarioMuelleController::class);
    Route::apiResource('muelles', MuelleController::class);
    Route::apiResource('reserva', ReservaController::class);
    Route::apiResource('privilegios', PrivilegioController::class);
    Route::apiResource('bloqueo/grupos', BloqueoGrupoController::class);
    Route::apiResource('bloqueo/grupo/detalles', BloqueoGrupoDetalleController::class);


    Route::get('/columns/{table}', function ($table) {
        $allowedTables = ['materiales', 'usuarios', 'proveedores','bloqueo/grupos','transportes','empresas','muelles','users'];
        if (!in_array($table, $allowedTables)) {
            return response()->json(['error' => 'Taula no permesa'], 403);
        }

        try {
            $columns = DB::select("SHOW COLUMNS FROM `$table`");
            return response()->json($columns);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error carregant columnes: ' . $e->getMessage()], 500);
        }
    });
 });
