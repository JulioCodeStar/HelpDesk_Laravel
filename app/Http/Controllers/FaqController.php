<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    /**
     * Muestra el listado de preguntas frecuentes.
     */
    public function index()
    {
        return view('faqs.index');
    }

    /**
     * Muestra el formulario para registrar una nueva pregunta frecuente.
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * Valida y guarda una nueva pregunta frecuente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:faqs,title',
            'description' => 'required|string|max:5000',
        ], [
            'title.required' => 'La pregunta es obligatoria.',
            'title.unique' => 'Ya existe una pregunta frecuente con ese título.',
            'title.max' => 'La pregunta no puede superar los 255 caracteres.',
            'description.required' => 'La respuesta es obligatoria.',
            'description.max' => 'La respuesta no puede superar los 5000 caracteres.',
        ]);

        try {
            $faq = Faq::create($validated);

            return redirect()
                ->route('faqs.index')
                ->with('success', "La pregunta «{$faq->title}» se creó correctamente.");
        } catch (\Exception $e) {
            Log::error('Error en FaqController@store: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la pregunta frecuente. Inténtalo nuevamente.');
        }
    }

    /**
     * Muestra el formulario de edición de una pregunta frecuente.
     */
    public function edit(Faq $faq)
    {
        try {
            return view('faqs.edit', compact('faq'));
        } catch (\Exception $e) {
            Log::error('Error en FaqController@edit: ' . $e->getMessage());
            return redirect()->route('faqs.index')->with('error', 'Error al cargar la pregunta frecuente.');
        }
    }

    /**
     * Valida y actualiza una pregunta frecuente existente.
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:faqs,title,' . $faq->id,
            'description' => 'required|string|max:5000',
        ], [
            'title.required' => 'La pregunta es obligatoria.',
            'title.unique' => 'Ya existe una pregunta frecuente con ese título.',
            'title.max' => 'La pregunta no puede superar los 255 caracteres.',
            'description.required' => 'La respuesta es obligatoria.',
            'description.max' => 'La respuesta no puede superar los 5000 caracteres.',
        ]);

        try {
            $faq->fill($validated);

            if (!$faq->isDirty()) {
                return redirect()
                    ->route('faqs.index')
                    ->with('info', 'No se realizaron cambios en la pregunta frecuente.');
            }

            $faq->save();

            return redirect()
                ->route('faqs.index')
                ->with('success', "La pregunta «{$faq->title}» se actualizó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en FaqController@update: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la pregunta frecuente. Inténtalo nuevamente.');
        }
    }

    /**
     * Elimina una pregunta frecuente.
     * La tabla faqs no tiene relaciones dependientes, por lo que no requiere
     * validaciones previas como en categories o departments.
     */
    public function destroy(Faq $faq)
    {
        try {
            $titulo = $faq->title;
            $faq->delete();

            return redirect()
                ->route('faqs.index')
                ->with('success', "La pregunta «{$titulo}» se eliminó correctamente.");
        } catch (QueryException $e) {
            Log::error('Error en FaqController@destroy: ' . $e->getMessage());
            return redirect()
                ->route('faqs.index')
                ->with('error', 'No se pudo eliminar la pregunta frecuente.');
        }
    }
}
