<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoProveedorRequest;
use App\Http\Requests\UpdateTipoProveedorRequest;
use App\Models\TipoProveedor;

class TipoProveedorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TipoProveedor::class, 'tipoProveedor');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoProveedor = TipoProveedor::all();
        return response()->json($tipoProveedor);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoProveedorRequest $request)
    {
        TipoProveedor::create($request->validated());

        return response()->json([
            'message' => 'Tipo proveedor añadido correctamente'
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoProveedor $tipoProveedor)
    {
        
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoProveedorRequest $request, TipoProveedor $tipoProveedor)
    {
        $tipoProveedor->update($request->validated());
        
        return response()->json([
            'message' => 'Tipo proveedor actualizado correctamente',
            'data' => $tipoProveedor,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoProveedor $tipoProveedor)
    {
        $tipoProveedor->delete();

        return response()->json([
            'message' => 'Tipo proveedor eliminado correctamente'
        ]);
    }
}
