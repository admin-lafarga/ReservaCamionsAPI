<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoKgMaterialRequest;
use App\Http\Requests\UpdateBloqueoKgMaterialRequest;
use App\Models\BloqueoKgMaterial;

class BloqueoKgMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bloqueosKgMaterial = BloqueoKgMaterial::all();
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
    public function store(StoreBloqueoKgMaterialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoKgMaterial $bloqueigKGMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueoKgMaterial $bloqueigKGMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoKgMaterialRequest $request, BloqueoKgMaterial $bloqueigKGMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoKgMaterial $bloqueigKGMaterial)
    {
        //
    }
}
