<?php

namespace App\Livewire;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TicketsTable extends BaseTable
{
    // ── Query base ─────────────────────────────────────────────────

    protected function baseQuery(): Builder
    {
        $query = Ticket::query()
            ->select([
                'tickets.id',
                'tickets.subject',
                'tickets.created_at',
                'u.name  as creator_name',
                'a.name  as agent_name',
                'c.name  as category_name',
                's.name  as status_name',
                's.color as status_color',
                'p.name  as priority_name',
            ])
            ->leftJoin('users as u',      'tickets.user_id',     '=', 'u.id')
            ->leftJoin('users as a',      'tickets.assigned_to', '=', 'a.id')
            ->leftJoin('categories as c', 'tickets.category_id', '=', 'c.id')
            ->leftJoin('status as s',   'tickets.status_id',   '=', 's.id')
            ->leftJoin('priority as p', 'tickets.priority_id', '=', 'p.id');

        // Los clientes solo ven sus propios tickets
        if (Auth::user()->role === 'cliente') {
            $query->where('tickets.user_id', Auth::id());
        }

        return $query;
    }

    // ── Búsqueda ───────────────────────────────────────────────────

    protected function applySearch($query, string $search): void
    {
        $query->where('tickets.id',       'like', "%{$search}%")
              ->orWhere('tickets.subject', 'like', "%{$search}%")
              ->orWhere('u.name',          'like', "%{$search}%")
              ->orWhere('c.name',          'like', "%{$search}%")
              ->orWhere('s.name',          'like', "%{$search}%")
              ->orWhere('p.name',          'like', "%{$search}%");
    }

    // ── Configuración ──────────────────────────────────────────────

    public function defaultSortCol(): string
    {
        return 'tickets.created_at';
    }

    public function columns(): array
    {
        return [
            ['label' => '#',          'col' => 'tickets.id',         'sortable' => true],
            ['label' => 'Asunto',     'col' => 'tickets.subject',    'sortable' => true,  'style' => 'min-width:280px'],
            ['label' => 'Solicitante','col' => null,                 'sortable' => false, 'style' => 'min-width:180px'],
            ['label' => 'Asignado a', 'col' => null,                 'sortable' => false, 'style' => 'min-width:160px'],
            ['label' => 'Categoría',  'col' => null,                 'sortable' => false],
            ['label' => 'Prioridad',  'col' => null,                 'sortable' => false],
            ['label' => 'Estado',     'col' => null,                 'sortable' => false],
            ['label' => 'Creado',     'col' => 'tickets.created_at', 'sortable' => true],
            ['label' => 'Acciones',   'col' => null,                 'sortable' => false, 'class' => 'text-end'],
        ];
    }

    public function rowView(): string
    {
        return 'livewire.partials.tickets-row';
    }
}
