<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Seeder
{
    public function run()
    {
        DB::table('hotels')->insert([
            [
                'nombre' => 'Hotel Paraíso',
                'direccion' => 'Calle 123 #45-67',
                'ciudad' => 'Bogotá',
                'nit' => '900123456-1',
                'numero_habitaciones' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Hotel Mar Azul',
                'direccion' => 'Av. del Mar #12-34',
                'ciudad' => 'Cartagena',
                'nit' => '900987654-2',
                'numero_habitaciones' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Hotel Montañas',
                'direccion' => 'Carrera 45 #67-89',
                'ciudad' => 'Medellín',
                'nit' => '900555444-3',
                'numero_habitaciones' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
