<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function index()
    {
        return Hotel::with('habitaciones.tipo', 'habitaciones.acomodacion')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                Rule::unique('hotels')->where(function ($query) use ($request) {
                    return $query->where('nit', $request->nit);
                }),
            ],
            'direccion' => 'required',
            'ciudad' => 'required',
            'nit' => 'required',
            'numero_habitaciones' => 'required|integer|min:1',
        ], [
            'nombre.unique' => 'Ya existe un hotel registrado con este nombre y NIT.',
        ]);

        return Hotel::create($validated);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $validated = $request->validate([
            'nombre' => [
                'required',
                Rule::unique('hotels')->ignore($hotel->id)->where(function ($query) use ($request) {
                    return $query->where('nit', $request->nit);
                }),
            ],
            'direccion' => 'required',
            'ciudad' => 'required',
            'nit' => 'required',
            'numero_habitaciones' => 'required|integer|min:1',
        ], [
            'nombre.unique' => 'Ya existe un hotel registrado con este nombre y NIT.',
        ]);

        $hotel->update($validated);

        return $hotel;
    }
}
