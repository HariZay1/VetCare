<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Propietario;
use App\Models\Veterinario;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles([$validated['role']]);

        // Crear perfil asociado según rol si no existe
        $this->ensureProfileForRole($user, $validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario {$user->name} creado y rol asignado.");
    }

    public function updateRole(Request $request, User $user)
    {
        // Legacy single-field update which we keep for compatibility
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $role = $request->input('role');
        $user->syncRoles([$role]);

        // Crear perfil asociado si se asignó un rol que requiere perfil
        $this->ensureProfileForRole($user, $role);

        return redirect()->route('admin.users.index')
            ->with('success', "Rol de {$user->name} actualizado a {$role}.");
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function show(User $user)
    {
        $propietario = Propietario::where('user_id', $user->id)->first();
        $veterinario = Veterinario::where('user_id', $user->id)->first();
        return view('admin.users.show', compact('user', 'propietario', 'veterinario'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $user->syncRoles([$validated['role']]);

        // Crear perfil asociado si se asignó un rol que requiere perfil
        $this->ensureProfileForRole($user, $validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario {$user->name} actualizado correctamente.");
    }

    // Métodos auxiliares
    /**
     * Ensure there is a domain profile for the given role (propietario/veterinario)
     */
    protected function ensureProfileForRole(User $user, string $role)
    {
        if ($role === 'cliente') {
            // Crear Propietario si no existe
            if (!Propietario::where('user_id', $user->id)->exists()) {
                [$first, $rest] = $this->splitName($user->name);
                Propietario::create([
                    'user_id' => $user->id,
                    'nombre' => $first,
                    'apellido' => $rest,
                    'email' => $user->email,
                ]);
            }
        }

        if ($role === 'veterinario') {
            if (!Veterinario::where('user_id', $user->id)->exists()) {
                [$first, $rest] = $this->splitName($user->name);
                Veterinario::create([
                    'user_id' => $user->id,
                    'nombre' => $first,
                    'apellido' => $rest,
                    'activo' => true,
                ]);
            }
        }
    }

    protected function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName));
        $first = $parts[0] ?? $fullName;
        $rest = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
        return [$first, $rest];
    }
}
