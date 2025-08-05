<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HotelController;
use App\Http\Controllers\API\HabitacionController;
use App\Http\Controllers\API\TipoHabitacionController;
use App\Http\Controllers\API\AcomodacionController;

// Hoteles
Route::apiResource('hoteles', HotelController::class);

// Habitaciones
Route::controller(HabitacionController::class)->group(function () {
    Route::get('/hoteles/{hotel}/habitaciones', 'index');
    Route::get('/habitaciones/opciones', 'obtenerOpciones');
    //Route::get('/habitaciones', 'todas');
    Route::post('/habitaciones', 'store');
    Route::put('/habitaciones/{id}', 'update');
    Route::delete('/habitaciones/{id}', 'destroy');
});

Route::get('/habitaciones', [HabitacionController::class, 'porHotel']);
Route::get('/acomodaciones/por-tipo/{id}', [AcomodacionController::class, 'porTipoHabitacion']);

// Tipos de habitación y acomodaciones
Route::get('/tipo-habitaciones', [TipoHabitacionController::class, 'index']);
Route::get('/acomodaciones', [AcomodacionController::class, 'index']);
