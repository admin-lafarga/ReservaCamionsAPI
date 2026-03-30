<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpresaLfycsRequest;
use App\Http\Requests\UpdateEmpresaLfycsRequest;
use App\Models\EmpresaLfycs;

class EmpresaLfycsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = EmpresaLfycs::with([
            'muelles:muelle_id,nombre,empresa_lfycs_id',
        ])->get();

        return response()->json($empresas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpresaLfycsRequest $request)
    {
        $empresa = EmpresaLfycs::create($request->validated());

        return response()->json([
            'message' => 'Empresa creado correctamente.',
            'data' => $empresa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmpresaLfycs $empresa)
    {
        return response()->json($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmpresaLfycsRequest $request, EmpresaLfycs $empresa)
    {

        $empresa->update($request->validated());

        return response()->json([
            'message' => 'Empresa actualizado correctamente.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmpresaLfycs $empresa)
    {
        if ($empresa->muelles()->exists() || $empresa->reservas()->exists()) {
                return response()->json([
                    'message' => 'No se puede eliminar la empresa porque tiene muelles/reservas asociados.'
                ], 400);
        }

        $empresa->delete();

        return response()->json([
            'message' => 'Empresa eliminado correctamente.'
        ]);
        
    }
}
