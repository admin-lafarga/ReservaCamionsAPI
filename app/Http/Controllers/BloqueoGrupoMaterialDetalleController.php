<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoGrupoMaterialMaterialDetalleRequest;
use App\Http\Requests\UpdateBloqueoGrupoMaterialDetalleRequest;
use App\Models\BloqueoGrupoMaterialDetalle;

class BloqueoGrupoMaterialDetalleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BloqueoGrupoMaterialDetalle::class, 'detalle');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = BloqueoGrupoMaterialDetalle::with([
        'material',
        'BloqueoGrupoMaterial.proveedor'
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
    public function store(StoreBloqueoGrupoMaterialMaterialDetalleRequest $request)
    {
        $detalle = BloqueoGrupoMaterialDetalle::create($request->validated());
        return response()->json($detalle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoGrupoMaterialDetalle $BloqueoGrupoMaterialDetalle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BloqueoGrupoMaterialDetalle $BloqueoGrupoMaterialDetalle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoGrupoMaterialDetalleRequest $request, BloqueoGrupoMaterialDetalle $BloqueoGrupoMaterialDetalle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoGrupoMaterialDetalle $BloqueoGrupoMaterialDetalle)
    {
        //
    }
}
