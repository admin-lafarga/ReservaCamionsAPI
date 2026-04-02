<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Models\Rol;

class RolController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Rol::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles);
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
    public function store(StoreRolRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $tipus_Usuari)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $tipus_Usuari)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRolRequest $request, Rol $tipus_Usuari)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rol $tipus_Usuari)
    {
        //
    }
}
