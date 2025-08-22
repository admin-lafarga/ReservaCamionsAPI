<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\DocumentosReserva;
use Illuminate\Support\Facades\Log;
use App\Models\Proveedor;
use App\Models\BloqueoGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Llistat de reservas CRUD
    public function index()
    {
        $reservas = Reserva::with([
            'documentos',
            'proveedor:proveedor_id,nombre',
            'tipoCamion:tipo_camion_id,nombre',
            'material:material_id,nombre_material',
            'material1:material_id,nombre_material',
            'muelle1'
        ])
        ->whereNotNull('cantidad1')
        ->get();

        return response()->json($reservas);
    }

    // Llistat de reserves Calendari
    public function indexCalendar()
    {
        $reservas = Reserva::with([
            'documentos',
            'proveedor:proveedor_id,nombre',
            'tipoCamion:tipo_camion_id,nombre',
            'material:material_id,nombre_material',
            'material1:material_id,nombre_material',
            'muelle1'
        ])
        ->get();

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

        // Obtener el proveedor y su tipo
        $provider = Proveedor::find($validatedData['proveedor_id']);
        $providerType = $provider->tipo_proveedor_id;

        // Buscar bloqueos de grupo para material1
        $bloqueos1 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
            ->whereHas('detalles', function ($q) use ($validatedData) {
            $q->where('material_id', $validatedData['tipo_material1_id']);
            })
            ->where('fecha_desde', '<=', $validatedData['inicio1'])
            ->where('fecha_hasta', '>=', $validatedData['fin1'])
            ->where('activo', true)
            ->get();

        // Buscar bloqueos de grupo para material2 si existe
        $bloqueos2 = collect();
        if (!empty($validatedData['tipo_material2_id']) && !empty($validatedData['cantidad2'])) {
            $bloqueos2 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
            ->whereHas('detalles', function ($q) use ($validatedData) {
                $q->where('material_id', $validatedData['tipo_material2_id']);
            })
            ->where('fecha_desde', '<=', $validatedData['inicio1'])
            ->where('fecha_hasta', '>=', $validatedData['fin1'])
            ->where('activo', true)
            ->get();
        }

        // Agrupar bloqueig per grups
        $bloqueos = $bloqueos1->concat($bloqueos2)->keyBy('bloqueo_grupo_id');

        // Sumar les quantitats
        $gruposCantidades = [];
        foreach ($bloqueos1 as $bloqueo) {
            $gruposCantidades[$bloqueo->bloqueo_grupo_id] = $validatedData['cantidad1'];
        }
        foreach ($bloqueos2 as $bloqueo) {
            if (isset($gruposCantidades[$bloqueo->bloqueo_grupo_id])) {
            $gruposCantidades[$bloqueo->bloqueo_grupo_id] += $validatedData['cantidad2'];
            } else {
            $gruposCantidades[$bloqueo->bloqueo_grupo_id] = $validatedData['cantidad2'];
            }
        }

        // Validar dispopnibilitat
        foreach ($gruposCantidades as $bloqueoId => $cantidadSolicitada) {
            $bloqueo = $bloqueos->get($bloqueoId);
            if ($bloqueo && $bloqueo->cantidad_disponible < $cantidadSolicitada) {
            // Obtener el nombre del material asociado al bloqueo
            $materialNombre = null;
            if ($bloqueo->detalles && $bloqueo->detalles->count() > 0) {
                // Tomar el primer detalle (puedes ajustar si hay varios materiales)
                $detalle = $bloqueo->detalles->first();
                if ($detalle->material && isset($detalle->material->nombre_material)) {
                $materialNombre = $detalle->material->nombre_material;
                }
            }
            return response()->json([
                'id' => 1,
                'message' => 'No hay suficiente material disponible en el bloqueo de grupo.',
                'grupo_bloqueo_id' => $bloqueoId,
                'material' => $materialNombre,
            ], 422);
            }
        }

        // Restar cantidades
        foreach ($gruposCantidades as $bloqueoId => $cantidadSolicitada) {
            $bloqueo = $bloqueos->get($bloqueoId);
            if ($bloqueo) {
            $bloqueo->cantidad_disponible -= $cantidadSolicitada;
            $bloqueo->save();
            }
        }

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
     * MÈTODE UPDATE VALIDACIONS
     * En aquest mètode es validen les dades de la reserva i es comprova que els bloquejos de grups tinguin disponibilitat per a les dates i quantitats indicades.
     * Si hi ha canvis en les quantitats o dates, es gestionen els bloquejos de grups per assegurar que les quantitats es restin correctament.
     * També s'actualitzen els fitxers associats a la reserva si n'hi ha.
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        $validatedData = $request->validated();

        $provider = Proveedor::find($validatedData['proveedor_id']);
        $providerType = $provider->tipo_proveedor_id;

        // Obtenir valors anteriors i els nous que es volen canviar en el cas que n'hi hagin de nous
        $oldCantidad1 = $reserva->getOriginal('cantidad1');
        $oldCantidad2 = $reserva->getOriginal('cantidad2') ?? 0;
        $newCantidad1 = $validatedData['cantidad1'];
        $newCantidad2 = $validatedData['cantidad2'] ?? 0;

        $oldMaterial1 = $reserva->getOriginal('tipo_material1_id');
        $oldMaterial2 = $reserva->getOriginal('tipo_material2_id');
        $newMaterial1 = $validatedData['tipo_material1_id'];
        $newMaterial2 = $validatedData['tipo_material2_id'] ?? null;

        $oldInicio1 = $reserva->getOriginal('inicio1');
        $oldFin1 = $reserva->getOriginal('fin1');
        $newInicio1 = $validatedData['inicio1'];
        $newFin1 = $validatedData['fin1'];

        // Només en el cas que es faci un canvi de quantitats o bé de dates
        if (
            $oldCantidad1 != $newCantidad1 || $oldCantidad2 != $newCantidad2 ||
            $oldMaterial1 != $newMaterial1 || $oldMaterial2 != $newMaterial2 ||
            $oldInicio1 != $newInicio1 || $oldFin1 != $newFin1
        ) {
            // --- BLOQUEIG ANTERIORS (per tirar enrrere les quantitats en el cas que surti del seu rang (BACKUP)) ---
            $oldBloqueos1 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
                ->whereHas('detalles', function ($q) use ($oldMaterial1) {
                    $q->where('material_id', $oldMaterial1);
                })
                ->where('fecha_desde', '<=', $oldInicio1)
                ->where('fecha_hasta', '>=', $oldFin1)
                ->where('activo', true)
                ->get();

            $oldBloqueos2 = collect();
            if (!empty($oldMaterial2) && !empty($oldCantidad2)) {
                $oldBloqueos2 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
                    ->whereHas('detalles', function ($q) use ($oldMaterial2) {
                        $q->where('material_id', $oldMaterial2);
                    })
                    ->where('fecha_desde', '<=', $oldInicio1)
                    ->where('fecha_hasta', '>=', $oldFin1)
                    ->where('activo', true)
                    ->get();
            }

            // --- NOUS BLOQUEIJOS (validar i restar quantitats) ---
            $newBloqueos1 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
                ->whereHas('detalles', function ($q) use ($newMaterial1) {
                    $q->where('material_id', $newMaterial1);
                })
                ->where('fecha_desde', '<=', $newInicio1)
                ->where('fecha_hasta', '>=', $newFin1)
                ->where('activo', true)
                ->get();

            $newBloqueos2 = collect();
            if (!empty($newMaterial2) && !empty($newCantidad2)) {
                $newBloqueos2 = BloqueoGrupo::where('tipo_proveedor_id', $providerType)
                    ->whereHas('detalles', function ($q) use ($newMaterial2) {
                        $q->where('material_id', $newMaterial2);
                    })
                    ->where('fecha_desde', '<=', $newInicio1)
                    ->where('fecha_hasta', '>=', $newFin1)
                    ->where('activo', true)
                    ->get();
            }

            // --- Sumar quantitats a bloquejos antics en el cas que la reserva ja no estigui en el rang de dates ---
            // En el cas que la data/material/quantitat ja no formi part del bloqueig anterior es suma la quantitat
            $oldBloqueos = $oldBloqueos1->concat($oldBloqueos2)->keyBy('bloqueo_grupo_id');
            $newBloqueos = $newBloqueos1->concat($newBloqueos2)->keyBy('bloqueo_grupo_id');

            // Per a cada bloquig antic, en el cas que ja no estigui en els nous es suma la quantitat
            foreach ($oldBloqueos1 as $bloqueo) {
                $bloqueoId = $bloqueo->bloqueo_grupo_id;
                $oldMat = $bloqueo->detalles->first()->material_id;
                if (
                    !$newBloqueos->has($bloqueoId) ||
                    $oldMaterial1 != $newMaterial1 ||
                    $oldInicio1 != $newInicio1 ||
                    $oldFin1 != $newFin1
                ) {
                    $bloqueo->cantidad_disponible += $oldCantidad1;
                    $bloqueo->save();
                }
            }
            foreach ($oldBloqueos2 as $bloqueo) {
                $bloqueoId = $bloqueo->bloqueo_grupo_id;
                $oldMat = $bloqueo->detalles->first()->material_id;
                if (
                    !$newBloqueos->has($bloqueoId) ||
                    $oldMaterial2 != $newMaterial2 ||
                    $oldInicio1 != $newInicio1 ||
                    $oldFin1 != $newFin1
                ) {
                    $bloqueo->cantidad_disponible += $oldCantidad2;
                    $bloqueo->save();
                }
            }

            // --- Calcular les diferències per per grup pels nous bloquejos ---
            $gruposDiferencias = [];
            foreach ($newBloqueos1 as $bloqueo) {
                $bloqueoId = $bloqueo->bloqueo_grupo_id;
                $old = ($oldBloqueos->has($bloqueoId) && $oldMaterial1 == $bloqueo->detalles->first()->material_id && $oldInicio1 == $newInicio1 && $oldFin1 == $newFin1) ? $oldCantidad1 : 0;
                $new = $newCantidad1;
                $gruposDiferencias[$bloqueoId] = $new - $old;
            }
            foreach ($newBloqueos2 as $bloqueo) {
                $bloqueoId = $bloqueo->bloqueo_grupo_id;
                $old = ($oldBloqueos->has($bloqueoId) && $oldMaterial2 == $bloqueo->detalles->first()->material_id && $oldInicio1 == $newInicio1 && $oldFin1 == $newFin1) ? $oldCantidad2 : 0;
                $new = $newCantidad2;
                if (isset($gruposDiferencias[$bloqueoId])) {
                    $gruposDiferencias[$bloqueoId] += ($new - $old);
                } else {
                    $gruposDiferencias[$bloqueoId] = $new - $old;
                }
            }

            // --- En el cas que hi hagi increment validar disponibilitat ---
            foreach ($gruposDiferencias as $bloqueoId => $diferencia) {
                if ($diferencia > 0) {
                    $bloqueo = $newBloqueos->get($bloqueoId);
                    if ($bloqueo && $bloqueo->cantidad_disponible < $diferencia) {
                        $materialNombre = null;
                        if ($bloqueo->detalles && $bloqueo->detalles->count() > 0) {
                            $detalle = $bloqueo->detalles->first();
                            if ($detalle->material && isset($detalle->material->nombre_material)) {
                                $materialNombre = $detalle->material->nombre_material;
                            }
                        }
                        return response()->json([
                            'id' => 1,
                            'message' => 'No hay suficiente material disponible en el bloqueo de grupo.',
                            'grupo_bloqueo_id' => $bloqueoId,
                            'material' => $materialNombre,
                        ], 422);
                    }
                }
            }

            // --- Actualitzar les quantitats dels bloquejos ---
            foreach ($gruposDiferencias as $bloqueoId => $diferencia) {
                $bloqueo = $newBloqueos->get($bloqueoId);
                if ($bloqueo && $diferencia != 0) {
                    $bloqueo->cantidad_disponible -= $diferencia;
                    $bloqueo->save();
                }
            }
        }

        // Validar que en el cas que hi hagin arxius i no estiguin ja creats els crei
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $ruta = $archivo->store("reservas{$reserva->id}");

                $nombreOriginal = $archivo->getClientOriginalName();

                $documento = DocumentosReserva::where('name', $nombreOriginal)->where('reserva_id', $reserva->id)->first();
                if (!$documento){
                    $reserva->documentos()->create([
                        'url' => $ruta,
                        'name' => $nombreOriginal
                    ]);
                }
            }
        }

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
        // Eliminar fitxers associats a la reserva (si existeixen)
        foreach ($reserva->documentos as $documento) {
            $filePath = storage_path('app/private/' . $documento->url);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $documento->delete();
        }

        // Eliminar la reserva
        $reserva->delete();

        return response()->json([
            'message' => 'Reserva i documents associats eliminats correctament.',
        ]);
    }

    // GESTIÓ DE DOCUMENTS DE LES RESERVES #######################################################################

    // Retornar els fitxers associats a la reserva
    public function getPrivateFile($path)
    {
        $fullPath = storage_path('app/private/' . $path);
        $realBase = realpath(storage_path('app/private'));
        $realFullPath = realpath($fullPath);

        if (!$realFullPath || strpos($realFullPath, $realBase) !== 0) {
            return response("Accés no autoritzat", 403);
        }

        if (!file_exists($realFullPath)) {
            return response("Fitxer no trobat: " . $realFullPath, 404);
        }

        $document = DocumentosReserva::where('url', $path)->first();
        if (!$document) {
            return response("Document no trobat a la base de dades", 404);
        }

        $filename = $document->name;

        $mimeType = mime_content_type($realFullPath);

        return response()->download($realFullPath, $filename, [
            'Content-Type' => $mimeType,
        ]);
    }

    // Funció per retornar el nom del fitxer
    public function getPrivateFileName($path)
    {
        $document = DocumentosReserva::where('url', $path)->first();
        if (!$document) {
            return response("Document no trobat a la base de dades", 404);
        }

        $filename = $document->name;
        $reservaId = $document->reserva_id;

        return response()->json([
            'name' => $filename,
        ]);
    }

    // Funció per eliminar un fitxer privat
    public function deletePrivateFile($id)
    {
        $booking_document = DocumentosReserva::findOrFail($id);

        $filePath = storage_path('app/private/' . $booking_document->url);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fitxer no trobat'], 404);
        }

        unlink($filePath);

        $booking_document->delete();

        return response()->json(['missatge' => 'Fitxer eliminat correctament']);
    }

    ########################################################################################

    // BLOQUEIG DE MOLLS (s'ha seguit la mateixa llògica que es fa a Wifor, per això està ubicat dins de reserves)

    public function indexBloqueoMuelle() {

        $reservas = Reserva::with(['muelle1'])->whereNull('cantidad1')->get();

        return response()->json($reservas);
    }

    /**
     * Crear un nuevo bloqueo de muelle.
     */
    public function storeBloqueoMuelle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'muelle1_id' => 'required|exists:muelles,muelle_id',
            'inicio1'    => 'required|date',
            'fin1'       => 'required|date|after_or_equal:inicio1',
            'notas'      => 'nullable|string|max:500',
        ], [
            'muelle1_id.required' => 'El muelle és obligatori.',
            'muelle1_id.exists'   => 'El muelle seleccionat no existeix.',
            'inicio1.required'    => 'La data d’inici és obligatòria.',
            'fin1.required'       => 'La data de fi és obligatòria.',
            'fin1.after_or_equal' => 'La data de fi ha de ser posterior o igual a la d’inici.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Buscar conflictes d’horaris al mateix muelle
        $conflicto = Reserva::where('muelle1_id', $data['muelle1_id'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('inicio1', [$data['inicio1'], $data['fin1']]) // inicia dins del nou bloqueig
                ->orWhereBetween('fin1', [$data['inicio1'], $data['fin1']]) // acaba dins del nou bloqueig
                ->orWhere(function ($q2) use ($data) { // ocupa tot el rang
                    $q2->where('inicio1', '<=', $data['inicio1'])
                        ->where('fin1', '>=', $data['fin1']);
                });
            })
            ->exists();

        if ($conflicto) {
            return response()->json([
                'id' => '1',
                'message' => 'Ya existe una reserva o bloqueo en ese rango horario para el muelle seleccionado.',
            ], 422);
        }

        $bloqueo = Reserva::create($data);

        return response()->json([
            'message' => 'Bloqueo creado correctamente',
            'data'    => $bloqueo
        ], 201);
    }


    /**
     * Actualizar un bloqueo de muelle existente.
     */
    public function updateBloqueoMuelle(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'muelle1_id' => 'required|exists:muelles,muelle_id',
            'inicio1'    => 'required|date',
            'fin1'       => 'required|date|after_or_equal:inicio1',
            'notas'      => 'nullable|string|max:500',
        ], [
            'muelle1_id.required' => 'El muelle és obligatori.',
            'muelle1_id.exists'   => 'El muelle seleccionat no existeix.',
            'inicio1.required'    => 'La data d’inici és obligatòria.',
            'fin1.required'       => 'La data de fi és obligatòria.',
            'fin1.after_or_equal' => 'La data de fi ha de ser posterior o igual a la d’inici.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $conflicto = Reserva::where('muelle1_id', $data['muelle1_id'])
            ->where('reserva_id', '!=', $id) // exclou la reserva que s’està actualitzant
            ->where(function ($q) use ($data) {
                $q->whereBetween('inicio1', [$data['inicio1'], $data['fin1']])
                ->orWhereBetween('fin1', [$data['inicio1'], $data['fin1']])
                ->orWhere(function ($q2) use ($data) {
                    $q2->where('inicio1', '<=', $data['inicio1'])
                        ->where('fin1', '>=', $data['fin1']);
                });
            })
            ->exists();

       if ($conflicto) {
            return response()->json([
                'id' => '1',
                'message' => 'Ya existe una reserva o bloqueo en ese rango horario para el muelle seleccionado.',
            ], 422);
        }

        $bloqueo = Reserva::findOrFail($id);
        $bloqueo->update($data);

        return response()->json([
            'message' => 'Bloqueig actualitzat correctament',
            'data'    => $bloqueo
        ], 200);
    }

}
