<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HorarioMuelle;

class StoreHorarioMuelleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'muelle_id' => 'required|exists:muelles,muelle_id',
            'dia_semana'       => 'required|in:1,2,3,4,5,6,7',
            'inicio'    => 'required|date_format:H:i',
            'fin'       => 'required|date_format:H:i|after:inicio',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = HorarioMuelle::where('muelle_id', $this->muelle_id)
                ->where('dia_semana', $this->dia_semana)
                ->exists();

            if ($exists) {
                $validator->errors()->add('message', 'Ya existe un horario para este muelle en este día.');
            }
        });
    }
}
