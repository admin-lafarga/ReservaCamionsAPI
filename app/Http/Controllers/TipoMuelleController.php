<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoMuelleRequest;
use App\Http\Requests\UpdateTipoMuelleRequest;
use App\Models\TipoMuelle;

class TipoMuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposMuelle = TipoMuelle::all();
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
    public function store(StoreTipoMuelleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoMuelle $tipusMoll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoMuelle $tipusMoll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoMuelleRequest $request, TipoMuelle $tipusMoll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoMuelle $tipusMoll)
    {
        //
    }
}
