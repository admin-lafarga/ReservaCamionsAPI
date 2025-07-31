<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\DocumentosReserva;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with('documentos')->get();
        return response()->json($reservas);
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

    public function store(StoreReservaRequest $request)
    {
        $validatedData = $request->validated();

        $reserva = Reserva::create([
            'tipo_camion_id'      => $validatedData['tipo_camion_id'],
            'tipo_material1_id'   => $validatedData['tipo_material1_id'],
            'tipo_material2_id'   => $validatedData['tipo_material2_id'] ?? null,
            'proveedor_id'        => $validatedData['proveedor_id'],
            'transporte_id'       => $validatedData['transporte_id'],
            'muelle1_id'          => $validatedData['muelle1_id'],
            'muelle2_id'          => $validatedData['muelle2_id'] ?? null,
            'status_id'           => $validatedData['status_id'],
            'empresa_id'          => 1,
            'cantidad1'           => $validatedData['cantidad1'],
            'cantidad2'           => $validatedData['cantidad2'] ?? null,
            'pedido_LF'           => $validatedData['pedido_LF'] ?? null,
            'matricula_camion'    => $validatedData['matricula_camion'],
            'inicio1'             => $validatedData['inicio1'],
            'fin1'                => $validatedData['fin1'],
            'inicio2'             => $validatedData['inicio2'] ?? null,
            'fin2'                => $validatedData['fin2'] ?? null,
            'es_aduana'           => $validatedData['es_aduana'] ?? false,
            'notas'               => $validatedData['notas'] ?? null,
            'tel1'                => $validatedData['tel1'] ?? null,
            'duracion1'           => $validatedData['duracion1'],
            'duracion2'           => $validatedData['duracion2'] ?? null,
        ]);

        // AMB MODELS
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $ruta = $archivo->store("reservas{$reserva->id}");

                $nombreOriginal = $archivo->getClientOriginalName();

                $reserva->documentos()->create([
                    'url' => $ruta,
                    'name' => $nombreOriginal
                ]);
            }
        }

        return response()->json([
            'message' => 'Reserva creada correctament.',
            'data' => $reserva,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        $validatedData = $request->validated();

        $reserva->update([
            'tipo_camion_id'      => $validatedData['tipo_camion_id'],
            'tipo_material1_id'   => $validatedData['tipo_material1_id'],
            'tipo_material2_id'   => $validatedData['tipo_material2_id'] ?? null,
            'proveedor_id'        => $validatedData['proveedor_id'],
            'transporte_id'       => $validatedData['transporte_id'],
            'muelle1_id'          => $validatedData['muelle1_id'],
            'muelle2_id'          => $validatedData['muelle2_id'] ?? null,
            'status_id'           => $validatedData['status_id'],
            'cantidad1'           => $validatedData['cantidad1'],
            'cantidad2'           => $validatedData['cantidad2'] ?? null,
            'pedido_LF'           => $validatedData['pedido_LF'] ?? null,
            'matricula_camion'    => $validatedData['matricula_camion'],
            'inicio1'             => $validatedData['inicio1'],
            'fin1'                => $validatedData['fin1'],
            'inicio2'             => $validatedData['inicio2'] ?? null,
            'fin2'                => $validatedData['fin2'] ?? null,
            'es_aduana'           => $validatedData['es_aduana'] ?? false,
            'notas'               => $validatedData['notas'] ?? null,
            'tel1'                => $validatedData['tel1'] ?? null,
            'duracion1'           => $validatedData['duracion1'],
            'duracion2'           => $validatedData['duracion2'] ?? null,
        ]);

        return response()->json([
            'message' => 'Reserva actualitzada correctament.',
            'data' => $reserva
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        //
    }
}
