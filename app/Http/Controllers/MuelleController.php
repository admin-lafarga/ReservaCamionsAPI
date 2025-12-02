<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMuelleRequest;
use App\Http\Requests\UpdateMuelleRequest;
use App\Models\Muelle;

class MuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muelles = Muelle::with([
            'empresa_lfycs:empresa_lfycs_id,nombre',
            'horarios:horarios_muelle_id,muelle_id,dia_semana,inicio,fin',
        ])->get();

        return response()->json($muelles);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMuelleRequest $request)
    {
        $muelle = Muelle::create($request->validated());

        return response()->json([
            'message' => 'Muelle creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Muelle $muelle)
    {
        return response()->json($muelle);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMuelleRequest $request, Muelle $muelle)
    {
        $muelle->update($request->validated());

        return response()->json([
            'message' => 'Muelle actualizado correctamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Elimina también los horarios asociados debido a la relación de dependencia en la base de datos.
     */
    public function destroy(Muelle $muelle)
    {
        $muelle->delete();

        return response()->json([
            'message' => 'Muelle & Horarios eliminados correctamente',
        ]);
    }
}
