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
    public function __construct()
    {
        $this->authorizeResource(Material::class, 'material');
    }

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
            'tipo_camiones',
            'muelles'
        ]);

        

        if (!empty($ids)) {
            $materials = $query->whereIn('material_id', $ids)->get();
        } else {
            $materials = $query->get();
        }

        return response()->json($materials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        $transportista = $request->validated();
        

        // Ho faig amb un transaction, assegurar que es completin tots els inserts
        // i en el cas que en falli algun no es crei el material.
        try {
            DB::transaction(function () use ($transportista, $request) {
                // Crear el material
                $material = Material::create([
                    'codigo_sap' => $transportista['codigo_sap'],
                    'nombre' => $transportista['nombre'],
                ]);
                
                // Relacionar tipo_camiones
                foreach($request['tipo_camiones'] as $camion ){
                    $material->tipo_camiones()->attach($camion['tipo_camion_id']);
                }

                 // Relacionar muelles
                 foreach($request['muelles'] as $muelle ){
                    $material->muelles()->attach($muelle['muelle_id']);
                 }
            });

            return response()->json([
                'message' => 'Material creado correctamente.',
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al crear el material.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return response()->json($material);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        $data = $request->validated();

        try {
            DB::transaction(function ( ) use ($material, $data) {
                $material->update([
                    'codigo_sap' => $data['codigo_sap'],
                    'nombre' => $data['nombre'],
                ]);

                $material->tipo_camiones()->sync(
                    collect($data['tipo_camiones'])->pluck('tipo_camion_id')->toArray()
                );
                $material->muelles()->sync(
                    collect($data['muelles'])->pluck('muelle_id')->toArray()
                );
                
            });

            return response()->json([
                'message' => 'Material actualitzado correctamente.',
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar el material.',
                'error' => $e->getMessage()
            ], 500);
        }

        // $request->validate([
        //     'nombre' => 'required|string|max:255',
        //     'tipo_camion_ids' => 'array',
        //     'muelle_ids' => 'array',
        // ]);

        // $material->update([
        //     'nombre' => $request->nombre
        // ]);

        // // Actualizar relaciones
        // $material->tiposCamion()->sync($request->tipo_camion_ids ?? []);
        // $material->muelles()->sync($request->muelle_ids ?? []);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->tipo_camiones()->detach();
        $material->muelles()->detach();
        $material->delete();

        return response()->json([
            'message' => 'Material eliminado correctamente',
        ], 200);
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
