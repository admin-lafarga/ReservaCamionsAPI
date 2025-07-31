<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoProveedorRequest;
use App\Http\Requests\UpdateTipoProveedorRequest;
use App\Models\TipoProveedor;

class TipoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposProveidor = TipoProveedor::all();
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
    public function store(StoreTipoProveedorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoProveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoProveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoProveedorRequest $request, TipoProveedor $tipus_Proveidor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoProveedor $tipus_Proveidor)
    {
        //
    }
}
