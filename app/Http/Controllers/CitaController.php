<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Propietario;
use App\Models\Veterinario;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;

class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|recepcion')->except(['misCitas', 'solicitar', 'miAgenda', 'completar']);
    }

    public function index(Request $request)
    {
        $query = Cita::with(['mascota.propietario', 'veterinario']);

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('veterinario_id')) {
            $query->where('veterinario_id', $request->veterinario_id);
        }

        if ($request->filled('mascota_id')) {
            $query->where('mascota_id', $request->mascota_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('motivo', 'like', "%{$search}%")
                  ->orWhereHas('mascota', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('propietario', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%");
                  });
            });
        }

        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(20);
        $veterinarios = Veterinario::activos()->get();

        return view('cita.index', compact('citas', 'veterinarios'));
    }

    public function create(Request $request)
    {
        $propietarios = Propietario::orderBy('nombre')->get();
        $mascotas = Mascota::activos()->with('propietario')->get();
        $veterinarios = Veterinario::activos()->get();
        
        $selectedMascota = $request->mascota_id ? Mascota::find($request->mascota_id) : null;

        return view('cita.create', compact('propietarios', 'mascotas', 'veterinarios', 'selectedMascota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'propietario_id' => 'required|exists:propietarios,id',
            'veterinario_id' => 'nullable|exists:veterinarios,id',
            'fecha_hora' => 'required|date',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,en_proceso,completada,cancelada',
            'notas' => 'nullable|string',
        ]);

        $cita = Cita::create($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita registrada exitosamente');
    }

    public function show(Cita $cita)
    {
        $cita->load(['mascota.propietario', 'veterinario', 'tratamientos']);
        return view('cita.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $propietarios = Propietario::orderBy('nombre')->get();
        $mascotas = Mascota::where('propietario_id', $cita->propietario_id)->get();
        $veterinarios = Veterinario::activos()->get();

        return view('cita.edit', compact('cita', 'propietarios', 'mascotas', 'veterinarios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'propietario_id' => 'required|exists:propietarios,id',
            'veterinario_id' => 'nullable|exists:veterinarios,id',
            'fecha_hora' => 'required|date',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,en_proceso,completada,cancelada',
            'notas' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'receta' => 'nullable|string',
            'costo' => 'nullable|numeric|min:0',
        ]);

        $cita->update($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }

    
 
    public function completar(Request $request, Cita $cita)
    {
        $request->validate([
            'diagnostico' => 'required|string',
            'receta' => 'nullable|string',
            'costo' => 'nullable|numeric|min:0',
        ]);

        $cita->update([
            'diagnostico' => $request->diagnostico,
            'receta' => $request->receta,
            'costo' => $request->costo,
            'estado' => 'completada',
        ]);

        // Si hay fecha de seguimiento, crear tratamiento
        if ($request->fecha_seguimiento) {
            Tratamiento::create([
                'cita_id' => $cita->id,
                'mascota_id' => $cita->mascota_id,
                'descripcion' => $request->diagnostico,
                'receta' => $request->receta,
                'costo' => $request->costo ?? 0,
                'fecha_seguimiento' => $request->fecha_seguimiento,
            ]);
        }

        return redirect()->back()->with('success', 'Cita completada exitosamente');
    }

    public function miAgenda()
    {
        $veterinario = Veterinario::where('user_id', auth()->id())->firstOrFail();
        
        $citasHoy = Cita::with(['mascota', 'propietario'])
            ->where('veterinario_id', $veterinario->id)
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        $proximasCitas = Cita::with(['mascota', 'propietario'])
            ->where('veterinario_id', $veterinario->id)
            ->where('fecha_hora', '>', now())
            ->orderBy('fecha_hora', 'asc')
            ->take(5)
            ->get();
        
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

    /**
     * Mostrar todas las citas del veterinario autenticado
     */
    public function indexVeterinario(Request $request)
    {
        $veterinario = Veterinario::where('user_id', auth()->id())->firstOrFail();
        // Forzar filtro por veterinario
        $request->merge(['veterinario_id' => $veterinario->id]);

        return $this->index($request);
    }

    /**
     * Mostrar ficha de una cita para el veterinario autenticado (solo su propia cita)
     */
    public function showVeterinario(Cita $cita)
    {
        $veterinario = Veterinario::where('user_id', auth()->id())->firstOrFail();

        if ($cita->veterinario_id !== $veterinario->id) {
            abort(403, 'No tienes permiso para ver esta cita');
        }

        $cita->load(['mascota.propietario', 'veterinario', 'tratamientos']);

        return view('cita.show', compact('cita'));
    }

    public function misCitas()
    {
        $propietario = Propietario::where('user_id', auth()->id())->firstOrFail();
        
        $proximasCitas = Cita::with(['mascota', 'veterinario'])
            ->where('propietario_id', $propietario->id)
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        $historialCitas = Cita::with(['mascota', 'veterinario'])
            ->where('propietario_id', $propietario->id)
            ->where('fecha_hora', '<', now())
            ->orderBy('fecha_hora', 'desc')
            ->paginate(10);
        
        return view('cliente.citas', compact('propietario', 'proximasCitas', 'historialCitas'));
    }

    public function solicitar(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'propietario_id' => 'required|exists:propietarios,id',
            'fecha_hora' => 'required|date|after:now',
            'motivo' => 'required|string|max:255',
        ]);

        Cita::create([
            'mascota_id' => $request->mascota_id,
            'propietario_id' => $request->propietario_id,
            'fecha_hora' => $request->fecha_hora,
            'motivo' => $request->motivo,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Solicitud de cita enviada. Recepción la confirmará pronto.');
    }

    public function generarPdfCita(Cita $cita)
    {
        // Permisos: admin/recepcion pueden generar cualquier PDF
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->hasRole('recepcion')) {
            $cita->load(['mascota', 'veterinario', 'propietario']);
            $pdf = PDF::loadView('pdf.cita', compact('cita'));
            return $pdf->download("cita_{$cita->id}.pdf");
        }

        // Si no es admin/recepcion, permitir solo al propietario dueño de la cita
        $propietario = Propietario::where('user_id', $user->id)->first();
        if ($propietario && $cita->propietario_id === $propietario->id) {
            $cita->load(['mascota', 'veterinario', 'propietario']);
            $pdf = PDF::loadView('pdf.cita', compact('cita'));
            return $pdf->download("cita_{$cita->id}.pdf");
        }

        abort(403, 'User does not have the right roles.');
    }

    /**
     * Permitir a un propietario descargar el PDF de su propia cita
     */
    public function generarPdfCitaCliente(Cita $cita)
    {
        $propietario = Propietario::where('user_id', auth()->id())->first();
        if (!$propietario) {
            abort(403, 'No tienes perfil de propietario.');
        }

        if ($cita->propietario_id !== $propietario->id) {
            abort(403, 'No tienes permiso para ver esta cita');
        }

        $cita->load(['mascota', 'veterinario', 'propietario']);
        $pdf = PDF::loadView('pdf.cita', compact('cita'));
        return $pdf->download("cita_{$cita->id}.pdf");
    }

    public function exportExcel()
    {
        return Excel::download(new CitasExport, 'citas_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $citas = Cita::with(['mascota', 'propietario', 'veterinario'])
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        $pdf = PDF::loadView('pdf.citas-lista', compact('citas'));
        return $pdf->download('citas_hoy_' . now()->format('Y-m-d') . '.pdf');
    }
    
}