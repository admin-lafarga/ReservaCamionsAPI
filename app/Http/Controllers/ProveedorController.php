<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProveedorRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::with('tipoProveedor:tipo_proveedor_id,nombre')->get();
        return response()->json($proveedores);
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
    public function store(StoreProveedorRequest $request)
    {
        $proveedor = Proveedor::create($request->validated());

        return response()->json([
            'message' => 'Proveedor creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json(['message' => 'No trobat'], 404);
        }

        return response()->json($proveedor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProveedorRequest $request, Proveedor $proveedore)
    {
        // Validar y actualizar el proveedor con los datos validados del request
        $proveedore->update($request->validated());

        return response()->json([
            'message' => 'Proveedor actualizado correctamente.',
            'proveedor' => $proveedore
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    // S'ha canviat de convenció perquè estava donant errors
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $tieneReservas = $proveedor->reservas()
            ->whereDate('inicio1', '>=', Carbon::today())
            ->exists();

        if ($tieneReservas) {
            return response()->json([
                'id' => 1,
                'message' => 'No se puede desactivar el proveedor porque tiene reservas activas desde hoy en adelante.'
            ], 422);
        }

        $proveedor->estado = false;
        $proveedor->save();

        return response()->json([
            'message' => 'Proveedor desactivado correctamente.'
        ]);
    }
}
