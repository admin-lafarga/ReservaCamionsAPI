<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestriccionRequest;
use App\Http\Requests\UpdateRestriccionRequest;
use App\Models\Restriccion;

class RestriccionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Restriccion::class, 'restriccion');
    }

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
        foreach ($request->all() as $item) {
            Restriccion::firstOrCreate([
                'muelle_id' => $item['muelle_id'],
                'muelle_restringido_id' => $item['muelle_restringido_id'],
            ]);
        }

        return response()->json(['message' => 'Restricciones añadidas'], 201);
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
        $restriccion->update($request->validated());
        
        return response()->json([
            'message' => 'Restriccion actualizada correctamente',
            'data' => $restriccion,
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

    public function bulkDelete(StoreRestriccionRequest $request)
    {
        $this->authorize('delete', new Restriccion());

        foreach ($request->all() as $item) {
            Restriccion::where('muelle_id', $item['muelle_id'])
                ->where('muelle_restringido_id', $item['muelle_restringido_id'])
                ->delete();
        }

        return response()->json(['message' => 'Restricciones eliminadas'], 200);
    }
}
