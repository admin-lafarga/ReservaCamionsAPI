<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipo_MaterialRequest;
use App\Http\Requests\UpdateTipo_MaterialRequest;
use App\Models\Tipo_Material;

class Tipo_MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMaterial = Tipo_Material::all();
        return response()->json($tiposMaterial);
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
    public function store(StoreTipo_MaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo_Material $tipus_Material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipo_Material $tipus_Material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipo_MaterialRequest $request, Tipo_Material $tipus_Material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipo_Material $tipus_Material)
    {
        //
    }
}
