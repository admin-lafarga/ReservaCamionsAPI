<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoGrupoDetalleRequest;
use App\Http\Requests\UpdateBloqueoGrupoDetalleRequest;
use App\Models\BloqueoGrupoDetalle;

class BloqueoGrupoDetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = BloqueoGrupoDetalle::with([
        'material',
        'bloqueoGrupo.proveedor'
        ])->get();

        return response()->json($datos);
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
    public function store(StoreBloqueoGrupoDetalleRequest $request)
    {
        $detalle = BloqueoGrupoDetalle::create($request->validated());
        return response()->json($detalle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoGrupoDetalle $bloqueoGrupoDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueoGrupoDetalle $bloqueoGrupoDetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoGrupoDetalleRequest $request, BloqueoGrupoDetalle $bloqueoGrupoDetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoGrupoDetalle $bloqueoGrupoDetalle)
    {
        //
    }
}
