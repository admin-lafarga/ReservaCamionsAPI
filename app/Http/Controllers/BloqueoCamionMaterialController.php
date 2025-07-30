<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoCamionMaterialRequest;
use App\Http\Requests\UpdateBloqueoCamionMaterialRequest;
use App\Models\BloqueoCamionMaterial;

class BloqueoCamionMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloqueoCamionesMateriales = BloqueoCamionMaterial::all();
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
    public function store(StoreBloqueoCamionMaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoCamionMaterial $bloqueigCamioMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueoCamionMaterial $bloqueigCamioMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoCamionMaterialRequest $request, BloqueoCamionMaterial $bloqueigCamioMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoCamionMaterial $bloqueigCamioMaterial)
    {
        //
    }
}
