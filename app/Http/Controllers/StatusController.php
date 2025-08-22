<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::all();
        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        $status = Status::create($request->validated());

        return response()->json([
            'message' => 'Status creat correctament',
            'data' => $status
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return response()->json($status);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
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
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json([
            'message' => 'Status eliminat correctament'
        ]);
    }
}
