<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorarios_MuelleRequest;
use App\Http\Requests\UpdateHorarios_MuelleRequest;
use App\Models\Horarios_Muelle;

class Horarios_MuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horariosMuelle = Horarios_Muelle::all();
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
    public function store(StoreHorarios_MuelleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Horarios_Muelle $moll_Horari)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horarios_Muelle $moll_Horari)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHorarios_MuelleRequest $request, Horarios_Muelle $moll_Horari)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horarios_Muelle $moll_Horari)
    {
        //
    }
}
