<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\DocumentosReserva;
use App\Models\Proveedor;
use App\Models\BloqueoGrupoMaterial;
use App\Models\Entidad;
use App\Models\Restriccion;
use App\Models\Transportista;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Llistat de reservas CRUD
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Definimos las relaciones en un solo lugar para evitar repetición
        $relations = [
            'documentos',
            'proveedor.entidad',
            'tipoCamion:tipo_camion_id,nombre',
            'material1:material_id,nombre',
            'material2:material_id,nombre',
            'muelle',
            'estado',
            'transportista'
        ];

        $query = Reserva::with($relations);

        // 2. Aplicamos filtros si es una Entidad
        if ($user instanceof Entidad) {
            $query->where(function ($q) use ($user) {
                // Buscamos si es proveedor
                $q->whereHas('proveedor', function ($pq) use ($user) {
                    $pq->where('entidad_id', $user->entidad_id);
                })
                    // O si es transportista
                    ->orWhereHas('transportista', function ($tq) use ($user) {
                        $tq->where('entidad_id', $user->entidad_id);
                    });
            });
        }

        // 3. Si no es Entidad, es User, el query sigue sin filtros y trae todo
        return response()->json($query->get());
    }

    // Llistat de reserves Calendari
    public function indexCalendar()
    {
        $reservas = Reserva::with([
            'documentos',
            'proveedor:proveedor_id,entidad_id',
            'proveedor.entidad:entidad_id,nombre',
            'tipoCamion:tipo_camion_id,nombre',
            'material1:material_id,nombre',
            'material2:material_id,nombre',
            'muelle',
        ])
            ->get();

        return response()->json($reservas);
    }


    /**
     * Store a newly created resource in storage.
     * 1️⃣ Validar conflictos de tiempo con otras reservas del mismo muelle
     * 2️⃣ Validar disponibilidad de materiales en bloqueos de grupo
     * 3️⃣ Crear la reserva solo si todas las validaciones pasaron
     * 4️⃣ Restar cantidades disponibles
     * 5️⃣ Guardar archivos adjuntos
     */

    public function store(StoreReservaRequest $request)
    {
        $validatedData = $request->validated();

        // 1️⃣ Validar conflictos de tiempo con otras reservas del mismo muelle
        $conflictos = Reserva::where('muelle_id', $validatedData['muelle_id'])
            ->where('inicio', '<', $validatedData['fin'])
            ->where('fin', '>', $validatedData['inicio'])
            ->get();

        foreach ($conflictos as $reservaExistente) {
            $muelleExistente = $reservaExistente->muelle_id;

            // Validar restricciones entre muelles
            $restriccion = Restriccion::where(function ($q) use ($validatedData, $muelleExistente) {
                $q->where('muelle_id', $validatedData['muelle_id'])
                    ->where('muelle_restringido_id', $muelleExistente);
            })
                ->orWhere(function ($q) use ($validatedData, $muelleExistente) {
                    $q->where('muelle_id', $muelleExistente)
                        ->where('muelle_restringido_id', $validatedData['muelle_id']);
                })
                ->first();

            if ($restriccion) {
                return response()->json([
                    'id' => 2,
                    'message' => 'Restricción entre muelles. No se puede crear la reserva con el muelle seleccionado.',
                    'muelle_id' => $restriccion->muelle_id,
                    'muelle_restringido_id' => $restriccion->muelle_restringido_id,
                ], 422);
            }
        }

        // 2️⃣ Validar disponibilidad de materiales en bloqueos de grupo

        // Obtener proveedor y tipo
        $provider = Proveedor::find($validatedData['proveedor_id']);
        $providerType = $provider->tipo_proveedor_id;

        // Bloqueos para material1
        $bloqueos = BloqueoGrupoMaterial::where('tipo_proveedor_id', $providerType)
            ->whereHas('detalles', fn($q) => $q->where('material_id', $validatedData['material1_id']))
            ->where('inicio', '<=', $validatedData['inicio'])
            ->where('fin', '>=', $validatedData['fin'])
            ->get();

        // Bloqueos para material2 si existe
        if (!empty($validatedData['material2_id'])) {
            $bloqueos2 = BloqueoGrupoMaterial::where('tipo_proveedor_id', $providerType)
                ->whereHas('detalles', fn($q) => $q->where('material_id', $validatedData['material2_id']))
                ->where('inicio', '<=', $validatedData['inicio'])
                ->where('fin', '>=', $validatedData['fin'])
                ->get();

            $bloqueos = $bloqueos->concat($bloqueos2);
        }

        // Agrupar bloqueos por grupo y sumar cantidades
        $bloqueos = $bloqueos->keyBy('bloqueo_grupo_id');
        $gruposCantidades = [];
        foreach ($bloqueos as $bloqueo) {
            $gruposCantidades[$bloqueo->bloqueo_grupo_id] = ($gruposCantidades[$bloqueo->bloqueo_grupo_id] ?? 0)
                + ($validatedData['cantidad1'] ?? 0)
                + ($validatedData['cantidad2'] ?? 0);
        }

        // Validar disponibilidad
        foreach ($gruposCantidades as $bloqueoId => $cantidadSolicitada) {
            $bloqueo = $bloqueos->get($bloqueoId);
            if ($bloqueo && $bloqueo->cantidad_disponible < $cantidadSolicitada) {
                $materialNombre = $bloqueo->detalles->first()?->material?->nombre ?? null;

                return response()->json([
                    'id' => 1,
                    'message' => 'No hay suficiente material disponible en el bloqueo de grupo.',
                    'grupo_bloqueo_id' => $bloqueoId,
                    'material' => $materialNombre,
                ], 422);
            }
        }

        // 3️⃣ Crear la reserva solo si todas las validaciones pasaron
        $reserva = Reserva::create([
            'tipo_camion_id'      => $validatedData['tipo_camion_id'],
            'material1_id'        => $validatedData['material1_id'],
            'material2_id'        => $validatedData['material2_id'] ?? null,
            'proveedor_id'        => $validatedData['proveedor_id'],
            'transportista_id'    => $validatedData['transportista_id'],
            'muelle_id'           => $validatedData['muelle_id'],
            'estado_id'           => $validatedData['estado_id'],
            'empresa_lfycs_id'    => 1,
            'cantidad1'           => $validatedData['cantidad1'],
            'cantidad2'           => $validatedData['cantidad2'] ?? 0,
            'pedido1'             => 123,
            'pedido2'             => 123,
            'matricula_camion'    => $validatedData['matricula_camion'],
            'inicio'              => $validatedData['inicio'] ?? null,
            'fin'                 => $validatedData['fin'] ?? null,
            'aduana'              => $validatedData['aduana'] ?? false,
            'notas'               => $validatedData['notas'] ?? null,
            'telefono'            => $validatedData['telefono'] ?? null,
            'duracion'            => $validatedData['duracion'],
        ]);

        // 4️⃣ Restar cantidades disponibles
        foreach ($gruposCantidades as $bloqueoId => $cantidadSolicitada) {
            $bloqueo = $bloqueos->get($bloqueoId);
            if ($bloqueo) {
                $bloqueo->cantidad_disponible -= $cantidadSolicitada;
                $bloqueo->save();
            }
        }

        // 5️⃣ Guardar archivos adjuntos
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $ruta = $archivo->store("reservas/{$reserva->id}");
                $nombreOriginal = $archivo->getClientOriginalName();

                $reserva->documentos()->create([
                    'url' => $ruta,
                    'name' => $nombreOriginal
                ]);
            }
        }

        return response()->json([
            'message' => 'Reserva creada correctamente.',
            'data' => $reserva,
        ], 201);
    }




    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        return response()->json($reserva->load([
            'documentos',
            'proveedor.entidad',
            'transportista.entidad',
            'tipoCamion:tipo_camion_id,nombre',
            'material1:material_id,nombre',
            'material2:material_id,nombre',
            'muelle1',
            'muelle2',
            'estado'
        ]));
    }

    /**
     * MÈTODE UPDATE VALIDACIONS
     * 1️⃣ Validación de solapamiento y restricciones de muelle
     * 2️⃣ Validación de disponibilidad de materiales
     * 3️⃣ Archivos adjuntos
     * 4️⃣ Actualizar reserva
     */
    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        $validatedData = $request->validated();
        
        // 1️⃣ Validación de solapamiento y restricciones de muelle
        $conflictos = Reserva::where('muelle_id', $validatedData['muelle_id'])
            ->where('reserva_id', '!=', $reserva->reserva_id) // Excluir la propia reserva
            ->where('inicio', '<', $validatedData['fin'])
            ->where('fin', '>', $validatedData['inicio'])
            ->get();

        foreach ($conflictos as $reservaExistente) {
            $muelleExistente = $reservaExistente->muelle1_id;

            $restriccion = Restriccion::where(function ($q) use ($validatedData, $muelleExistente) {
                $q->where('muelle_id', $validatedData['muelle_id'])
                    ->where('muelle_restringido_id', $muelleExistente);
            })
                ->orWhere(function ($q) use ($validatedData, $muelleExistente) {
                    $q->where('muelle_id', $muelleExistente)
                        ->where('muelle_restringido_id', $validatedData['muelle_id']);
                })
                ->first();

            if ($restriccion) {
                return response()->json([
                    'id' => 2,
                    'message' => 'Restricción entre muelles. No se puede actualizar la reserva con el muelle seleccionado.',
                    'muelle_id' => $restriccion->muelle_id,
                    'muelle_restringido_id' => $restriccion->muelle_restringido_id,
                ], 422);
            }
        }

        // 2️⃣ Validación de disponibilidad de materiales
        $provider = Proveedor::find($validatedData['proveedor_id']);
        $providerType = $provider->tipo_proveedor_id;

        $materialesNuevos = array_filter([
            $validatedData['material_id'] ?? null,
            $validatedData['material_id'] ?? null,
        ]);

        $bloqueosNuevos = BloqueoGrupoMaterial::where('tipo_proveedor_id', $providerType)
            ->whereHas('detalles', function ($q) use ($materialesNuevos) {
                $q->whereIn('material_id', $materialesNuevos);
            })
            ->where('inicio', '<=', $validatedData['inicio'])
            ->where('fin', '>=', $validatedData['fin'])
            ->get()
            ->keyBy('bloqueo_grupo_id');

        // Calcular diferencia de cantidades
        $diferencias = [];
        foreach ($bloqueosNuevos as $bloqueo) {
            $materialId = $bloqueo->detalles->pluck('material_id')->first();
            $cantidadVieja = 0;
            if ($reserva->material1_id == $materialId) $cantidadVieja = $reserva->cantidad1;
            if ($reserva->material2_id == $materialId) $cantidadVieja = $reserva->cantidad2 ?? 0;

            $cantidadNueva = 0;
            if ($validatedData['material_id'] == $materialId) $cantidadNueva = $validatedData['cantidad1'];
            if (($validatedData['material_id'] ?? null) == $materialId) $cantidadNueva = $validatedData['cantidad2'] ?? 0;

            $diferencias[$bloqueo->bloqueo_grupo_id] = $cantidadNueva - $cantidadVieja;
        }

        // Validar disponibilidad
        foreach ($diferencias as $bloqueoId => $diferencia) {
            if ($diferencia > 0 && $bloqueosNuevos[$bloqueoId]->cantidad_disponible < $diferencia) {
                $detalle = $bloqueosNuevos[$bloqueoId]->detalles->first();
                return response()->json([
                    'id' => 1,
                    'message' => 'No hay suficiente material disponible en el bloqueo de grupo.',
                    'grupo_bloqueo_id' => $bloqueoId,
                    'material' => $detalle->material->nombre ?? null,
                ], 422);
            }
        }

        // Restar / sumar cantidades disponibles
        foreach ($diferencias as $bloqueoId => $diferencia) {
            if ($diferencia != 0) {
                $bloqueo = $bloqueosNuevos[$bloqueoId];
                $bloqueo->cantidad_disponible -= $diferencia;
                $bloqueo->save();
            }
        }

        // 3️⃣ Archivos adjuntos
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $reserva->documentos()->firstOrCreate(
                    ['name' => $archivo->getClientOriginalName()],
                    ['url' => $archivo->store("reservas/{$reserva->reserva_id}")]
                );
            }
        }

        // 4️⃣ Actualizar reserva
        $reserva->update([
            'tipo_camion_id'      => $validatedData['tipo_camion_id'],
            'material1_id'   => $validatedData['material1_id'],
            'material2_id'   => $validatedData['material2_id'] ?? null,
            'proveedor_id'        => $validatedData['proveedor_id'],
            'transportista_id'    => $validatedData['transportista_id'],
            'muelle_id'          => $validatedData['muelle_id'],
            'estado_id'           => $validatedData['estado_id'],
            'cantidad1'           => $validatedData['cantidad1'],
            'cantidad2'           => $validatedData['cantidad2'] ?? null,
            'pedido1'           => $validatedData['pedido1'] ?? null,
            'pedido2'           => $validatedData['pedido1'] ?? null,
            'matricula_camion'    => $validatedData['matricula_camion'],
            'inicio'             => $validatedData['inicio'],
            'fin'                => $validatedData['fin'],
            'aduana'           => $validatedData['aduana'] ?? false,
            'notas'               => $validatedData['notas'] ?? null,
            'telefono'                => $validatedData['telefono'] ?? null,
            'duracion'           => $validatedData['duracion'],
        ]);

        return response()->json([
            'message' => 'Reserva actualizada correctamente.',
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

    /**
     * @inicio
     * @param date $inicio
     * @param date $fin
     * @return csv de las reservas entre esas fechas exactas
     **/
    public function generateReport(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        $fileName = 'reservas_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            "ID Reserva",
            "Comanda LF",
            "Tipo camión",
            "Material 1",
            "Cantidad",
            "SAP Proveedor",
            "Nombre proveedor",
            "Nombre abreviado proveedor",
            "NIF/CIF proveedor",
            "Nombre contacto proveedor",
            "Email proveedor",
            "Teléfono 1 proveedor",
            "Teléfono 2 proveedor",
            "Nombre Transportista",
            "Abreviado Transportista",
            "NIF Transportista",
            "Nombre contacto Transportista",
            "Email Transportista",
            "Teléfono 1 Transportista",
            "Teléfono 2 Transportista",
            "Matrícula camión",
            "Hora inicio 1",
            "Hora final 1",
            "Nombre muelle 1",
            "Descripción muelle 1",
            "Estado",
            "Aduana?",
            "Creado el",
            "Notas",
            "Empresa LFYCS",
            "Descripción empresa",
            "Teléfono",
            "Material 2",
            "Hora inicio 2",
            "Hora final 2",
            "Muelle 2",
            "Descripción muelle 2",
            "Cantidad 2"
        ];

        $reservas = Reserva::with(['tipoCamion', 'material', 'material1', 'proveedor', 'transportista', 'muelle1', 'muelle2', 'empresa_lfycs', 'status'])
            ->whereBetween('inicio1', [$from, $to])
            ->get();


        $callback = function () use ($reservas, $headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, $headers, ';', '"');

            foreach ($reservas as $reserva) {
                fputcsv($handle, [
                    $reserva->reserva_id,
                    $reserva->pedido_LF,
                    $reserva->tipoCamion->nombre ?? '',
                    $reserva->material1->nombre ?? '',
                    $reserva->cantidad1,
                    $reserva->proveedor->codigo_sap ?? '',
                    $reserva->proveedor->nombre ?? '',
                    $reserva->proveedor->abreviatura ?? '',
                    $reserva->proveedor->NIF ?? '',
                    $reserva->proveedor->nombre_contacto ?? '',
                    $reserva->proveedor->email ?? '',
                    $reserva->proveedor->tel1 ?? '',
                    $reserva->proveedor->tel2 ?? '',
                    $reserva->Carrier->nombre ?? '',
                    $reserva->Carrier->abreviatura ?? '',
                    $reserva->Carrier->NIF ?? '',
                    $reserva->Carrier->nombre_contacto ?? '',
                    $reserva->Carrier->email ?? '',
                    $reserva->Carrier->tel1 ?? '',
                    $reserva->Carrier->tel2 ?? '',
                    $reserva->matricula_camion,
                    $reserva->inicio1,
                    $reserva->fin1,
                    $reserva->muelle1->nombre ?? '',
                    $reserva->muelle1->descripcion ?? '',
                    $reserva->status->nombre ?? '',
                    $reserva->es_aduana ? 1 : 0,
                    $reserva->created_at,
                    $reserva->notas,
                    $reserva->empresa_lfycs->nombre ?? '',
                    $reserva->empresa_lfycs->descripcion ?? '',
                    $reserva->tel1,
                    $reserva->tipo_material2_id->nombre ?? '',
                    $reserva->inicio2,
                    $reserva->fin2,
                    $reserva->muelle2->nombre ?? '',
                    $reserva->muelle2->descripcion ?? '',
                    $reserva->cantidad2
                ], ';', '"');
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}"
        ]);
    }
}
