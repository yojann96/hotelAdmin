<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcomodacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acomodacions')->insert([
            ['nombre' => 'Sencilla', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Doble', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Triple', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
