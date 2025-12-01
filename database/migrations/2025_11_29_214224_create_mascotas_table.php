<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id')->constrained('propietarios')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->enum('especie', ['perro', 'gato', 'ave', 'conejo', 'reptil', 'otro']);
            $table->string('raza', 100)->nullable();
            $table->enum('sexo', ['macho', 'hembra'])->nullable();
            $table->string('color', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('foto')->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('propietario_id');
            $table->index('especie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};