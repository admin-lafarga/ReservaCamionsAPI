<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reserva;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\HorarioMuelle;
use Carbon\Carbon;

class StoreReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_camion_id'      => ['required', 'integer', 'exists:tipo_camiones,tipo_camion_id'],
            'tipo_material1_id'   => ['required', 'integer', 'exists:materiales,material_id'],
            'tipo_material2_id'   => ['nullable', 'integer', 'exists:materiales,material_id', 'different:tipo_material1_id'],
            'proveedor_id'        => ['required', 'integer', 'exists:proveedores,proveedor_id'],
            'transporte_id'       => ['required', 'integer', 'exists:transportes,transporte_id'],
            'muelle1_id'          => ['required', 'integer', 'exists:muelles,muelle_id'],
            'muelle2_id'          => ['nullable', 'integer', 'exists:muelles,muelle_id', 'different:muelle1_id'],
            'status_id'           => ['required', 'integer', 'exists:status,status_id'],
            'cantidad1'           => ['required', 'numeric', 'min:0'],
            'cantidad2'           => ['nullable', 'numeric', 'min:0'],
            'pedido_LF'           => ['nullable', 'string', 'max:255'],
            'matricula_camion'    => ['required', 'string', 'max:50'],
            'inicio1'             => ['required', 'date'],
            'fin1'                => ['required', 'date', 'after_or_equal:inicio1'],
            'inicio2'             => ['nullable', 'date'],
            'fin2'                => ['nullable', 'date', 'after_or_equal:inicio2'],
            'es_aduana'           => ['boolean'],
            'notas'               => ['nullable', 'string'],
            'tel1'                => ['nullable', 'string', 'max:50'],
            'duracion1'           => ['required', 'numeric', 'min:0'],
            'duracion2'           => ['nullable', 'numeric', 'min:0'],
            'archivos'            => ['nullable', 'array'],
            'archivos.*'          => ['file', 'max:5120'],
        ];
    }

    // AQUÍ S'HAURÀ D'APLICAR LA LÒGICA DEL TEMA DE LA TAULA RESTRICCIONES
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $muelleIds = [$this->muelle1_id];
            if ($this->muelle2_id) {
                $muelleIds[] = $this->muelle2_id;
            }

            // Comprovem solapament amb altres reserves
            foreach ($muelleIds as $muelleId) {
                $overlap = Reserva::where(function ($q) use ($muelleId) {
                        $q->where('muelle1_id', $muelleId);
                    })
                    ->where(function ($query) {
                        $query->where('inicio1', '<', $this->fin1)
                            ->where('fin1', '>', $this->inicio1);
                    })
                    ->exists();

                if ($overlap) {
                    throw new HttpResponseException(response()->json([
                        'id'      => 2,
                        'message' => "Ya existe una reserva solapada en el muelle {$muelleId}.",
                    ], 422));
                }
            }

            // Validació contra horarios del muelle
            foreach ($muelleIds as $muelleId) {
                // Agafem el dia de la setmana
                $dayOfWeek = Carbon::parse($this->inicio1)->dayOfWeekIso;

                $horario = HorarioMuelle::where('muelle_id', $muelleId)
                    ->where('num_dia', $dayOfWeek)
                    ->first();

                if (!$horario) {
                    throw new HttpResponseException(response()->json([
                        'id'      => 3,
                        'message' => "El muelle {$muelleId} no té horari configurat per aquest dia.",
                    ], 422));
                }

                // Passem hores a format HH:MM
                $reservaInicio = Carbon::parse($this->inicio1)->format('H:i:s');
                $reservaFin    = Carbon::parse($this->fin1)->format('H:i:s');

                if ($reservaInicio < $horario->inicio || $reservaFin > $horario->fin) {
                    throw new HttpResponseException(response()->json([
                        'id'      => 4,
                        'message' => "La reserva en el muelle {$muelleId} ha d'estar dins de l'horari permès ({$horario->inicio} - {$horario->fin}).",
                    ], 422));
                }
            }
        });
    }

}
