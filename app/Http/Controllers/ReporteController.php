<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Propietario;
use App\Models\Veterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        // Fechas por defecto (mes actual)
        $fechaInicio = $request->fecha_inicio ?? now()->startOfMonth()->format('Y-m-d');
        $fechaFin = $request->fecha_fin ?? now()->endOfMonth()->format('Y-m-d');

        // Estadísticas Generales
        $totalMascotas = Mascota::count();
        $mascotasActivas = Mascota::where('activo', true)->count();
        $totalPropietarios = Propietario::count();
        $totalVeterinarios = Veterinario::where('activo', true)->count();

        // Citas en el período
        $citasPeriodo = Cita::whereBetween('fecha_hora', [$fechaInicio, $fechaFin])->count();
        $citasCompletadas = Cita::whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->where('estado', 'completada')
            ->count();
        $citasCanceladas = Cita::whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->where('estado', 'cancelada')
            ->count();

        // Ingresos del período
        $ingresosPeriodo = Cita::whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->where('estado', 'completada')
            ->sum('costo');

        // Citas por veterinario
        $citasPorVeterinario = Cita::with('veterinario')
            ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->select('veterinario_id', DB::raw('count(*) as total'))
            ->groupBy('veterinario_id')
            ->get();

        // Mascotas por especie
        $mascotasPorEspecie = Mascota::select('especie', DB::raw('count(*) as total'))
            ->groupBy('especie')
            ->get();

        // Top 10 propietarios con más citas
        $topPropietarios = Propietario::withCount(['citas' => function($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
            }])
            ->orderBy('citas_count', 'desc')
            ->take(10)
            ->get();

        return view('reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'totalMascotas',
            'mascotasActivas',
            'totalPropietarios',
            'totalVeterinarios',
            'citasPeriodo',
            'citasCompletadas',
            'citasCanceladas',
            'ingresosPeriodo',
            'citasPorVeterinario',
            'mascotasPorEspecie',
            'topPropietarios'
        ));
    }
}