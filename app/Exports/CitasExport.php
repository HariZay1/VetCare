<?php

namespace App\Exports;

use App\Models\Cita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CitasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Cita::with(['mascota', 'propietario', 'veterinario'])
            ->orderBy('fecha_hora', 'asc')
            ->get()
            ->map(function ($cita) {
                return [
                    'id' => $cita->id,
                    'fecha_hora' => $cita->fecha_hora->format('Y-m-d H:i'),
                    'mascota' => optional($cita->mascota)->nombre,
                    'propietario' => optional($cita->propietario)->nombre_completo,
                    'veterinario' => optional($cita->veterinario)->nombre_completo ?? '-',
                    'motivo' => $cita->motivo,
                    'estado' => $cita->estado,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID', 'Fecha Hora', 'Mascota', 'Propietario', 'Veterinario', 'Motivo', 'Estado'
        ];
    }
}
