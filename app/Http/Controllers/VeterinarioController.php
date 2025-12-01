<?php

namespace App\Http\Controllers;

use App\Models\Veterinario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class VeterinarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Veterinario::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('especialidad', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $veterinarios = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('veterinario.index', compact('veterinarios'));
    }

    public function create()
    {
        $usuariosDisponibles = User::role('veterinario')
            ->whereDoesntHave('veterinario')
            ->get();

        return view('veterinario.create', compact('usuariosDisponibles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'horario' => 'nullable|string',
            'activo' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
            'crear_usuario' => 'nullable|boolean',
            'email' => 'required_if:crear_usuario,1|nullable|email|unique:users,email',
            'password' => 'required_if:crear_usuario,1|nullable|min:8|confirmed',
        ], [
            'email.required_if' => 'El email es requerido si deseas crear usuario',
            'email.unique' => 'Ya existe un usuario con este email',
            'password.required_if' => 'La contraseña es requerida si deseas crear usuario',
        ]);

        $userId = $request->user_id;
        if ($request->crear_usuario) {
            $user = User::create([
                'name' => $validated['nombre'] . ' ' . $validated['apellido'],
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('veterinario');
            $userId = $user->id;
        }

        $validated['user_id'] = $userId;
        unset($validated['crear_usuario'], $validated['email'], $validated['password'], $validated['password_confirmation']);

        $veterinario = Veterinario::create($validated);

        return redirect()->route('veterinarios.index')
            ->with('success', "Veterinario {$veterinario->nombre_completo} registrado exitosamente");
    }

    public function show(Veterinario $veterinario)
    {
        $veterinario->load(['user', 'citas.mascota.propietario']);
        
        $citasHoy = $veterinario->citas()->whereDate('fecha_hora', today())->count();
        $citasSemana = $veterinario->citas()
            ->whereBetween('fecha_hora', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $citasCompletadas = $veterinario->citas()->where('estado', 'completada')->count();
        $proximasCitas = $veterinario->citas()
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc')
            ->take(5)
            ->get();

        return view('veterinario.show', compact(
            'veterinario', 
            'citasHoy', 
            'citasSemana', 
            'citasCompletadas',
            'proximasCitas'
        ));
    }

    public function edit(Veterinario $veterinario)
    {
        return view('veterinario.edit', compact('veterinario'));
    }

    public function update(Request $request, Veterinario $veterinario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:100',
            'telefono' => 'required|string|max:20',
            'horario' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $veterinario->update($validated);

        return redirect()->route('veterinarios.index')
            ->with('success', "Veterinario {$veterinario->nombre_completo} actualizado exitosamente");
    }

    public function destroy(Veterinario $veterinario)
    {
        $nombre = $veterinario->nombre_completo;
        
        if ($veterinario->citas()->count() > 0) {
            return redirect()->route('veterinarios.index')
                ->with('error', "No se puede eliminar a {$nombre} porque tiene citas asociadas");
        }

        $veterinario->delete();

        return redirect()->route('veterinarios.index')
            ->with('success', "Veterinario {$nombre} eliminado exitosamente");
    }
}