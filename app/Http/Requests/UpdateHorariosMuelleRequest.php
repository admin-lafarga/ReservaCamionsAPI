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
            'muelle_id' => 'sometimes|exists:muelles,muelle_id',
            'dia'       => 'sometimes|string|max:50',
            'num_dia'   => 'sometimes|string|max:2',
            'inicio'    => 'sometimes|date_format:H:i',
            'fin'       => 'sometimes|date_format:H:i|after:inicio',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $horarioId = $this->route('horariosMuelle')->horarios_muelle_id;

            $exists = HorarioMuelle::where('muelle_id', $this->muelle_id ?? $this->route('horariosMuelle')->muelle_id)
                ->where('dia', $this->dia ?? $this->route('horariosMuelle')->dia)
                ->where('horarios_muelle_id', '!=', $horarioId)
                ->exists();

            if ($exists) {
                $validator->errors()->add('dia', 'Ya existe un horario para este muelle en este día.');
            }
        });
    }
}
