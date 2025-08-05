<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitacionSeeder extends Seeder
{
    public function run()
    {
        // Ejemplo de datos: ajustar los IDs según tus datos reales
        DB::table('habitacions')->insert([
            [
                'hotel_id' => 1,
                'tipo_habitacion_id' => 1,
                'acomodacion_id' => 1,
                'cantidad' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 1,
                'tipo_habitacion_id' => 2,
                'acomodacion_id' => 2,
                'cantidad' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hotel_id' => 2,
                'tipo_habitacion_id' => 3,
                'acomodacion_id' => 1,
                'cantidad' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
