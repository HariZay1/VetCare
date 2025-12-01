<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('ci', 20)->unique();
            $table->string('telefono', 20);
            $table->string('email')->unique();
            $table->text('direccion')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            
            $table->index('ci');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propietarios');
    }
};