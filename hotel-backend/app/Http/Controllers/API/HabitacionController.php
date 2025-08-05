<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HabitacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Habitacion::with(['hotel', 'tipo', 'acomodacion']);

        if ($request->has('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|exists:hotels,id',
            'tipo_habitacion_id' => 'required|exists:tipo_habitacions,id',
            'acomodacion_id' => 'required|exists:acomodacions,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Evitar duplicados si ya existe una combinación
        $existe = Habitacion::where([
            'hotel_id' => $request->hotel_id,
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'acomodacion_id' => $request->acomodacion_id,
        ])->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ya existe esta combinación de habitación para el hotel.'
            ], 409);
        }

        Habitacion::create([
            'hotel_id' => $request->hotel_id,
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'acomodacion_id' => $request->acomodacion_id,
            'cantidad' => $request->cantidad,
        ]);

        return response()->json(['message' => 'Habitación creada correctamente.'], 201);
    }

    public function show($id)
    {
        $habitacion = Habitacion::with(['hotel', 'tipo', 'acomodacion'])->findOrFail($id);
        return response()->json($habitacion);
    }

    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $validated = $request->validate([
            'tipo_habitacion_id' => 'required|exists:tipo_habitacions,id',
            'acomodacion_id' => 'required|exists:acomodacions,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Validar el nuevo total
        $hotel = $habitacion->hotel;
        $habitacionesExistentes = Habitacion::where('hotel_id', $hotel->id)
            ->where('id', '!=', $habitacion->id)
            ->sum('cantidad');

        if (($habitacionesExistentes + $validated['cantidad']) > $hotel->numero_habitaciones) {
            return response()->json(['error' => 'Excede el número de habitaciones permitidas por el hotel.'], 422);
        }

        $habitacion->update($validated);

        return response()->json($habitacion->load(['hotel', 'tipo', 'acomodacion']));
    }

    public function destroy($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->delete();

        return response()->json(['message' => 'Habitación eliminada correctamente.']);
    }

    public function obtenerOpciones()
    {
        $tipos = \App\Models\TipoHabitacion::all();
        $acomodaciones = \App\Models\Acomodacion::all();

        return response()->json([
            'tipos' => $tipos,
            'acomodaciones' => $acomodaciones,
        ]);
    }

    public function porHotel(Request $request)
    {
        $hotelId = $request->query('hotel_id');

        if (!$hotelId) {
            return response()->json(['error' => 'hotel_id requerido'], 400);
        }

        $habitaciones = Habitacion::with(['tipo', 'acomodacion'])
            ->where('hotel_id', $hotelId)
            ->get();

        return response()->json($habitaciones);
    }
}
