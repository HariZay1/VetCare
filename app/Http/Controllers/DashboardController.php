<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\Mascota;
use App\Models\Cita;
use App\Models\Veterinario;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Verificar si el usuario tiene roles asignados
        if (!$user->roles || $user->roles->isEmpty()) {
            return view('dashboard.guest')->with('warning', 'No tienes roles asignados. Contacta al administrador.');
        }
        
        // Redirigir según rol
        if ($user->hasRole('admin')) {
            return $this->dashboardAdmin();
        } elseif ($user->hasRole('recepcion')) {
            return $this->dashboardRecepcion();
        } elseif ($user->hasRole('veterinario')) {
            return $this->dashboardVeterinario();
        } elseif ($user->hasRole('cliente')) {
            return $this->dashboardCliente();
        }

        return view('dashboard')->with('info', 'Bienvenido al sistema');
    }
    
    private function dashboardAdmin()
    {
        // KPIs principales
        $totalMascotas = Mascota::where('activo', true)->count();
        $totalPropietarios = Propietario::count();
        $citasHoy = Cita::whereDate('fecha_hora', today())->count();
        $citasPendientes = Cita::where('estado', 'pendiente')->count();
        
        // Gráfico: Citas últimos 7 días
        $citasPorDia = Cita::whereBetween('fecha_hora', [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        ])
        ->select(DB::raw('DATE(fecha_hora) as fecha'), DB::raw('count(*) as total'))
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();
        
        // Próximas 5 citas
        $proximasCitas = Cita::with(['mascota', 'propietario', 'veterinario'])
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->take(5)
            ->get();
        
        // Estadísticas por estado
        $citasPorEstado = Cita::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        // Ingresos del mes
        $ingresosMes = Cita::whereMonth('fecha_hora', now()->month)
            ->whereYear('fecha_hora', now()->year)
            ->sum('costo');
        
        return view('dashboard.admin', compact(
            'totalMascotas',
            'totalPropietarios',
            'citasHoy',
            'citasPendientes',
            'citasPorDia',
            'proximasCitas',
            'citasPorEstado',
            'ingresosMes'
        ));
    }

    private function dashboardRecepcion()
    {
        // Citas del día
        $citasHoy = Cita::with(['mascota', 'propietario', 'veterinario'])
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        // Citas pendientes de confirmar
        $citasPendientes = Cita::with(['mascota', 'propietario'])
            ->where('estado', 'pendiente')
            ->orderBy('fecha_hora', 'asc')
            ->take(10)
            ->get();
        
        // Próximas citas (próximos 3 días)
        $proximasCitas = Cita::with(['mascota', 'propietario', 'veterinario'])
            ->whereBetween('fecha_hora', [now(), now()->addDays(3)->endOfDay()])
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        return view('dashboard.recepcion', compact(
            'citasHoy',
            'citasPendientes',
            'proximasCitas'
        ));
    }

    private function dashboardVeterinario()
    {
        $veterinario = Veterinario::where('user_id', auth()->id())->first();
        
        if (!$veterinario) {
            return redirect()->route('dashboard')->with('error', 'No se encontró el perfil de veterinario.');
        }
        
        // Mi agenda del día
        $citasHoy = Cita::with(['mascota', 'propietario'])
            ->where('veterinario_id', $veterinario->id)
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        // Próximas citas
        $proximasCitas = Cita::with(['mascota', 'propietario'])
            ->where('veterinario_id', $veterinario->id)
            ->where('fecha_hora', '>', now())
            ->orderBy('fecha_hora', 'asc')
            ->take(5)
            ->get();
        
        // Citas completadas hoy
        $citasCompletadasHoy = Cita::where('veterinario_id', $veterinario->id)
            ->whereDate('fecha_hora', today())
            ->where('estado', 'completada')
            ->count();
        
        return view('dashboard.veterinario', compact(
            'veterinario',
            'citasHoy',
            'proximasCitas',
            'citasCompletadasHoy'
        ));
    }

    private function dashboardCliente()
    {
        $propietario = Propietario::where('user_id', auth()->id())->first();
        
        if (!$propietario) {
            return redirect()->route('dashboard')->with('info', 'Completa tu perfil de propietario.');
        }
        
        $misMascotas = Mascota::where('propietario_id', $propietario->id)
            ->where('activo', true)
            ->get();
        
        $proximasCitas = Cita::with(['mascota', 'veterinario'])
            ->where('propietario_id', $propietario->id)
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->get();
    
        $historialCitas = Cita::with(['mascota', 'veterinario'])
            ->where('propietario_id', $propietario->id)
            ->where('fecha_hora', '<', now())
            ->orderBy('fecha_hora', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard.cliente', compact(
            'propietario',
            'misMascotas',
            'proximasCitas',
            'historialCitas'
        ));
    }

    public function reportes()
    {
        if (!auth()->user()->can('ver_reportes')) {
            abort(403, 'No tienes permiso para ver reportes.');
        }
        
        return view('reportes.index');
    }
}