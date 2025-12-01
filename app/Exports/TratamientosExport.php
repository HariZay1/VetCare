<?php

namespace App\Exports;

use App\Models\Tratamiento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TratamientosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tratamiento::with(['cita','mascota'])->orderBy('created_at', 'desc')->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'cita_motivo' => optional($t->cita)->motivo,
                'mascota' => optional($t->mascota)->nombre,
                'descripcion' => $t->descripcion,
                'receta' => $t->receta,
                'costo' => $t->costo,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Motivo Cita', 'Mascota', 'Descripción', 'Receta', 'Costo'];
    }
}
