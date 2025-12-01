<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->foreignId('propietario_id')->constrained('propietarios')->cascadeOnDelete();
            $table->foreignId('veterinario_id')->nullable()->constrained('veterinarios')->nullOnDelete();
            $table->dateTime('fecha_hora');
            $table->string('motivo', 255);
            $table->enum('estado', ['pendiente', 'confirmada', 'en_proceso', 'completada', 'cancelada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('receta')->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index(['fecha_hora', 'estado']);
            $table->index('veterinario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};