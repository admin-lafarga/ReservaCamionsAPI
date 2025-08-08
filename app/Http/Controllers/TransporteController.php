<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransporteRequest;
use App\Http\Requests\UpdateTransporteRequest;
use App\Models\Transporte;

class TransporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transportes = Transporte::with('proveedor')->get();
        return response()->json($transportes);
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
    public function store(StoreTransporteRequest $request)
    {
        Transporte::create($request->validated());
        return response()->json([
            'message' => 'Transportista creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transporte = Transporte::find($id);

        if (!$transporte) {
            return response()->json(['message' => 'No trobat'], 404);
        }

        return response()->json($transporte);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transporte $transportista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransporteRequest $request, Transporte $transportista)
    {
        $transportista->update($request->validated());

        return response()->json([
            'message' => 'Transportista actualizado correctamente.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transporte $transportista)
    {
        //
    }
}
