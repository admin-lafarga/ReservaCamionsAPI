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
        $horariosMuelle = HorarioMuelle::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HorarioMuelle $moll_Horari)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HorarioMuelle $moll_Horari)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHorarioMuelleRequest $request, Horarios_Muelle $moll_Horari)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HorarioMuelle $moll_Horari)
    {
        //
    }
}
