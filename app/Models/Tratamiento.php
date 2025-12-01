<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    protected $fillable = [
        'cita_id',
        'mascota_id',
        'descripcion',
        'receta',
        'costo',
        'fecha_seguimiento',
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'fecha_seguimiento' => 'date',
    ];

    // Relaciones
    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }
}