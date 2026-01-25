<?php

namespace App\Http\Controllers;

use App\Models\Entidad;
use Illuminate\Http\Request;

class EntidadController extends Controller
{
    public function update(Request $request, Entidad $entidad)
    {
        // Validar que el usuario autenticado es el dueño de la entidad
        $user = $request->user();
        
        // Si el usuario autenticado es una Entidad, verificar que sea la misma
        if ($user instanceof \App\Models\Entidad) {
            if ($user->entidad_id !== $entidad->entidad_id) {
                abort(403, 'No tienes permiso para editar esta entidad');
            }
        }
        // Si es un User (admin), permitir la edición
        // No hacemos nada, los admins pueden editar cualquier entidad

        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',
            'telefono1' => 'required',
            //'pin' => 'nullable|string',
        ]);

        $entidad->update($data);
        
        return $entidad;
    }
}
