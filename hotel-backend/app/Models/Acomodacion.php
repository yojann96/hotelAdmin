<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acomodacion extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }

    public function tipoHabitaciones()
    {
        return $this->belongsToMany(TipoHabitacion::class);
    }
}
