<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueo_Camion_MaterialRequest;
use App\Http\Requests\UpdateBloqueo_Camion_MaterialRequest;
use App\Models\Bloqueo_Camion_Material;

class Bloqueo_Camion_MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloqueoCamionesMateriales = Bloqueo_Camion_Material::all();
        return response()->json($bloqueoCamionesMateriales);
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
    public function store(StoreBloqueo_Camion_MaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bloqueo_Camion_Material $bloqueig_Camio_Material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bloqueo_Camion_Material $bloqueig_Camio_Material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueo_Camion_MaterialRequest $request, Bloqueo_Camion_Material $bloqueig_Camio_Material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloqueo_Camion_Material $bloqueig_Camio_Material)
    {
        //
    }
}
