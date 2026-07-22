<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::latest()
                ->get()->sortBy('id');
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las categorias');
        }
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique'   => 'Ya existe una categoría con ese nombre.',
        ]);

        try {
            Category::create($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría creada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la categoría.');
        }
    }

    public function edit(Category $category)
    {
        try {
            return view('categories.edit', compact('category'));
        } catch (\Exception $e) {
            Log::error('Error en CategoryController@edit: ' . $e->getMessage());
            return redirect()->route('categories.index')->with('error', 'Error al cargar la categoría.');
        }

    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.unique'   => 'Ya existe una categoría con ese nombre.',
        ]);

        try {
            $category->update($validated);

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría actualizada correctamente.');
        } catch (QueryException $e) {
            Log::error('Error en CategoryController@update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la categoría.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()
                ->route('categories.index')
                ->with('success', 'Categoría eliminada correctamente.');
        } catch (QueryException $e) {
            Log::error('Error en CategoryController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('categories.index')
                ->with('error', 'No se puede eliminar: la categoría tiene tickets asociados.');
        }
    }
}
