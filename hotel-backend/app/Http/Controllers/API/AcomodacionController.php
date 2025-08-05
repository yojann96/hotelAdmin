<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Acomodacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\JsonResponse;

class AcomodacionController extends Controller
{
    /**
     * Retorna todas las acomodaciones.
     */
    public function index(): JsonResponse
    {
        $acomodaciones = Acomodacion::all(['id', 'nombre']);
        return response()->json($acomodaciones);
    }

    public function porTipoHabitacion($tipoHabitacionId)
    {
        $tipo = TipoHabitacion::with('acomodaciones')->findOrFail($tipoHabitacionId);
        return response()->json($tipo->acomodaciones);
    }
}
