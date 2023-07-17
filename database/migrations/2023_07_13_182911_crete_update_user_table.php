<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('updateuser', function (Blueprint $table) {
            $table->id();
            $table->integer('id_proyecto');
            $table->integer('numero_semana');
            $table->integer('anio');
            $table->integer('personas');
            $table->text('aprender');
            $table->date('rango1')->nullable();
            $table->date('rango2')->nullable();
            $table->integer('mes')->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updateuser');
    }
};
