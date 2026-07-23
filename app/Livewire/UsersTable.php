<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class UsersTable extends BaseTable
{
    protected function baseQuery(): Builder
    {
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.role',
                'users.created_at',
                'd.name as department_name',
            ])
            ->leftJoin('departments as d', 'users.department_id', '=', 'd.id');
    }

    protected function applySearch($query, string $search): void
    {
        $query->where('users.name',   'like', "%{$search}%")
              ->orWhere('users.email', 'like', "%{$search}%")
              ->orWhere('d.name',      'like', "%{$search}%")
              ->orWhere('users.role',  'like', "%{$search}%");
    }

    public function defaultSortCol(): string
    {
        return 'users.created_at';
    }

    public function columns(): array
    {
        return [
            ['label' => 'Nombre',       'col' => 'users.name',       'sortable' => true,  'style' => 'min-width:220px'],
            ['label' => 'Correo',       'col' => 'users.email',      'sortable' => true,  'style' => 'min-width:200px'],
            ['label' => 'Departamento', 'col' => null,               'sortable' => false, 'style' => 'min-width:160px'],
            ['label' => 'Rol',          'col' => 'users.role',       'sortable' => true],
            ['label' => 'Registrado',   'col' => 'users.created_at', 'sortable' => true],
            ['label' => 'Acciones',     'col' => null,               'sortable' => false, 'class' => 'text-end'],
        ];
    }

    public function rowView(): string
    {
        return 'livewire.partials.users-row';
    }

    #[On('delete-record')]
    public function deleteRecord(int $id): void
    {
        if ($id === Auth::id()) {
            $this->notify('warning', 'No puedes eliminar tu propio usuario.');
            return;
        }

        $user = User::find($id);

        if (! $user) {
            $this->notify('error', 'Usuario no encontrado.');
            return;
        }

        if ($user->tickets()->exists() || $user->assignedTickets()->exists()) {
            $this->notify('warning', "No se puede eliminar «{$user->name}» porque tiene tickets asociados.");
            return;
        }

        $nombre = $user->name;
        $user->delete();
        $this->notify('success', "El usuario «{$nombre}» se eliminó correctamente.");
    }
}
