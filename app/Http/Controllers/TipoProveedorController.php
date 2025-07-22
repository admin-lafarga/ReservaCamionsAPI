<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipo_ProveedorRequest;
use App\Http\Requests\UpdateTipo_ProveedorRequest;
use App\Models\Tipo_Proveedor;

class TipoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposProveidor = Tipo_Proveedor::all();
        return response()->json($tiposProveidor);
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
    public function store(StoreTipo_ProveedorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo_Proveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipo_Proveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipo_ProveedorRequest $request, Tipo_Proveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipo_Proveedor $tipus_Proveidor)
    {
        //
    }
}
