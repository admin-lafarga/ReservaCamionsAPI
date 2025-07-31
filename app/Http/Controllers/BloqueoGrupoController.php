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
            'proveedor:tipo_proveedor_id,nombre',
            'detalles.material:material_id,nombre_material'
        ])->get();

        return response()->json($bloqueoGrupos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreBloqueoGrupoRequest $request)
    {
        $validated = $request->validated();

        $bloqueoGrupo = [
            'tipo_proveedor_id' => $validated['tipo_proveedor'],
            'fecha_inicio' => $validated['fecha_inicio'] ?? null,
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'usuario_id' => auth()->id(),
            'cantidad_total' => $validated['cantidad_total'],
            'cantidad_disponible' => $validated['cantidad_disponible'],
        ];

        $grupo = BloqueoGrupo::create($bloqueoGrupo);

        foreach ($validated['detalles'] as $detalle) {
            $bloqueoGrupoDetalle = [
                'bloqueo_grupo_id' => $grupo->id,
                'material_id' => $detalle['material_id'],
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
    public function update(UpdateBloqueoGrupoRequest $request, BloqueoGrupo $bloqueoGrupo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoGrupo $bloqueoGrupo)
    {
        //
    }
}
