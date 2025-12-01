<?php

namespace App\Exports;

use App\Models\Veterinario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VeterinariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Veterinario::with('user')->orderBy('created_at', 'desc')->get()->map(function ($v) {
            return [
                'id' => $v->id,
                'nombre' => $v->nombre_completo,
                'especialidad' => $v->especialidad,
                'telefono' => $v->telefono,
                'usuario' => optional($v->user)->email,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Especialidad', 'Teléfono', 'Usuario'];
    }
}
