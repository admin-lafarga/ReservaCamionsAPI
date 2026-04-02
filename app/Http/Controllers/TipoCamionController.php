<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoCamionRequest;
use App\Http\Requests\UpdateTipoCamionRequest;
use App\Models\TipoCamion;
use Illuminate\Http\JsonResponse;

class TipoCamionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TipoCamion::class, 'tipocamione');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposCamion = TipoCamion::all();
        return response()->json($tiposCamion);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoCamionRequest $request): JsonResponse
    {
        $tipoCamion = TipoCamion::create($request->validated());

        return response()->json([
            'message' => 'TipoCamion creado correctamente',
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipoCamion = TipoCamion::find($id);

        if (!$tipoCamion) {
            return response()->json(['message' => 'No trobat'], 404);
        }

        return response()->json($tipoCamion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoCamionRequest $request, TipoCamion $tipocamione): JsonResponse
    {

        // Abans validem que no hi hagi alguna reserva que tingui ja assignat aquest tipo camión.
        $exists = $tipocamione->reservas()->exists();

        if ($exists) {
            return response()->json([
                // Assignem id 1 per poder recuperar el missatge en el frontend
                'id' => 1,
                'message' => 'No se puede actualizar este TipoCamion porque ya tiene reservas asociadas.',
            ], 400);
        }

        $tipocamione->update($request->validated());

        return response()->json([
            'message' => 'TipoCamion actualizado correctamente',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoCamion $tipocamione)
    {
        $exists = $tipocamione->reservas()->exists();

        if ($exists) {
            return response()->json([
                // Assignem id 1 per poder recuperar el missatge en el frontend
                'id' => 1,
                'message' => 'No se puede eliminar este TipoCamion porque ya tiene reservas asociadas.',
            ], 400);
        }

        $tipocamione->delete();
    }
}
