<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestriccionRequest;
use App\Http\Requests\UpdateRestriccionRequest;
use App\Models\Restriccion;

class RestriccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restricciones = Restriccion::all();
        return response()->json($restricciones);
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
    public function store(StoreRestriccionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Restriccion $restriccio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restriccion $restriccio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestriccionRequest $request, Restriccion $restriccio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restriccion $restriccio)
    {
        //
    }
}
