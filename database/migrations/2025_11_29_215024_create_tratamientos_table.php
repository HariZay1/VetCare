<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('mascota_id')->constrained('mascotas')->cascadeOnDelete();
            $table->text('descripcion');
            $table->text('receta')->nullable();
            $table->decimal('costo', 10, 2)->default(0);
            $table->date('fecha_seguimiento')->nullable();
            $table->timestamps();
            
            $table->index('cita_id');
            $table->index('mascota_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};