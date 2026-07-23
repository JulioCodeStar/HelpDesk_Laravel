<?php

namespace App\Livewire;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class FaqsTable extends BaseTable
{
    protected function baseQuery(): Builder
    {
        return Faq::query()
            ->select(['id', 'title', 'description', 'created_at']);
    }

    protected function applySearch($query, string $search): void
    {
        $query->where('title',       'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    public function defaultSortCol(): string
    {
        return 'created_at';
    }

    public function columns(): array
    {
        return [
            ['label' => '#',          'col' => 'id',         'sortable' => true],
            ['label' => 'Pregunta',   'col' => 'title',      'sortable' => true,  'style' => 'min-width:280px'],
            ['label' => 'Respuesta',  'col' => null,         'sortable' => false, 'style' => 'min-width:320px'],
            ['label' => 'Creado',     'col' => 'created_at', 'sortable' => true],
            ['label' => 'Acciones',   'col' => null,         'sortable' => false, 'class' => 'text-end'],
        ];
    }

    public function rowView(): string
    {
        return 'livewire.partials.faqs-row';
    }

    #[On('delete-record')]
    public function deleteRecord(int $id): void
    {
        $faq = Faq::find($id);

        if (! $faq) {
            $this->notify('error', 'Pregunta frecuente no encontrada.');
            return;
        }

        $titulo = $faq->title;
        $faq->delete();
        $this->notify('success', "La pregunta «{$titulo}» se eliminó correctamente.");
    }
}
