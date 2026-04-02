<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoGrupoMaterialRequest;
use App\Http\Requests\StoreBloqueoGrupoMaterialDetalleRequest;
use App\Http\Requests\UpdateBloqueoGrupoMaterialRequest;
use App\Models\BloqueoGrupoMaterial;
use App\Models\BloqueoGrupoMaterialDetalle;

class BloqueoGrupoMaterialController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BloqueoGrupoMaterial::class, 'grupo');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $BloqueoGrupoMaterials = BloqueoGrupoMaterial::with([
            'tipoproveedor:tipo_proveedor_id,nombre',
            'detalles.material:material_id,nombre'
        ])->get();

        return response()->json($BloqueoGrupoMaterials);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreBloqueoGrupoMaterialRequest $request)
    {
        $validated = $request->validated();

        $BloqueoGrupoMaterial = [
            'tipo_proveedor_id' => (int) $validated['tipo_proveedor_id'],
            'inicio' => $validated['fecha_desde'],
            'fin' => $validated['fecha_hasta'],
            'cantidad_total' => $validated['cantidad_total'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
        ];

        $grupo = BloqueoGrupoMaterial::create($BloqueoGrupoMaterial);

        foreach ($validated['detalles'] as $detalle) {
            $BloqueoGrupoMaterialDetalle = [
                'bloqueo_grupo_id' => $grupo->bloqueo_grupo_id,
                'material_id' => $detalle['material_id'],
            ];
            BloqueoGrupoMaterialDetalle::create($BloqueoGrupoMaterialDetalle);
        }

        return response()->json($grupo, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoGrupoMaterialRequest $request, int $id)
    {
        $validated = $request->validated();

        $grupo = BloqueoGrupoMaterial::findOrFail($id);

        $grupo->update([
            'tipo_proveedor_id' => (int) $validated['tipo_proveedor_id'],
            'inicio' => $validated['fecha_desde'],
            'fin' => $validated['fecha_hasta'],
            'cantidad_total' => $validated['cantidad_total'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
        ]);

        $grupo->detalles()->delete();

        foreach ($validated['detalles'] as $detalle) {
            $BloqueoGrupoMaterialDetalle = [
                'bloqueo_grupo_id' => $grupo->bloqueo_grupo_id,
                'material_id' => $detalle['material_id'],
            ];
            BloqueoGrupoMaterialDetalle::create($BloqueoGrupoMaterialDetalle);
        }

        return response()->json($grupo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //Eliminar el bloqueo y sus detalles
        $grupo = BloqueoGrupoMaterial::findOrFail($id);
        $grupo->detalles()->delete();
        $grupo->delete();


        return response()->json(['message' => 'BloqueoGrupoMaterial i los detalles eliminados correctamente']);
    }
    /**
     * Get material blockages by material_id for calendar display
     */
    public function getByMaterial(int $materialId)
    {
        $this->authorize('viewAny', BloqueoGrupoMaterial::class);

        $bloqueos = BloqueoGrupoMaterial::with([
            'tipoproveedor:tipo_proveedor_id,nombre',
            'detalles.material:material_id,nombre'
        ])
        ->whereHas('detalles', function($q) use ($materialId) {
            $q->where('material_id', $materialId);
        })
        ->get();

        return response()->json($bloqueos);
    }
}
