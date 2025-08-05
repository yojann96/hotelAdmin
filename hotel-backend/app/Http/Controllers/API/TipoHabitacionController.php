<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TipoHabitacion;
use Illuminate\Http\JsonResponse;

class TipoHabitacionController extends Controller
{
    /**
     * Retorna todos los tipos de habitación.
     */
    public function index(): JsonResponse
    {
        $tipos = TipoHabitacion::all(['id', 'nombre']);
        return response()->json($tipos);
    }
}
