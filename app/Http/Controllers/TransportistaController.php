<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransportistaRequest;
use App\Http\Requests\UpdateTransportistaRequest;
use App\Models\Transportista;
use App\Models\Entidad;
use Carbon\Carbon;



class TransportistaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Transportista::class, 'transportista');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transportistas = Transportista::with(['entidad'])->get();
        return response()->json($transportistas);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransportistaRequest $request)
    {
        $entidad = Entidad::create($request->validated()['entidad']);
        
        $entidad->transportista()->create([
            'puede_gestionar' => $request->validated()['puede_gestionar'],          
        ]);
        return response()->json([
            'message' => 'Carrier creado correctamente.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transportista = Transportista::find($id);

        if (!$transportista) {
            return response()->json(['message' => 'No trobat'], 404);
        }

        return response()->json($transportista);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransportistaRequest $request, Transportista $transportista)
    {
        $transportista->update($request->validated());
        $transportista->entidad->update($request->validated()['entidad']);

        return response()->json([
            'message' => 'Transportista actualizado correctamente.',
            'data' => $transportista
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transportista $transportista)
    {
         $tieneReservas = $transportista->reservas()
            ->whereDate('inicio', '>=', Carbon::today())
            ->exists();

        if ($tieneReservas) {
            return response()->json([
                'proveedor' => $transportista,
                'message' => 'No se puede desactivar el proveedor porque tiene reservas activas',
            ], 422);
        }
        $transportista->entidad()->delete();
        $transportista->delete();

        return response()->json([
            'message' => 'Proveedor desactivado correctamente.'
        ]);

    }
}
