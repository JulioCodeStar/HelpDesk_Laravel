@push('scripts')
<script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
<script>
    /* Confirmación de borrado → despacha evento Livewire */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;
        e.preventDefault();
        Swal.fire({
            title: '¿Eliminar registro?',
            html: `Se eliminará <strong>${btn.dataset.name}</strong>. Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then(r => {
            if (r.isConfirmed)
                Livewire.dispatch('delete-record', { id: parseInt(btn.dataset.id) });
        });
    });
    /* Toast de respuesta desde Livewire */
    window.addEventListener('swal', e => {
        Swal.fire({
            icon: e.detail.type,
            text: e.detail.message,
            timer: 2500,
            showConfirmButton: false,
            position: 'top-end',
            toast: true,
        });
    });
</script>
@endpush

<div>
    {{-- ── Cabecera: título + búsqueda + filas por página ── --}}
    <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <h6 class="card-title mb-0">
            {{ $title ?? 'Listado' }}
            <span class="badge bg-subtle-primary text-primary ms-1">
                {{ number_format($rows->total()) }}
            </span>
        </h6>

        <div class="d-flex align-items-center gap-2">
            {{-- Buscador --}}
            <div class="input-group input-group-sm" style="width:230px;">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="fi fi-rr-search text-muted" style="font-size:.8rem;"></i>
                </span>
                <input type="search"
                       class="form-control form-control-sm border-start-0"
                       placeholder="Buscar..."
                       wire:model.live.debounce.350ms="search">
            </div>
            {{-- Filas por página --}}
            <select class="form-select form-select-sm" wire:model.live="perPage" style="width:auto;">
                <option value="10">10 / pág.</option>
                <option value="25">25 / pág.</option>
                <option value="50">50 / pág.</option>
                <option value="100">100 / pág.</option>
            </select>
        </div>
    </div>

    {{-- ── Tabla ── --}}
    <div class="card-body p-0">

        {{-- Barra de carga --}}
        <div wire:loading.flex
             style="display:none; background:rgba(var(--bs-primary-rgb),.06); font-size:.8rem; color:var(--bs-primary);"
             class="align-items-center justify-content-center gap-2 py-2 border-bottom">
            <span class="spinner-border spinner-border-sm"></span>
            Cargando…
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                {{-- Cabecera dinámica --}}
                <thead class="table-light">
                    <tr>
                        @foreach ($columns as $col)
                            @if ($col['sortable'] && $col['col'])
                                <th wire:click="sort('{{ $col['col'] }}')"
                                    class="{{ $col['class'] ?? '' }}"
                                    style="cursor:pointer; white-space:nowrap; {{ $col['style'] ?? '' }}">
                                    {{ $col['label'] }}
                                    @if ($sortCol === $col['col'])
                                        <i class="fi fi-rr-angle-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"
                                           style="font-size:.7rem;"></i>
                                    @else
                                        <span class="ms-1 opacity-25" style="font-size:.75rem;">⇅</span>
                                    @endif
                                </th>
                            @else
                                <th class="{{ $col['class'] ?? '' }}"
                                    style="{{ $col['style'] ?? '' }}">
                                    {{ $col['label'] }}
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>

                {{-- Filas: delegadas al partial de cada tabla --}}
                <tbody>
                    @forelse ($rows as $row)
                        @include($rowView, ['row' => $row])
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="text-center py-5 text-muted">
                                <i class="fi fi-rr-search d-block mb-2" style="font-size:2rem; opacity:.3;"></i>
                                <span style="font-size:.875rem;">
                                    {{ $search ? 'Sin resultados para "' . $search . '"' : 'No hay registros.' }}
                                </span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- ── Paginación ── --}}
        @if ($rows->hasPages())
            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top flex-wrap gap-2">
                <small class="text-muted">
                    Mostrando
                    <strong>{{ $rows->firstItem() }}</strong> –
                    <strong>{{ $rows->lastItem() }}</strong>
                    de <strong>{{ number_format($rows->total()) }}</strong>
                </small>

                <ul class="pagination pagination-sm mb-0">
                    {{-- Anterior --}}
                    <li class="page-item {{ $rows->onFirstPage() ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="previousPage" wire:loading.attr="disabled">
                            <i class="fi fi-rr-angle-left"></i>
                        </button>
                    </li>

                    {{-- Números de página (ventana ±2) --}}
                    @foreach (range(max(1, $rows->currentPage() - 2), min($rows->lastPage(), $rows->currentPage() + 2)) as $page)
                        <li class="page-item {{ $page === $rows->currentPage() ? 'active' : '' }}">
                            <button class="page-link" wire:click="gotoPage({{ $page }})">
                                {{ $page }}
                            </button>
                        </li>
                    @endforeach

                    {{-- Siguiente --}}
                    <li class="page-item {{ ! $rows->hasMorePages() ? 'disabled' : '' }}">
                        <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled">
                            <i class="fi fi-rr-angle-right"></i>
                        </button>
                    </li>
                </ul>
            </div>
        @endif

    </div>
</div>
