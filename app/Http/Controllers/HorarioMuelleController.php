<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorarioMuelleRequest;
use App\Http\Requests\UpdateHorarioMuelleRequest;
use App\Models\HorarioMuelle;

class HorarioMuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horariosMuelle = HorarioMuelle::with('muelle')->get();
        return response()->json($horariosMuelle);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHorarioMuelleRequest $request)
    {
        $horario = HorarioMuelle::create($request->validated());

        return response()->json([
            'message' => 'Horario creado correctamente',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HorarioMuelle $moll_Horari)
    {
        //
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateHorarioMuelleRequest $request, HorarioMuelle $horariosMuelle)
    {
        $horariosMuelle->update($request->validated());

        return response()->json([
            'message' => 'Horario actualizado correctamente',
        ], 200);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(HorarioMuelle $horariosMuelle)
    {
        $horariosMuelle->delete();

        return response()->json([
            'message' => 'Horario eliminado correctamente'
        ], 200);
    }
}
