<?php

namespace App\Livewire;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class DepartmentsTable extends BaseTable
{
    protected function baseQuery(): Builder
    {
        return Department::query()
            ->select([
                'departments.id',
                'departments.name',
                'departments.created_at',
                DB::raw('(SELECT COUNT(*) FROM users WHERE users.department_id = departments.id) as users_count'),
            ]);
    }

    protected function applySearch($query, string $search): void
    {
        $query->where('departments.name', 'like', "%{$search}%");
    }

    public function defaultSortCol(): string
    {
        return 'departments.created_at';
    }

    public function columns(): array
    {
        return [
            ['label' => '#',        'col' => 'departments.id',         'sortable' => true],
            ['label' => 'Nombre',   'col' => 'departments.name',       'sortable' => true,  'style' => 'min-width:220px'],
            ['label' => 'Usuarios', 'col' => null,                     'sortable' => false],
            ['label' => 'Creado',   'col' => 'departments.created_at', 'sortable' => true],
            ['label' => 'Acciones', 'col' => null,                     'sortable' => false, 'class' => 'text-end'],
        ];
    }

    public function rowView(): string
    {
        return 'livewire.partials.departments-row';
    }

    #[On('delete-record')]
    public function deleteRecord(int $id): void
    {
        $department = Department::find($id);

        if (! $department) {
            $this->notify('error', 'Departamento no encontrado.');
            return;
        }

        if ($department->users()->exists()) {
            $this->notify('warning', "No se puede eliminar «{$department->name}» porque tiene usuarios asignados.");
            return;
        }

        try {
            $nombre = $department->name;
            $department->delete();
            $this->notify('success', "El departamento «{$nombre}» se eliminó correctamente.");
        } catch (QueryException) {
            $this->notify('error', 'No se pudo eliminar el departamento.');
        }
    }
}
