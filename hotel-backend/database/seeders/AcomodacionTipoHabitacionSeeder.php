<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcomodacionTipoHabitacionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs
        $estandarId = DB::table('tipo_habitacions')->where('nombre', 'ESTANDAR')->value('id');
        $juniorId = DB::table('tipo_habitacions')->where('nombre', 'JUNIOR')->value('id');

        $sencillaId = DB::table('acomodacions')->where('nombre', 'SENCILLA')->value('id');
        $dobleId = DB::table('acomodacions')->where('nombre', 'DOBLE')->value('id');
        $tripleId = DB::table('acomodacions')->where('nombre', 'TRIPLE')->value('id');

        // Insertar relaciones
        DB::table('acomodacion_tipo_habitacion')->insert([
            ['tipo_habitacion_id' => $estandarId, 'acomodacion_id' => $sencillaId],
            ['tipo_habitacion_id' => $estandarId, 'acomodacion_id' => $dobleId],
            ['tipo_habitacion_id' => $juniorId, 'acomodacion_id' => $tripleId],
        ]);
    }
}
