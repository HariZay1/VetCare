<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cita extends Model
{
    protected $fillable = [
        'mascota_id',
        'propietario_id',
        'veterinario_id',
        'fecha_hora',
        'motivo',
        'estado',
        'notas',
        'diagnostico',
        'receta',
        'costo',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'costo' => 'decimal:2',
    ];

    // Relaciones
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Propietario::class);
    }

    public function veterinario(): BelongsTo
    {
        return $this->belongsTo(Veterinario::class);
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

    // Accessors
    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'badge bg-warning text-dark',
            'confirmada' => 'badge bg-info',
            'en_proceso' => 'badge bg-primary',
            'completada' => 'badge bg-success',
            'cancelada' => 'badge bg-danger',
            default => 'badge bg-secondary',
        };
    }

    public function getEstadoTextoAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'en_proceso' => 'En Proceso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            default => 'Desconocido',
        };
    }

    // Scopes
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha_hora', today());
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeProximas($query)
    {
        return $query->where('fecha_hora', '>=', now())
                     ->orderBy('fecha_hora', 'asc');
    }

    public function scopePorVeterinario($query, $veterinarioId)
    {
        return $query->where('veterinario_id', $veterinarioId);
    }
}