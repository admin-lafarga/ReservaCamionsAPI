<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreControlMaterialMuelleRequest;
use App\Http\Requests\UpdateControlMaterialMuelleRequest;
use Illuminate\Http\Request;
use App\Models\ControlMaterialMuelle;
use App\Models\TipoCamion;
use Illuminate\Support\Collection;

class ControlMaterialMuelleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreControlMaterialMuelleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ControlMaterialMuelle $controlMaterialMuelle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateControlMaterialMuelleRequest $request, ControlMaterialMuelle $controlMaterialMuelle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ControlMaterialMuelle $controlMaterialMuelle)
    {
        //
    }

    // Retorna retorna la info dels camions, juntament amb els molls que hi ha disponible per aquests dos
    public function materialCamioMuelle(Request $request)
    {
        $request->validate([
            'materiales' => 'required|array|min:1',
            'materiales.*' => 'integer|exists:materiales,material_id',
            'restricciones' => 'boolean|required'
        ]);

        $materiales = $request->input('materiales');

        $resultats = ControlMaterialMuelle::with(['muelle', 'tipoCamion'])
            ->whereIn('material_id', $materiales)
            ->get();

        $muellesPorMaterial = $resultats
            ->groupBy('material_id')
            ->map(function (Collection $items) {
                return $items->pluck('muelle_id')->unique()->values();
            });

        $muellesComunsIds = $muellesPorMaterial->reduce(function ($carry, $ids) {
            return $carry === null ? $ids : $carry->intersect($ids)->values();
        }, null);

        $muellesDisponibles = $resultats
            ->pluck('muelle')
            ->unique('muelle_id')
            ->whereIn('muelle_id', $muellesComunsIds)
            ->values();

        $camionesPorMaterial = $resultats
            ->groupBy('material_id')
            ->map(fn ($items) => $items->pluck('tipo_camion_id')->unique()->values());

        $camionesComunsIds = $camionesPorMaterial->reduce(fn ($carry, $ids) => $carry === null ? $ids : $carry->intersect($ids)->values(), null);

        $camionesDisponibles = TipoCamion::whereIn('tipo_camion_id', $camionesComunsIds)->get();

        return response()->json([
            'muellesDisponibles' => $muellesDisponibles,
            'camionesDisponibles' => $camionesDisponibles,
        ]);
    }
}
