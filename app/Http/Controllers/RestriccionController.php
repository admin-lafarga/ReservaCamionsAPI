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
     * Store a newly created resource in storage.
     */
    public function store(StoreRestriccionRequest $request)
    {
        Restriccion::create($request->validated());

        return response()->json([
            'message' => 'Restriccion añadida correctamente'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restriccion $restriccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestriccionRequest $request, Restriccion $restriccion)
    {
        $restriccio->update($request->validated());
        
        return response()->json([
            'message' => 'Restriccion actualizada correctamente',
            'data' => $restriccio,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restriccion $restriccion)
    {
        $restriccion->delete();

        return response()->json([
            'message' => 'Restriccion eliminada correctamente'
        ]);
    }
}
