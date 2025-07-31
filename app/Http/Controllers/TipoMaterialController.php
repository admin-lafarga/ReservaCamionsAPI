<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoMaterialRequest;
use App\Http\Requests\UpdateTipoMaterialRequest;
use App\Models\TipoMaterial;

class TipoMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMaterial = TipoMaterial::all();
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
    public function store(StoreTipoMaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoMaterial $tipusMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoMaterial $tipusMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoMaterialRequest $request, TipoMaterial $tipusMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoMaterial $tipusMaterial)
    {
        //
    }
}
