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
        Schema::create('updateanimo', function (Blueprint $table) {
            $table->id();
            $table->integer('id_proyecto');
            $table->integer('animo');
            $table->text('metrica');
            $table->text('obstaculo');
            $table->date('rango1')->nullable();
            $table->date('rango2')->nullable();
            $table->integer('mes')->nullable();
            $table->integer('anio')->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updateanimo');
    }
};
