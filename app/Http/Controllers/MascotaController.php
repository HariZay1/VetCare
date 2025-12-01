<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MascotasExport;

class MascotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Permitir a los clientes acceder a los métodos necesarios para crear/editar/ver sus propias mascotas
        $this->middleware('role:admin|recepcion')->except(['misMascotas', 'create', 'store', 'edit', 'update', 'show']);
    }

    public function exportExcel()
    {
        return Excel::download(new MascotasExport, 'mascotas_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $mascotas = Mascota::with('propietario')->orderBy('created_at', 'desc')->get();
        $pdf = PDF::loadView('pdf.mascotas-lista', compact('mascotas'));
        return $pdf->download('mascotas_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportaciones para clientes (solo sus mascotas)
     */
    public function exportExcelClient()
    {
        $propietario = Propietario::where('user_id', auth()->id())->firstOrFail();
        return Excel::download(new MascotasExport($propietario->id), 'mis_mascotas_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdfClient()
    {
        $propietario = Propietario::where('user_id', auth()->id())->firstOrFail();
        $mascotas = Mascota::with('propietario')->where('propietario_id', $propietario->id)->orderBy('created_at', 'desc')->get();
        $pdf = PDF::loadView('pdf.mascotas-lista', compact('mascotas'));
        return $pdf->download('mis_mascotas_' . now()->format('Y-m-d') . '.pdf');
    }

    public function index(Request $request)
    {
        $query = Mascota::with('propietario');
        $propietario = Propietario::where('user_id', auth()->id())->first();


        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('especie', 'like', "%{$search}%")
                  ->orWhere('raza', 'like', "%{$search}%")
                  ->orWhereHas('propietario', function($q) use ($search) {
                      $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido', 'like', "%{$search}%")
                        ->orWhere('ci', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por especie
        if ($request->filled('especie')) {
            $query->where('especie', $request->especie);
        }

        // Filtro por propietario
        if ($request->filled('propietario_id')) {
            $query->where('propietario_id', $request->propietario_id);
        }

        // Filtro por estado
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $mascotas = $query->orderBy('created_at', 'desc')->paginate(12);
        $propietarios = Propietario::orderBy('nombre')->get();

        return view('mascota.index', compact('mascotas', 'propietarios'));

    }

    public function create()
    {
        // Si es cliente, obtener su propietario
        $propietarioCliente = null;
        if (auth()->user()->hasRole('cliente')) {
            $propietarioCliente = Propietario::where('user_id', auth()->id())->first();
            if (!$propietarioCliente) {
                return redirect()->route('dashboard')
                    ->with('error', 'Debes tener un perfil de propietario para registrar mascotas');
            }
        }
        
        $propietarios = auth()->user()->hasRole('cliente') 
            ? collect([$propietarioCliente]) 
            : Propietario::orderBy('nombre')->get();
            
        return view('mascota.create', compact('propietarios', 'propietarioCliente'));
    }

    public function store(Request $request)
    {
        // Si es cliente, forzar su propietario_id
        if (auth()->user()->hasRole('cliente')) {
            $propietario = Propietario::where('user_id', auth()->id())->first();
            if (!$propietario) {
                return redirect()->route('dashboard')
                    ->with('error', 'No tienes un perfil de propietario');
            }
            $request->merge(['propietario_id' => $propietario->id]);
        }

        $validated = $request->validate([
            'propietario_id' => 'required|exists:propietarios,id',
            'nombre' => 'required|string|max:100',
            'especie' => 'required|in:perro,gato,ave,conejo,reptil,otro',
            'raza' => 'nullable|string|max:100',
            'sexo' => 'nullable|in:macho,hembra',
            'color' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ], [
            'propietario_id.required' => 'Debe seleccionar un propietario',
            'nombre.required' => 'El nombre es obligatorio',
            'especie.required' => 'Debe seleccionar una especie',
            'foto.image' => 'El archivo debe ser una imagen',
            'foto.max' => 'La imagen no debe superar 2MB',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
        ]);

        // Forzar activo = true
        $validated['activo'] = true;

        // Procesar imagen si existe
        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->uploadImage($request->file('foto'));
        }

        $mascota = Mascota::create($validated);
        if (auth()->user()->hasRole('cliente')) {
            return redirect()->route('cliente.mascotas')
                ->with('success', "Mascota {$mascota->nombre} registrada exitosamente");
        }

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota {$mascota->nombre} registrada exitosamente");
    }

    public function show(Mascota $mascota)
    {
        $mascota->load(['propietario', 'citas.veterinario', 'tratamientos']);
        return view('mascota.show', compact('mascota'));
    }

    public function edit(Mascota $mascota)
    {
        // Si es cliente, verificar que sea su mascota
        if (auth()->user()->hasRole('cliente')) {
            $propietario = Propietario::where('user_id', auth()->id())->first();
            if (!$propietario || $mascota->propietario_id !== $propietario->id) {
                abort(403, 'No tienes permiso para editar esta mascota');
            }
        }
        
        $propietarios = auth()->user()->hasRole('cliente') 
            ? collect([$mascota->propietario]) 
            : Propietario::orderBy('nombre')->get();
            
        return view('mascota.edit', compact('mascota', 'propietarios'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        // Si es cliente, verificar que sea su mascota y forzar propietario_id
        if (auth()->user()->hasRole('cliente')) {
            $propietario = Propietario::where('user_id', auth()->id())->first();
            if (!$propietario || $mascota->propietario_id !== $propietario->id) {
                abort(403, 'No tienes permiso para editar esta mascota');
            }
            $request->merge(['propietario_id' => $propietario->id]);
        }

        $validated = $request->validate([
            'propietario_id' => 'required|exists:propietarios,id',
            'nombre' => 'required|string|max:100',
            'especie' => 'required|in:perro,gato,ave,conejo,reptil,otro',
            'raza' => 'nullable|string|max:100',
            'sexo' => 'nullable|in:macho,hembra',
            'color' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        // Procesar nueva imagen si existe
        if ($request->hasFile('foto')) {
            // Eliminar imagen anterior
            if ($mascota->foto) {
                Storage::disk('public')->delete($mascota->foto);
            }
            $validated['foto'] = $this->uploadImage($request->file('foto'));
        }

        $mascota->update($validated);
        if (auth()->user()->hasRole('cliente')) {
            return redirect()->route('cliente.mascotas')
                ->with('success', "Mascota {$mascota->nombre} actualizada exitosamente");
        }

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota {$mascota->nombre} actualizada exitosamente");
    }

    public function destroy(Mascota $mascota)
    {
        $nombre = $mascota->nombre;
        
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        $mascota->delete();

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota {$nombre} eliminada exitosamente");
    }

    public function misMascotas()
    {
        $propietario = Propietario::where('user_id', auth()->id())->first();
        
        if (!$propietario) {
            return redirect()->route('dashboard')
                ->with('info', 'Debes completar tu perfil de propietario primero');
        }

        $mascotas = Mascota::where('propietario_id', $propietario->id)
            ->where('activo', true)
            ->get();

        return view('cliente.mascotas', compact('propietario', 'mascotas'));
    }

 
    private function uploadImage($file)
    {
        // Generar nombre único
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'mascotas/' . $filename;

        // Intentar procesar la imagen con Intervention Image (GD/Imagick)
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            // Redimensionar manteniendo proporción (máximo 800px)
            // Use scale if available, otherwise fallback to resize
            if (method_exists($image, 'scale')) {
                $image->scale(width: 800);
            } else {
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // Guardar en storage
            Storage::disk('public')->put($path, (string) $image->encode());
            return $path;
        } catch (\Throwable $e) {
            // Si falla (por ejemplo GD no instalado), guardar el archivo original sin procesar
            // Esto evita un error 500 en producción mientras el sistema se configura
            try {
                $stored = $file->storeAs('mascotas', $filename, 'public');
                return $stored;
            } catch (\Throwable $ex) {
                // Si tampoco se puede guardar, relanzar la excepción original para diagnóstico
                throw $e;
            }
        }
    }
}