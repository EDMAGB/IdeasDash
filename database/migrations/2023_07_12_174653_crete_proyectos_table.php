<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
   {
       Schema::create('proyectos', function (Blueprint $table) {
           $table->id('id_proyecto');
           $table->string('nombre_proyecto')->unique();
           $table->longText('descripcion_proyecto');
           $table->text('url')->nullable();
           $table->char('fase',10);
           $table->timestamps();
       });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
       Schema::dropIfExists('proyectos');
   }
};
