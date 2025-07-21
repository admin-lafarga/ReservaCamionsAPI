<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueo_Kg_MaterialRequest;
use App\Http\Requests\UpdateBloqueo_Kg_MaterialRequest;
use App\Models\Bloqueo_Kg_Material;

class Bloqueo_Kg_MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloqueosKgMaterial = Bloqueo_Kg_Material::all();
        return response()->json($users);
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
    public function store(StoreBloqueo_Kg_MaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bloqueo_Kg_Material $bloqueig_KG_Material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bloqueo_Kg_Material $bloqueig_KG_Material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueo_Kg_MaterialRequest $request, Bloqueo_Kg_Material $bloqueig_KG_Material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloqueo_Kg_Material $bloqueig_KG_Material)
    {
        //
    }
}
