<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipo_MuelleRequest;
use App\Http\Requests\UpdateTipo_MuelleRequest;
use App\Models\Tipo_Muelle;

class TipoMuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMuelle = Tipo_Muelle::all();
        return response()->json($tiposMuelle);
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
    public function store(StoreTipo_MuelleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipo_Muelle $tipus_Moll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipo_Muelle $tipus_Moll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipo_MuelleRequest $request, Tipo_Muelle $tipus_Moll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipo_Muelle $tipus_Moll)
    {
        //
    }
}
