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
        $muelles = Muelle::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Muelle $moll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Muelle $moll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMuelleRequest $request, Muelle $moll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Muelle $moll)
    {
        //
    }
}
