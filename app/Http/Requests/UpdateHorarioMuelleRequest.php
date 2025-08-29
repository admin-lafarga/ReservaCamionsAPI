<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HorarioMuelle;

class UpdateHorarioMuelleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'muelle_id' => 'required|exists:muelles,muelle_id',
            'dia'       => 'required|string|max:50',
            'num_dia'   => 'required|string|max:2',
            'inicio'    => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/'], // Validem que funcionin correctament els següents formats 15:00 i 15:00:00
            'fin'       => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', 'after:inicio'], // Validem que funcionin correctament els següents formats 15:00 i 15:00:00
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $horario = $this->route('horario');

            if (!$horario) return;

            $muelle_id = $this->muelle_id ?? $horario->muelle_id;
            $num_dia   = $this->num_dia ?? $horario->num_dia;

            $exists = HorarioMuelle::where('muelle_id', $muelle_id)
                ->where('num_dia', $num_dia)
                ->where('horarios_muelle_id', '!=', $horario->horarios_muelle_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('message', 'Ya existe un horario para este muelle en este día.');
            }
        });
    }
}
