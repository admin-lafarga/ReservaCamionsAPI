<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoGrupoRequest;
use App\Http\Requests\UpdateBloqueoGrupoRequest;
use App\Models\BloqueoGrupo;
use App\Models\BloqueoGrupoDetalle;

class BloqueoGrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloqueoGrupos = BloqueoGrupo::with([
            'tipoproveedor:tipo_proveedor_id,nombre',
            'detalles.material:material_id,nombre_material'
        ])->where('activo','!=', 0)->get();

        return response()->json($bloqueoGrupos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreBloqueoGrupoRequest $request)
    {
        $validated = $request->validated();

        $bloqueoGrupo = [
            'tipo_proveedor_id' => (int) $validated['tipo_proveedor_id'],
            'fecha_desde' => $validated['fecha_desde'],
            'fecha_hasta' => $validated['fecha_hasta'],
            'usuario_id' => auth()->id(),
            'cantidad_total' => $validated['cantidad_total'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
            'activo' => true,
        ];

        $grupo = BloqueoGrupo::create($bloqueoGrupo);

        foreach ($validated['detalles'] as $detalle) {
            $bloqueoGrupoDetalle = [
                'bloqueo_grupo_id' => $grupo->bloqueo_grupo_id,
                'material_id' => $detalle['material_id'],
                'activo' => true,
            ];
            BloqueoGrupoDetalle::create($bloqueoGrupoDetalle);
        }

        return response()->json($grupo, 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(BloqueoGrupo $bloqueoGrupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueoGrupo $bloqueoGrupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBloqueoGrupoRequest $request, int $id)
    {
        $validated = $request->validated();

        $grupo = BloqueoGrupo::findOrFail($id);

        $grupo->update([
            'tipo_proveedor_id' => (int) $validated['tipo_proveedor_id'],
            'fecha_desde' => $validated['fecha_desde'],
            'fecha_hasta' => $validated['fecha_hasta'],
            'cantidad_total' => $validated['cantidad_total'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
        ]);

        $grupo->detalles()->delete();

        foreach ($validated['detalles'] as $detalle) {
            $bloqueoGrupoDetalle = [
                'bloqueo_grupo_id' => $grupo->id,
                'material_id' => $detalle['material_id'],
                'activo' => true,
            ];
            BloqueoGrupoDetalle::create($bloqueoGrupoDetalle);
        }

        return response()->json($grupo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $grupo = BloqueoGrupo::findOrFail($id);

        // Actualitza el camp 'activo' a false per al grupo
        $grupo->activo = false;
        $grupo->save();

        // També actualitzem 'activo' a false per als detalls relacionats
        $grupo->detalles()->update(['activo' => false]);

        return response()->json(['message' => 'BloqueoGrupo i detalls desactivats correctament']);
    }
}
