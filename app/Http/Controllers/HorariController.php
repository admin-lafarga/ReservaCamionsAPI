<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorariRequest;
use App\Http\Requests\UpdateHorariRequest;
use App\Models\Horari;

class HorariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horaris = Horari::all();
        return response()->json($horaris);
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
    public function store(StoreHorariRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Horari $horari)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horari $horari)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHorariRequest $request, Horari $horari)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horari $horari)
    {
        //
    }
}
