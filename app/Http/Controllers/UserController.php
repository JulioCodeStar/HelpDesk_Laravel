<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra el listado de usuarios con su departamento.
     * Se usa with('department') para evitar el problema N+1.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Muestra el formulario de creación.
     * Se envían los departamentos para poblar el select.
     */
    public function create()
    {
        try {
            $departments = Department::orderBy('name')->get();
            return view('users.create', compact('departments'));
        } catch (\Exception $e) {
            Log::error('Error en UserController@create: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Valida y registra un nuevo usuario.
     * La contraseña se hashea antes de guardarse.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'role' => ['required', Rule::in(['cliente', 'agente', 'admin'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'department_id.exists' => 'El departamento seleccionado no es válido.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        try {
            // Se hashea la contraseña antes de crear el registro
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            return redirect()
                ->route('users.index')
                ->with('success', "El usuario «{$user->name}» se creó correctamente.");
        } catch (\Exception $e) {
            Log::error('Error en UserController@store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el usuario. Inténtalo nuevamente.');
        }
    }

    /**
     * Muestra el formulario de edición con los departamentos disponibles.
     */
    public function edit(User $user)
    {
        try {
            $departments = Department::orderBy('name')->get();
            return view('users.edit', compact('user', 'departments'));
        } catch (\Exception $e) {
            Log::error('Error en UserController@edit: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Error al cargar el usuario.');
        }
    }

    /**
     * Valida y actualiza un usuario existente.
     * La contraseña es opcional: solo se actualiza si se envía una nueva.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'role' => ['required', Rule::in(['cliente', 'agente', 'admin'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'department_id.exists' => 'El departamento seleccionado no es válido.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol seleccionado no es válido.',
        ]);

        try {
            // Si no se envió contraseña nueva, se descarta del arreglo
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->fill($validated);

            if (!$user->isDirty()) {
                return redirect()
                    ->route('users.index')
                    ->with('info', 'No se realizaron cambios en el usuario.');
            }

            $user->save();

            return redirect()
                ->route('users.index')
                ->with('success', "El usuario «{$user->name}» se actualizó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en UserController@update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el usuario. Inténtalo nuevamente.');
        }
    }

    /**
     * Elimina un usuario.
     * Se bloquea si el usuario tiene tickets asociados (creados o asignados)
     * o si intenta eliminarse a sí mismo.
     */
    public function destroy(User $user)
    {
        // Evita que el usuario autenticado se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('warning', 'No puedes eliminar tu propio usuario.');
        }

        // Bloquea el borrado si tiene tickets creados o asignados
        if ($user->tickets()->exists() || $user->assignedTickets()->exists()) {
            return redirect()
                ->route('users.index')
                ->with('warning', "No se puede eliminar «{$user->name}» porque tiene tickets asociados.");
        }

        try {
            $nombre = $user->name;
            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success', "El usuario «{$nombre}» se eliminó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en UserController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('users.index')
                ->with('error', 'No se pudo eliminar el usuario.');
        }
    }
}
