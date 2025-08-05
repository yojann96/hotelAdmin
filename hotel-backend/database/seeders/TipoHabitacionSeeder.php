<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoHabitacionSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipo_habitacions')->insert([
            ['nombre' => 'Estandar', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Junior', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Suite', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
