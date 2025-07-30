<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoCamionRequest;
use App\Http\Requests\UpdateTipoCamionRequest;
use App\Models\TipoCamion;

class TipoCamionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposCamion = TipoCamion::all();
        return response()->json($tiposCamion);
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
    public function store(StoreTipoCamionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoCamion $tipusCamio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoCamion $tipusCamio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoCamionRequest $request, TipoCamion $tipusCamio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoCamion $tipusCamio)
    {
        //
    }
}
