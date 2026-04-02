<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloqueoMuelleRequest;
use App\Http\Requests\UpdateBloqueoMuelleRequest;
use App\Models\BloqueoMuelle;
use App\Models\Muelle;
use App\Models\Reserva;

class BloqueoMuelleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BloqueoMuelle::class, 'bloqueoMuelle');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BloqueoMuelle::with('muelle')->get();
    }

    /**
     * Store a newly created resource in storage.
     * Validaciones:
     * 1️⃣ Verificar que no haya solapamiento con otros bloqueos del mismo muelle
     * 2️⃣ Verificar que no haya reservas existentes en el período bloqueado
     * 3️⃣ Si muelle_id es null, crear bloqueos para TODOS los muelles
     */
    public function store(StoreBloqueoMuelleRequest $request)
    {
        $validated = $request->validated();

        // Si muelle_id es null, crear bloqueos para TODOS los muelles
        if (is_null($validated['muelle_id'] ?? null)) {
            $muelles = Muelle::all();
            $bloqueosCreados = [];

            foreach ($muelles as $muelle) {
                // Verificar solapamiento para este muelle
                $solapamiento = BloqueoMuelle::where('muelle_id', $muelle->muelle_id)
                    ->where('inicio', '<', $validated['fin'])
                    ->where('fin', '>', $validated['inicio'])
                    ->exists();

                if ($solapamiento) {
                    return response()->json([
                        'message' => "Ya existe un bloqueo en el muelle {$muelle->nombre} que solapa con las fechas seleccionadas.",
                        'muelle_id' => $muelle->muelle_id,
                    ], 422);
                }

                // Verificar reservas existentes para este muelle
                $reservasExistentes = Reserva::where('muelle_id', $muelle->muelle_id)
                    ->where('inicio', '<', $validated['fin'])
                    ->where('fin', '>', $validated['inicio'])
                    ->count();

                if ($reservasExistentes > 0) {
                    return response()->json([
                        'message' => "No se puede crear el bloqueo. Hay {$reservasExistentes} reserva(s) existente(s) en el muelle {$muelle->nombre} durante este período.",
                        'muelle_id' => $muelle->muelle_id,
                        'reservas_count' => $reservasExistentes,
                    ], 422);
                }

                // Crear bloqueo para este muelle
                $bloqueosCreados[] = BloqueoMuelle::create([
                    'muelle_id' => $muelle->muelle_id,
                    'asunto' => $validated['asunto'],
                    'inicio' => $validated['inicio'],
                    'fin' => $validated['fin'],
                ]);
            }

            return response()->json([
                'message' => 'Bloqueos globales creados correctamente para todos los muelles.',
                'data' => $bloqueosCreados,
                'count' => count($bloqueosCreados),
            ], 201);
        }

        // Bloqueo específico para un muelle
        // 1️⃣ Verificar solapamiento con otros bloqueos del mismo muelle
        $solapamiento = BloqueoMuelle::where('muelle_id', $validated['muelle_id'])
            ->where('inicio', '<', $validated['fin'])
            ->where('fin', '>', $validated['inicio'])
            ->exists();

        if ($solapamiento) {
            return response()->json([
                'message' => 'Ya existe un bloqueo en este muelle que solapa con las fechas seleccionadas.',
                'muelle_id' => $validated['muelle_id'],
            ], 422);
        }

        // 2️⃣ Verificar que no haya reservas existentes en el período
        $reservasExistentes = Reserva::where('muelle_id', $validated['muelle_id'])
            ->where('inicio', '<', $validated['fin'])
            ->where('fin', '>', $validated['inicio'])
            ->count();

        if ($reservasExistentes > 0) {
            return response()->json([
                'message' => "No se puede crear el bloqueo. Hay {$reservasExistentes} reserva(s) existente(s) en este período.",
                'muelle_id' => $validated['muelle_id'],
                'reservas_count' => $reservasExistentes,
            ], 422);
        }

        // 3️⃣ Crear el bloqueo
        $bloqueo = BloqueoMuelle::create($validated);

        return response()->json([
            'message' => 'Bloqueo de muelle creado correctamente.',
            'data' => $bloqueo->load('muelle'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BloqueoMuelle $bloqueoMuelle)
    {
        return response()->json($bloqueoMuelle->load('muelle'));
    }

    /**
     * Update the specified resource in storage.
     * Validaciones:
     * 1️⃣ Verificar que no haya solapamiento con otros bloqueos (excluyendo el actual)
     * 2️⃣ Verificar que no haya reservas existentes en el nuevo período
     */
    public function update(UpdateBloqueoMuelleRequest $request, BloqueoMuelle $bloqueoMuelle)
    {
        $validated = $request->validated();

        // 1️⃣ Verificar solapamiento con otros bloqueos (excluyendo el actual)
        $solapamiento = BloqueoMuelle::where('muelle_id', $validated['muelle_id'])
            ->where('bloqueo_muelle_id', '!=', $bloqueoMuelle->bloqueo_muelle_id)
            ->where('inicio', '<', $validated['fin'])
            ->where('fin', '>', $validated['inicio'])
            ->exists();

        if ($solapamiento) {
            return response()->json([
                'message' => 'Ya existe un bloqueo en este muelle que solapa con las fechas seleccionadas.',
                'muelle_id' => $validated['muelle_id'],
            ], 422);
        }

        // 2️⃣ Verificar que no haya reservas existentes en el período
        $reservasExistentes = Reserva::where('muelle_id', $validated['muelle_id'])
            ->where('inicio', '<', $validated['fin'])
            ->where('fin', '>', $validated['inicio'])
            ->count();

        if ($reservasExistentes > 0) {
            return response()->json([
                'message' => "No se puede actualizar el bloqueo. Hay {$reservasExistentes} reserva(s) existente(s) en este período.",
                'muelle_id' => $validated['muelle_id'],
                'reservas_count' => $reservasExistentes,
            ], 422);
        }

        // 3️⃣ Actualizar el bloqueo
        $bloqueoMuelle->update($validated);

        return response()->json([
            'message' => 'Bloqueo de muelle actualizado correctamente.',
            'data' => $bloqueoMuelle->fresh()->load('muelle'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BloqueoMuelle $bloqueoMuelle)
    {
        $bloqueoMuelle->delete();

        return response()->json([
            'message' => 'Bloqueo de muelle eliminado correctamente.',
        ]);
    }
}
