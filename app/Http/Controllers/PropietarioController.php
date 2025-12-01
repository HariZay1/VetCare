<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PropietarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|recepcion');
    }

    public function index(Request $request)
    {
        $query = Propietario::with(['mascotas', 'citas', 'user']);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('ci', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        $propietarios = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('propietario.index', compact('propietarios'));
    }

    public function create()
    {
        return view('propietario.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => 'required|string|max:20|unique:propietarios,ci',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:propietarios,email',
            'direccion' => 'nullable|string',
            'crear_usuario' => 'nullable|boolean',
            'password' => 'required_if:crear_usuario,1|nullable|min:8|confirmed',
        ], [
            'ci.unique' => 'Ya existe un propietario con este CI',
            'email.unique' => 'Ya existe un propietario con este email',
            'password.required_if' => 'La contraseña es requerida si deseas crear usuario',
        ]);

        // Crear usuario si se solicitó
        $userId = null;
        if ($request->crear_usuario) {
            $user = User::create([
                'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                'email' => $validated['email'],
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('cliente');
            $userId = $user->id;
        }

        $validated['user_id'] = $userId;
        unset($validated['crear_usuario'], $validated['password'], $validated['password_confirmation']);

        $propietario = Propietario::create($validated);

        return redirect()->route('propietarios.index')
            ->with('success', "Propietario {$propietario->nombre_completo} registrado exitosamente");
    }

    public function show(Propietario $propietario)
    {
        $propietario->load(['mascotas', 'citas.mascota']);

        return view('propietario.show', compact('propietario'));
    }

    public function edit(Propietario $propietario)
    {
    
        return view('propietario.edit', compact('propietario'));
    }

    public function update(Request $request, Propietario $propietario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'ci' => ['required', 'string', 'max:20', Rule::unique('propietarios')->ignore($propietario->id)],
            'telefono' => 'required|string|max:20',
            'email' => ['required', 'email', Rule::unique('propietarios')->ignore($propietario->id)],
            'direccion' => 'nullable|string',
        ]);

        $propietario->update($validated);

        return redirect()->route('propietarios.index')
            ->with('success', "Propietario {$propietario->nombre_completo} actualizado exitosamente");
    }

    public function destroy(Propietario $propietario)
    {
        $nombre = $propietario->nombre_completo;
        
        // Verificar si tiene mascotas asociadas
        if ($propietario->mascotas()->count() > 0) {
            return redirect()->route('propietarios.index')
                ->with('error', "No se puede eliminar {$nombre} porque tiene mascotas asociadas");
        }

        $propietario->delete();

        return redirect()->route('propietarios.index')
            ->with('success', "Propietario {$nombre} eliminado exitosamente");
    }
}