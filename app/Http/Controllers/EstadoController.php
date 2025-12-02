<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\UpdateEstadoRequest;
use App\Models\Estado;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Estado::all();
        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstadoRequest $request)
    {
        $status = Estado::create($request->validated());

        return response()->json([
            'message' => 'Status creat correctament',
            'data' => $status
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estado $status)
    {
        return response()->json($status);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstadoRequest $request, Estado $status)
    {
        $status->update($request->validated());

        return response()->json([
            'message' => 'Status actualitzat correctament',
            'data' => $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estado $status)
    {
        $status->delete();

        return response()->json([
            'message' => 'Status eliminat correctament'
        ]);
    }
}
