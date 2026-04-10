<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Proveedor;
use App\Models\Entidad;
use Carbon\Carbon;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Proveedor::class, 'proveedor');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedors = Proveedor::with([
            'tipoProveedor:tipo_proveedor_id,nombre',
            'entidad'
            ])->get();

        $proveedors->each(function ($proveedor) {
            if ($proveedor->entidad) {
                $proveedor->entidad->makeVisible('pin');
            }
        });

        return response()->json($proveedors);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProveedorRequest $request)
    {
        $entidad = Entidad::create($request->validated()['entidad']);
        $entidad->proveedor()->create([
            'tipo_proveedor_id' => $request->validated()['tipo_proveedor_id'],
            'email_notificaciones' => $request->validated()['email_notificaciones'],
            'codigo_sap' => $request->validated()['codigo_sap'],
        ]);

        return response()->json([
            'message' => 'Proveedor creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load(['tipoProveedor', 'entidad']);
        if ($proveedor->entidad) {
            $proveedor->entidad->makeVisible('pin');
        }
        return response()->json($proveedor);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedor $proveedor)
    {
        // Validar y actualizar el proveedor con los datos validados del request
        $proveedor->update($request->validated());
        $proveedor->entidad->update($request->validated()['entidad']);

        return response()->json([
            'message' => 'Proveedor actualizado correctamente.',
            'data' => $proveedor
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    // S'ha canviat de convenció perquè estava donant errors
    public function destroy(Proveedor $proveedor)
    {
        $tieneReservas = $proveedor->reservas()
            ->whereDate('inicio', '>=', Carbon::today())
            ->exists();

        if ($tieneReservas) {
            return response()->json([
                'proveedor' => $proveedor,
                'message' => 'No se puede desactivar el proveedor porque tiene reservas activas',
            ], 422);
        }
        $proveedor->entidad()->delete();
        $proveedor->delete();

        return response()->json([
            'message' => 'Proveedor desactivado correctamente.'
        ]);
    }
}