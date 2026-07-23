<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('departments.index');
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe un departamento con ese nombre.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
        ]);

        try {
            $department = Department::create($validated);

            return redirect()
                ->route('departments.index')
                ->with('success', "El departamento «{$department->name}» se creó correctamente.");
        } catch (\Exception $e) {
            Log::error('Error en DepartmentController@store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar el departamento. Inténtalo nuevamente.');
        }
    }

    public function edit(Department $department)
    {
        try {
            return view('departments.edit', compact('department'));
        } catch (\Exception $e) {
            Log::error('Error en DepartmentController@edit: ' . $e->getMessage());
            return redirect()->route('departments.index')->with('error', 'Error al cargar el departamento.');
        }

    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'Ya existe un departamento con ese nombre.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
        ]);

        try {
            $department->fill($validated);

            if (!$department->isDirty()) {
                return redirect()
                    ->route('departments.index')
                    ->with('info', 'No se realizaron cambios en el departamento.');
            }

            $department->save();

            return redirect()
                ->route('departments.index')
                ->with('success', "El departamento «{$department->name}» se actualizó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en DepartmentController@update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el departamento. Inténtalo nuevamente.');
        }
    }

    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with('warning', "No se puede eliminar «{$department->name}» porque tiene usuarios asignados.");
        }

        try {
            $nombre = $department->name;
            $department->delete();

            return redirect()
                ->route('departments.index')
                ->with('success', "El departamento «{$nombre}» se eliminó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en DepartmentController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('departments.index')
                ->with('error', 'No se pudo eliminar el departamento.');
        }
    }
}
