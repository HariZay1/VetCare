<?php

namespace App\Exports;

use App\Models\Propietario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PropietariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Propietario::with('user')->orderBy('created_at', 'desc')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nombre' => $p->nombre_completo,
                'ci' => $p->ci,
                'telefono' => $p->telefono,
                'email' => $p->email,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'CI', 'Teléfono', 'Email'];
    }
}
