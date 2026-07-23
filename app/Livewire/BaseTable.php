<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseTable extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $sortCol = '';
    public string $sortDir = 'desc';
    public int    $perPage = 10;

    // ── Contrato que cada tabla hija debe implementar ──────────────

    /** Query base con JOINs y SELECTs (sin search ni order) */
    abstract protected function baseQuery(): \Illuminate\Database\Eloquent\Builder;

    /** Columna SQL por defecto para el ordenamiento inicial */
    abstract public function defaultSortCol(): string;

    /**
     * Definición de columnas para el <thead>.
     * Cada elemento:
     *   ['label' => 'Texto', 'col' => 'tabla.columna_sql', 'sortable' => true, 'class' => '']
     * 'col' => null significa que la columna no es ordenable.
     */
    abstract public function columns(): array;

    /** Vista blade que renderiza el <tr> de cada fila */
    abstract public function rowView(): string;

    // ── Lifecycle ──────────────────────────────────────────────────

    public function mount(): void
    {
        $this->sortCol = $this->defaultSortCol();
    }

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function sort(string $col): void
    {
        if ($this->sortCol === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortCol = $col;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    // ── Búsqueda (sobrescribir en la clase hija) ───────────────────

    /**
     * Aplica los filtros de búsqueda al query.
     * Se llama dentro de un where(closure), por lo que las condiciones
     * se agrupan automáticamente: AND (col1 LIKE ? OR col2 LIKE ? ...)
     */
    protected function applySearch($query, string $search): void {}

    // ── Datos paginados ────────────────────────────────────────────

    #[Computed]
    public function rows()
    {
        $search = $this->search;

        $query = $this->baseQuery();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $this->applySearch($q, $search);
            });
        }

        return $query
            ->orderBy($this->sortCol, $this->sortDir)
            ->paginate($this->perPage);
    }

    // ── Delete ─────────────────────────────────────────────────────

    /** Cada tabla hija implementa la lógica de borrado */
    #[On('delete-record')]
    public function deleteRecord(int $id): void {}

    protected function notify(string $type, string $message): void
    {
        $this->dispatch('swal', type: $type, message: $message);
    }

    // ── Render ─────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.base-table', [
            'rows'    => $this->rows,
            'columns' => $this->columns(),
            'rowView' => $this->rowView(),
        ]);
    }
}
