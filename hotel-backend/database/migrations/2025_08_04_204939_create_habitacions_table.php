<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('habitacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('tipo_habitacion_id')->constrained();
            $table->foreignId('acomodacion_id')->constrained();
            $table->unsignedInteger('cantidad');
            $table->timestamps();

            $table->unique(['hotel_id', 'tipo_habitacion_id', 'acomodacion_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitacions');
    }
};
