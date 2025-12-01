<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Propietario extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'email',
        'direccion',
        'user_id',
    ];

    protected $appends = ['nombre_completo'];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    // Accessors
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('apellido', 'like', "%{$search}%")
              ->orWhere('ci', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}