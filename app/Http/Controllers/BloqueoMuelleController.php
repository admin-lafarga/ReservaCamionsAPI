<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoMuelleRequest;
use App\Http\Requests\UpdateBloqueoMuelleRequest;
use App\Models\BloqueoMuelle;

class BloqueoMuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BloqueoMuelle::with('muelle')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBloqueoMuelleRequest $request)
    {
        return BloqueoMuelle::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoMuelle $bloqueoMuelle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBloqueoMuelleRequest $request, BloqueoMuelle $bloqueoMuelle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoMuelle $bloqueoMuelle)
    {
        //
    }
}
