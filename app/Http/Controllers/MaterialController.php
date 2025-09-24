<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\ControlMaterialMuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ids = [];

        if ($request->has('id1')) {
            $ids[] = $request->query('id1');
        }
        if ($request->has('id2')) {
            $ids[] = $request->query('id2');
        }

        $query = Material::with([
            'controlMaterialMuelle.tipoCamion:tipo_camion_id,nombre',
            'controlMaterialMuelle.muelle:muelle_id,nombre_muelle'
        ]);

        if (!empty($ids)) {
            $materials = $query->whereIn('material_id', $ids)->get();
        } else {
            $materials = $query->get();
        }

        return response()->json($materials);
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
    public function store(StoreMaterialRequest $request)
    {
        $data = $request->validated();

        // Ho faig amb un transaction, assegurar que es completin tots els inserts
        // i en el cas que en falli algun no es crei el material.
        DB::beginTransaction();
        try {
            // Crear el material
            $material = Material::create([
                'codigo_sap' => $data['codigo_sap'],
                'nombre_material' => $data['nombre'],
                'estado' => $data['estado'],
            ]);

            // Crear control_material_muelle combinando cada truck con cada muelle
            foreach ($data['trucks'] as $truck) {
                foreach ($data['muelles'] as $muelle) {
                    ControlMaterialMuelle::create([
                        'material_id' => $material->material_id,
                        'tipo_camion_id' => $truck['tipo_camion_id'],
                        'muelle_id' => $muelle['muelle_id'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Material y controles creados correctamente.',
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el material.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $materiale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $materiale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequest $request, Material $materiale)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            // Actualitzar camps del material
            $materiale->update([
                'codigo_sap' => $data['codigo_sap'],
                'nombre_material' => $data['nombre'],
                'estado' => $data['estado'],
                // 'camiones_permitidos' => 'tots',
                // 'muelles_permitidos' => 'tots',
                // 'max_concurrencia' => 'infinit',
            ]);

            // Obtenir combinacions actuals
            $existingControls = $materiale->controlMaterialMuelle()->get()->map(function ($item) {
                return [
                    'tipo_camion_id' => $item->tipo_camion_id,
                    'muelle_id' => $item->muelle_id,
                ];
            })->toArray();

            // Obtenir combinacions noves des del formulari
            $newControls = [];
            foreach ($data['trucks'] as $truck) {
                foreach ($data['muelles'] as $muelle) {
                    $newControls[] = [
                        'tipo_camion_id' => $truck['tipo_camion_id'],
                        'muelle_id' => $muelle['muelle_id'],
                    ];
                }
            }

            // Afegir els nous que no existeixen
            foreach ($newControls as $control) {
                if (!in_array($control, $existingControls)) {
                    ControlMaterialMuelle::create([
                        'material_id' => $materiale->material_id,
                        'tipo_camion_id' => $control['tipo_camion_id'],
                        'muelle_id' => $control['muelle_id'],
                    ]);
                }
            }

            // Eliminar els que ja no hi són al formulari
            foreach ($existingControls as $control) {
                if (!in_array($control, $newControls)) {
                    ControlMaterialMuelle::where('material_id', $materiale->material_id)
                        ->where('tipo_camion_id', $control['tipo_camion_id'])
                        ->where('muelle_id', $control['muelle_id'])
                        ->delete();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Material actualitzat correctament.',
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualitzar el material.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $materiale)
    {
        //
    }

    // Controlador
    // public function getMaterials(Request $request)
    // {
    //     $ids = [];

    //     if ($request->has('id1')) {
    //         $ids[] = $request->query('id1');
    //     }
    //     if ($request->has('id2')) {
    //         $ids[] = $request->query('id2');
    //     }

    //     if (empty($ids)) {
    //         return response()->json(['error' => 'No s’han passat IDs'], 400);
    //     }

    //     $materials = Material::whereIn('material_id', $ids)->get();

    //     return response()->json($materials);
    // }
}
