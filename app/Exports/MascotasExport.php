<?php

namespace App\Exports;

use App\Models\Mascota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MascotasExport implements FromCollection, WithHeadings
{
    protected $propietarioId;

    public function __construct($propietarioId = null)
    {
        $this->propietarioId = $propietarioId;
    }

    public function collection()
    {
        $query = Mascota::with('propietario')->orderBy('created_at', 'desc');
        if ($this->propietarioId) {
            $query->where('propietario_id', $this->propietarioId);
        }

        return $query->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'nombre' => $m->nombre,
                'especie' => $m->especie,
                'raza' => $m->raza,
                'propietario' => optional($m->propietario)->nombre_completo,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Especie', 'Raza', 'Propietario'];
    }
}
