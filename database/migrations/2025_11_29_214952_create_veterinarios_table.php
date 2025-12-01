<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veterinarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('especialidad', 100)->nullable();
            $table->string('telefono', 20);
            $table->string('horario')->nullable(); // Ej: "Lun-Vie 8am-6pm"
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veterinarios');
    }
};