<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMuelleRequest;
use App\Http\Requests\UpdateMuelleRequest;
use App\Models\Muelle;

class MuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muelles = Muelle::with('empresa:empresa_id,nombre')->get();
        return response()->json($muelles);
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
    public function store(StoreMuelleRequest $request)
    {
        $muelle = Muelle::create($request->validated());

        return response()->json([
            'message' => 'Muelle creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Muelle $muelle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Muelle $muelle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMuelleRequest $request, Muelle $muelle)
    {
        $muelle->update($request->validated());

        return response()->json([
            'message' => 'Muelle actualizado correctamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Muelle $muelle)
    {
        //
    }
}
