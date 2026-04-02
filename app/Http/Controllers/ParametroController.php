<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParametroRequest;
use App\Http\Requests\UpdateParametroRequest;
use App\Models\Parametro;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Parametro::class, 'config');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parametros = Parametro::all();
        return response()->json($parametros);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParametroRequest $request)
    {
        $parametro = Parametro::create($request->validated());
        return response()->json($parametro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Parametro $parametro)
    {
        return response()->json($parametro);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParametroRequest $request, Parametro $parametro)
    {
        $parametro->update($request->validated());
        return response()->json($parametro);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parametro $parametro)
    {
        $parametro->delete();
        return response()->json(null, 204);
    }


    /**
     * Get multiple parameters by keys
     */
    public function getParametrosByKeys(Request $request) {
        $this->authorize('viewAny', Parametro::class);

        $keys = (array) $request->input('keys', []);

        $parametros = Parametro::whereIn('clave', $keys)
            ->pluck('valor', 'clave');

        return response()->json($parametros);
    }

    /**
     * Store multiple parameters by keys
     */
    public function storeParametrosByKeys(Request $request) {
        $this->authorize('update', new Parametro());

        $data = $request->all();
        foreach ($data as $key => $value) {
            Parametro::updateOrCreate(
                ['clave' => $key],
                ['valor' => $value]
            );
        }

        return response()->json(['message' => 'Parámetros actualizados correctamente.']);
    }
}
