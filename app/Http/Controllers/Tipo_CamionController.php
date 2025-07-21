<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipo_CamionRequest;
use App\Http\Requests\UpdateTipo_CamionRequest;
use App\Models\Tipo_Camion;

class Tipo_CamionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposCamion = Tipo_Camion::all();
        return response()->json($tiposCamion);
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
    public function store(StoreTipo_CamionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo_Camion $tipus_Camio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipo_Camion $tipus_Camio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipo_CamionRequest $request, Tipo_Camion $tipus_Camio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipo_Camion $tipus_Camio)
    {
        //
    }
}
