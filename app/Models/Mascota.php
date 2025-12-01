<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Mascota extends Model
{
    protected $fillable = [
        'propietario_id',
        'nombre',
        'especie',
        'raza',
        'sexo',
        'color',
        'fecha_nacimiento',
        'foto',
        'notas',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    protected $appends = ['foto_url', 'edad'];

    // Relaciones
    public function propietario(): BelongsTo
    {
        return $this->belongsTo(Propietario::class);
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }

    // Accessors
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return Storage::url($this->foto);
        }
        return asset('images/default-pet.png');
    }

    public function getEdadAttribute(): ?string
    {
        if (!$this->fecha_nacimiento) {
            return null;
        }

        $diff = $this->fecha_nacimiento->diff(now());
        
        if ($diff->y > 0) {
            return $diff->y . ' año' . ($diff->y > 1 ? 's' : '');
        }
        
        if ($diff->m > 0) {
            return $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
        }
        
        return $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nombre', 'like', "%{$search}%")
                     ->orWhereHas('propietario', function($q) use ($search) {
                         $q->where('nombre', 'like', "%{$search}%")
                           ->orWhere('apellido', 'like', "%{$search}%");
                     });
    }
}