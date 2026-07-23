<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Livewire\Attributes\On;

class CategoriesTable extends BaseTable
{
    protected function baseQuery(): Builder
    {
        return Category::query()
            ->select(['id', 'name', 'description', 'created_at']);
    }

    protected function applySearch($query, string $search): void
    {
        $query->where('name',        'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
    }

    public function defaultSortCol(): string
    {
        return 'created_at';
    }

    public function columns(): array
    {
        return [
            ['label' => '#',           'col' => 'id',          'sortable' => true],
            ['label' => 'Nombre',      'col' => 'name',        'sortable' => true,  'style' => 'min-width:200px'],
            ['label' => 'Descripción', 'col' => null,          'sortable' => false, 'style' => 'min-width:280px'],
            ['label' => 'Creado',      'col' => 'created_at',  'sortable' => true],
            ['label' => 'Acciones',    'col' => null,          'sortable' => false, 'class' => 'text-end'],
        ];
    }

    public function rowView(): string
    {
        return 'livewire.partials.categories-row';
    }

    #[On('delete-record')]
    public function deleteRecord(int $id): void
    {
        $category = Category::find($id);

        if (! $category) {
            $this->notify('error', 'Categoría no encontrada.');
            return;
        }

        try {
            $nombre = $category->name;
            $category->delete();
            $this->notify('success', "La categoría «{$nombre}» se eliminó correctamente.");
        } catch (QueryException) {
            $this->notify('error', 'No se puede eliminar: la categoría tiene tickets asociados.');
        }
    }
}
