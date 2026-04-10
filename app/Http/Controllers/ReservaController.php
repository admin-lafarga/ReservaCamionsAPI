<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\DocumentosReserva;
use App\Models\Proveedor;
use App\Models\BloqueoGrupoMaterial;
use App\Models\BloqueoMuelle;
use App\Models\Entidad;
use App\Models\Restriccion;
use App\Models\Transportista;
use Illuminate\Http\Request;
use App\Models\HorarioMuelle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\ConfirmationMail;
use App\Models\Parametro;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Reserva::class, 'reserva');
    }

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
            'proveedor.tipoProveedor',
            'tipoCamion:tipo_camion_id,nombre',
            'material1:material_id,nombre',
            'material2:material_id,nombre',
            'muelle',
            'estado',
            'transportista.entidad',
            'empresa_lfycs'
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

        // 3. Aplicamos filtros de estado
        $statusFilter = $request->input('status_filter', 'todas');
        if ($statusFilter === 'pendientes') {
            $query->where('inicio', '>=', now()->startOfDay());
        } elseif ($statusFilter === 'antiguas') {
            $query->where('inicio', '<', now()->startOfDay());
        }

        // 4. Búsqueda global (search)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reserva_id', 'like', "%{$search}%")
                  ->orWhere('matricula_camion', 'like', "%{$search}%")
                  ->orWhere('pedido1', 'like', "%{$search}%")
                  ->orWhere('cantidad1', 'like', "%{$search}%")
                  ->orWhereHas('proveedor.entidad', function ($pq) use ($search) {
                      $pq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('transportista.entidad', function ($tq) use ($search) {
                      $tq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('muelle', function ($mq) use ($search) {
                      $mq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tipoCamion', function ($cq) use ($search) {
                      $cq->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('material1', function ($matq) use ($search) {
                      $matq->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        // 5. Ordenamiento (Sort)
        $sortField = $request->input('sort', 'inicio');
        $sortDir = $request->input('dir', 'desc');
        
        // Mapeo seguro de columnas para no fallar con relaciones
        $allowedSorts = ['reserva_id', 'inicio', 'fin', 'matricula_camion', 'pedido1', 'cantidad1'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('inicio', 'desc');
        }

        // 6. Finalmente, paginamos (por defecto 10 por página)
        $perPage = $request->input('per_page', 10);
        
        // return response()->json($query->paginate($perPage));
        return response()->json($query->paginate($perPage));
    }

    // Llistat de reserves Calendari
    public function indexCalendar(Request $request)
    {
        $this->authorize('viewAny', Reserva::class);
        $user = $request->user();
        
        // 1. Preparamos la query base cargando la relación del muelle (necesaria para el color en el front)
        $query = Reserva::with('muelle'); 

        // Opcional: Filtra por fechas si recibes 'start' y 'end' del FullCalendar
        if ($request->has(['start', 'end'])) {
            $query->whereBetween('inicio', [$request->start, $request->end]);
        }

        $reservas = $query->get();

        // 2. Lógica de SEGURIDAD: Detectar si es usuario externo
        // En este proyecto, los externos son instancias del modelo Entidad (Proveedores/Transportistas)
        $isExternal = $user instanceof Entidad; 

        if ($isExternal) {
            // Mapeamos para devolver SOLO lo necesario (start, end, y muelle para el color)
            return $reservas->map(function($reserva) {
                return [
                    'reserva_id' => $reserva->reserva_id, // ID por si acaso
                    'inicio'     => $reserva->inicio,
                    'fin'        => $reserva->fin,
                    // MUY IMPORTANTE: Devolver estructura de muelle con color para que el Front pinte las cajas
                    'muelle'     => [
                        'muelle_id' => $reserva->muelle_id,
                        'color'     => $reserva->muelle ? $reserva->muelle->color : '#cccccc', // Color fallback
                    ],
                    // NO devolvemos ni proveedor, ni matricula, ni materiales
                ];
            });
        }

        $reservas = Reserva::with([
            'documentos',
            'proveedor:proveedor_id,entidad_id,tipo_proveedor_id',
            'proveedor.entidad:entidad_id,nombre',
            'proveedor.tipoProveedor',
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

        // 0️⃣ Validar horario operativo del muelle
        $fechaInicio = Carbon::parse($validatedData['inicio']);
        $fechaFin = Carbon::parse($validatedData['fin']);
        
        // Obtener día de la semana (1 = Lunes, 7 = Domingo)
        $diaSemana = $fechaInicio->dayOfWeekIso;

        $horario = HorarioMuelle::where('muelle_id', $validatedData['muelle_id'])
            ->where('dia_semana', $diaSemana)
            ->first();

        if (!$horario) {
            return response()->json([
                'id' => 3,
                'message' => 'El muelle seleccionado no está operativo este día de la semana.',
                'muelle_id' => $validatedData['muelle_id']
            ], 422);
        }

        $horaInicioReserva = $fechaInicio->format('H:i:s');
        $horaFinReserva = $fechaFin->format('H:i:s');

        if ($horaInicioReserva < $horario->inicio || $horaFinReserva > $horario->fin) {
            return response()->json([
                'id' => 4,
                'message' => "La reserva está fuera del horario operativo ({$horario->inicio} - {$horario->fin}).",
                'muelle_id' => $validatedData['muelle_id']
            ], 422);
        }

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

        // 2️⃣ Validar que el muelle no esté bloqueado (específico o global)
        $bloqueoMuelle = BloqueoMuelle::where(function($q) use ($validatedData) {
                $q->where('muelle_id', $validatedData['muelle_id'])
                  ->orWhereNull('muelle_id'); // Verificar bloqueos globales
            })
            ->where('inicio', '<', $validatedData['fin'])
            ->where('fin', '>', $validatedData['inicio'])
            ->first();

        if ($bloqueoMuelle) {
            return response()->json([
                'id' => 2,
                'message' => 'No es posible reservar este muelle en estas fechas',
                'bloqueo_muelle_id' => $bloqueoMuelle->bloqueo_muelle_id,
                'asunto' => $bloqueoMuelle->asunto,
            ], 422);
        }

        // 3️⃣ Validar disponibilidad de materiales en bloqueos de grupo

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
                    'message' => 'No se puede reservar este material en estas fechas',
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
            'transportista_id'    => $validatedData['transportista_id'] ?? null,
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
                $ruta = $archivo->store("reservas/{$reserva->reserva_id}");
                $nombreOriginal = $archivo->getClientOriginalName();

                $reserva->documentos()->create([
                    'url' => $ruta,
                    'nombre' => $nombreOriginal
                ]);
            }
        }

        // 6️⃣ Enviar email de confirmación
        $this->enviarEmailsReserva($reserva);

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
            'muelle',
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
        
        // 0️⃣ Validar horario operativo del muelle
        $fechaInicio = Carbon::parse($validatedData['inicio']);
        $fechaFin = Carbon::parse($validatedData['fin']);
        
        // Obtener día de la semana (1 = Lunes, 7 = Domingo)
        $diaSemana = $fechaInicio->dayOfWeekIso;

        $horario = HorarioMuelle::where('muelle_id', $validatedData['muelle_id'])
            ->where('dia_semana', $diaSemana)
            ->first();

        if (!$horario) {
            return response()->json([
                'id' => 3,
                'message' => 'El muelle seleccionado no está operativo este día de la semana.',
                'muelle_id' => $validatedData['muelle_id']
            ], 422);
        }

        $horaInicioReserva = $fechaInicio->format('H:i:s');
        $horaFinReserva = $fechaFin->format('H:i:s');

        if ($horaInicioReserva < $horario->inicio || $horaFinReserva > $horario->fin) {
            return response()->json([
                'id' => 4,
                'message' => "La reserva está fuera del horario operativo ({$horario->inicio} - {$horario->fin}).",
                'muelle_id' => $validatedData['muelle_id']
            ], 422);
        }

        // 1️⃣ Validación de solapamiento y restricciones de muelle
        $conflictos = Reserva::where('muelle_id', $validatedData['muelle_id'])
            ->where('reserva_id', '!=', $reserva->reserva_id) // Excluir la propia reserva
            ->where('inicio', '<', $validatedData['fin'])
            ->where('fin', '>', $validatedData['inicio'])
            ->get();

        foreach ($conflictos as $reservaExistente) {
            $muelleExistente = $reservaExistente->muelle_id;

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

        // 1.5️⃣ Validar que el muelle no esté bloqueado (específico o global)
        $bloqueoMuelle = BloqueoMuelle::where(function($q) use ($validatedData) {
                $q->where('muelle_id', $validatedData['muelle_id'])
                  ->orWhereNull('muelle_id'); // Verificar bloqueos globales
            })
            ->where('inicio', '<', $validatedData['fin'])
            ->where('fin', '>', $validatedData['inicio'])
            ->first();

        if ($bloqueoMuelle) {
            return response()->json([
                'id' => 2,
                'message' => 'No es posible reservar este muelle en estas fechas',
                
            ], 422);
        }

        // 2️⃣ Validación de disponibilidad de materiales
        $provider = Proveedor::find($validatedData['proveedor_id']);
        $providerType = $provider->tipo_proveedor_id;

        $materialesNuevos = array_filter([
            $validatedData['material1_id'] ?? null,
            $validatedData['material2_id'] ?? null,
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
            if ($validatedData['material1_id'] == $materialId) $cantidadNueva = $validatedData['cantidad1'];
            if (($validatedData['material2_id'] ?? null) == $materialId) $cantidadNueva = $validatedData['cantidad2'] ?? 0;

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
                    ['nombre' => $archivo->getClientOriginalName()],
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
            'transportista_id'    => $validatedData['transportista_id'] ?? null,
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

        // 5️⃣ Enviar email de actualización
        $this->enviarEmailsReserva($reserva);

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
            if (Storage::disk('local')->exists($documento->url)) {
                Storage::disk('local')->delete($documento->url);
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
        $this->authorize('viewAny', Reserva::class);

        $document = DocumentosReserva::where('url', $path)->first();
        if (!$document) {
            return response("Document no trobat a la base de dades", 404);
        }

        if (!Storage::disk('local')->exists($path)) {
            return response("Fitxer no trobat al servidor", 404);
        }

        $filename = $document->nombre;
        $mimeType = Storage::disk('local')->mimeType($path);

        return response(Storage::disk('local')->get($path), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    // Funció per retornar el nom del fitxer
    public function getPrivateFileName($path)
    {
        $this->authorize('viewAny', Reserva::class);

        $document = DocumentosReserva::where('url', $path)->first();
        if (!$document) {
            return response("Document no trobat a la base de dades", 404);
        }

        $filename = $document->nombre;
        $reservaId = $document->reserva_id;

        return response()->json([
            'nombre' => $filename,
        ]);
    }

    // Funció per eliminar un fitxer privat
    public function deletePrivateFile($id)
    {
        $booking_document = DocumentosReserva::findOrFail($id);

        if (Storage::disk('local')->exists($booking_document->url)) {
            Storage::disk('local')->delete($booking_document->url);
        }

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
        $this->authorize('create', Reserva::class); // Solo usuarios internos pueden descargar informes

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

        $reservas = Reserva::with(['tipoCamion', 'material1', 'material2', 'proveedor', 'transportista', 'muelle', 'estado'])
            ->whereBetween('inicio', [$from, $to])
            ->get();


        $callback = function () use ($reservas, $headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, $headers, ';', '"');

            foreach ($reservas as $reserva) {
                fputcsv($handle, [
                    $reserva->reserva_id,
                    $reserva->pedido1,
                    $reserva->tipoCamion->nombre ?? '',
                    $reserva->material1->nombre ?? '',
                    $reserva->cantidad1,
                    $reserva->proveedor->codigo_sap ?? '',
                    $reserva->proveedor->entidad->nombre ?? '',
                    '',
                    $reserva->proveedor->entidad->nif ?? '',
                    '',
                    $reserva->proveedor->entidad->email ?? '',
                    $reserva->proveedor->entidad->telefono1 ?? '',
                    '',
                    $reserva->transportista->entidad->nombre ?? '',
                    '',
                    $reserva->transportista->entidad->nif ?? '',
                    '',
                    $reserva->transportista->entidad->email ?? '',
                    $reserva->transportista->entidad->telefono1 ?? '',
                    '',
                    $reserva->matricula_camion,
                    $reserva->inicio,
                    $reserva->fin,
                    $reserva->muelle->nombre ?? '',
                    $reserva->muelle->descripcion ?? '',
                    $reserva->estado->nombre ?? '',
                    $reserva->aduana ? 1 : 0,
                    $reserva->created_at,
                    $reserva->notas,
                    '',
                    '',
                    $reserva->telefono,
                    $reserva->material2->nombre ?? '',
                    '',
                    '',
                    '',
                    '',
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

    /**
     * Envía emails de confirmación o actualización a los destinatarios correspondientes.
     */
    private function enviarEmailsReserva(Reserva $reserva)
    {
        try {
            $destinatarios = [];

            // 1. Email del proveedor
            $reserva->load(['proveedor.entidad', 'transportista.entidad']);
            
            if ($reserva->proveedor && $reserva->proveedor->entidad && $reserva->proveedor->entidad->email) {
                $destinatarios[] = $reserva->proveedor->entidad->email;
            }

            // 2. Email del transportista
            if ($reserva->transportista && $reserva->transportista->entidad && $reserva->transportista->entidad->email) {
                $destinatarios[] = $reserva->transportista->entidad->email;
            }

            // 3. Email interno configurado en parámetros
            $emailInterno = Parametro::where('clave', 'email_notificaciones_recepcion')->value('valor');
            if ($emailInterno) {
                $destinatarios[] = $emailInterno;
            }

            // Eliminar duplicados y valores vacíos
            $destinatarios = array_unique(array_filter($destinatarios));

            if (!empty($destinatarios)) {
                $reserva->load(['tipoCamion', 'material1', 'material2', 'muelle', 'empresa_lfycs']);
                Mail::to($destinatarios)->send(new ConfirmationMail($reserva));
                Log::info('Emails de confirmación enviados para reserva #' . $reserva->reserva_id . ' a: ' . implode(', ', $destinatarios));
            }
        } catch (\Exception $e) {
            Log::error('Error enviando emails para reserva #' . $reserva->reserva_id . ': ' . $e->getMessage());
        }
    }
}
