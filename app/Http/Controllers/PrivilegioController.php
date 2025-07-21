<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrivilegioRequest;
use App\Http\Requests\UpdatePrivilegioRequest;
use App\Models\Privilegio;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $privilegios = Privilegio::all();
        return response()->json($privilegios);
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
    public function store(StorePrivilegioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Privilegio $privilegi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Privilegio $privilegi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrivilegioRequest $request, Privilegio $privilegi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Privilegio $privilegi)
    {
        //
    }
}
