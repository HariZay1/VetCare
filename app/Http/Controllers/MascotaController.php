<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MascotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|recepcion')->except(['index', 'show', 'create', 'store', 'edit', 'update', 'misMascotas']);
    }

    public function index(Request $request)
    {
        $query = Mascota::with('propietario');
        
        // SI ES CLIENTE, solo mostrar sus mascotas
        if (auth()->user()->hasRole('cliente')) {
            $propietario = Propietario::where('user_id', auth()->id())->first();
            if ($propietario) {
                $query->where('propietario_id', $propietario->id);
            }
        }

        // Búsqueda (solo para admin/recepcion)
        if ($request->filled('search') && !auth()->user()->hasRole('cliente')) {
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

        // Filtro por especie (solo para admin/recepcion)
        if ($request->filled('especie') && !auth()->user()->hasRole('cliente')) {
            $query->where('especie', $request->especie);
        }

        // Filtro por estado (solo para admin/recepcion)
        if ($request->filled('activo') && !auth()->user()->hasRole('cliente')) {
            $query->where('activo', $request->activo === '1');
        }

        $mascotas = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Solo pasar propietarios si es admin/recepcion
        $propietarios = auth()->user()->hasRole('cliente') ? collect() : Propietario::orderBy('nombre')->get();

        return view('mascota.index', compact('mascotas', 'propietarios'));
    }
    public function create()
    {
        $propietarios = Propietario::orderBy('nombre')->get();
        return view('mascota.create', compact('propietarios'));
    }

    public function store(Request $request)
    {
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

        // Procesar imagen si existe
        if ($request->hasFile('foto')) {
            $validated['foto'] = $this->uploadImage($request->file('foto'));
        }

        $mascota = Mascota::create($validated);

        return redirect()->route('mascota.index')
            ->with('success', "Mascota {$mascota->nombre} registrada exitosamente");
    }

    public function show(Mascota $mascota)
    {
        $mascota->load(['propietario', 'citas.veterinario', 'tratamientos']);
        return view('mascota.show', compact('mascota'));
    }

    public function edit(Mascota $mascota)
    {
        $propietarios = Propietario::orderBy('nombre')->get();
        return view('mascota.edit', compact('mascota', 'propietarios'));
    }

    public function update(Request $request, Mascota $mascota)
    {
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

        return redirect()->route('mascotas.index')
            ->with('success', "Mascota {$mascota->nombre} actualizada exitosamente");
    }

    public function destroy(Mascota $mascota)
    {
        $nombre = $mascota->nombre;
        
        // Eliminar imagen si existe
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        $mascota->delete();

        return redirect()->route('mascota.index')
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
            ->paginate(12); // ← CAMBIA get() por paginate()

        return view('mascota.index', compact('propietario', 'mascotas'));
    }
    /**
     * Procesar y guardar imagen con redimensión
     */
    private function uploadImage($file)
    {
        // Generar nombre único
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'mascotas/' . $filename;

        // Crear manager de Intervention Image
        $manager = new ImageManager(new Driver());
        
        // Leer y procesar imagen
        $image = $manager->read($file);
        
        // Redimensionar manteniendo proporción (máximo 800px)
        $image->scale(width: 800);
        
        // Guardar en storage
        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }
}