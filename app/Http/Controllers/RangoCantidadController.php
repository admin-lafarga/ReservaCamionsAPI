<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRangoCantidadRequest;
use App\Http\Requests\UpdateRangoCantidadRequest;
use App\Models\RangoCantidad;

class RangoCantidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rangocantidad = RangoCantidad::all();
        return response()->json($rangocantidad);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRangoCantidadRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RangoCantidad $cantidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRangoCantidadRequest $request, RangoCantidad $cantidad)
    {
        $cantidad->update($request->validated());
        return response()->json($cantidad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RangoCantidad $cantidad)
    {
        //
    }
}
