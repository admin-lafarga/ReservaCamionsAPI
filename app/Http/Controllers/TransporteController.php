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
        $transportes = Transporte::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transporte $transport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transporte $transport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransporteRequest $request, Transporte $transport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transporte $transport)
    {
        //
    }
}
