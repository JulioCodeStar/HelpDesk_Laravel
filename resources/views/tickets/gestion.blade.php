@extends('layouts.app')

@section('title', 'Gestionar tickets')

@push('styles')
<style>
    .lw-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(89, 85, 209, .12);
        color: var(--bs-primary);
        font-size: .65rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        letter-spacing: .02em;
        user-select: none;
    }
    .table-warning-subtle { background-color: rgba(var(--bs-warning-rgb), .06); }
</style>
@endpush

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',  'url' => route('dashboard'),     'icon' => 'fi fi-rr-home'],
                ['label' => 'Tickets', 'url' => route('tickets.index'), 'icon' => 'fi fi-rr-ticket'],
                ['label' => 'Gestionar'],
            ]"/>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden p-0">
                <livewire:gestion-table title="Gestión de tickets" />
            </div>
        </div>
    </div>

    {{-- ===== Modal de gestión (AJAX independiente de Livewire) ===== --}}
    <div class="modal fade" id="gestionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Ticket <span id="m_id" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form id="gestionForm" method="POST" style="display:contents;">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <div id="m_loading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="text-muted mt-2 mb-0">Cargando detalle...</p>
                        </div>

                        <div id="m_content" class="d-none">

                            <h6 class="mb-1" id="m_subject"></h6>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-subtle-secondary text-secondary" id="m_category"></span>
                                <span class="badge" id="m_status"></span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Solicitante</small>
                                    <span id="m_creator"></span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Correo</small>
                                    <span id="m_email"></span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Creado</small>
                                    <span id="m_created"></span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Cerrado</small>
                                    <span id="m_closed"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Descripción</small>
                                <div class="border rounded p-3 bg-body-tertiary" id="m_description"></div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Archivos adjuntos</small>
                                <div id="m_attachments" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <hr>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="assigned_to" class="form-label">Asignar a</label>
                                    <select class="form-select" id="assigned_to" name="assigned_to">
                                        <option value="">— Sin asignar —</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="priority_id" class="form-label">
                                        Prioridad <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="priority_id" name="priority_id" required>
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light waves-effect" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" id="m_submit" disabled>
                            <span id="m_submit_icon"><i class="fi fi-rr-disk me-1"></i></span>
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="m_submit_spinner"></span>
                            Guardar cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
<script>
    const gestionModal = new bootstrap.Modal(document.getElementById('gestionModal'));

    document.getElementById('gestionForm').addEventListener('submit', function () {
        const btn     = document.getElementById('m_submit');
        const icon    = document.getElementById('m_submit_icon');
        const spinner = document.getElementById('m_submit_spinner');
        btn.disabled = true;
        icon.classList.add('d-none');
        spinner.classList.remove('d-none');
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-gestionar');
        if (!btn) return;

        document.getElementById('m_loading').classList.remove('d-none');
        document.getElementById('m_content').classList.add('d-none');
        document.getElementById('m_submit').disabled = true;
        document.getElementById('gestionForm').action = btn.dataset.action;

        gestionModal.show();

        fetch(btn.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => {
                if (!res.ok) throw new Error('Error al cargar');
                return res.json();
            })
            .then(t => {
                document.getElementById('m_id').textContent       = '#' + t.id;
                document.getElementById('m_subject').textContent  = t.subject;
                document.getElementById('m_creator').textContent  = t.creator;
                document.getElementById('m_email').textContent    = t.email;
                document.getElementById('m_created').textContent  = t.created_at;
                document.getElementById('m_closed').textContent   = t.closed_at ?? '—';
                document.getElementById('m_category').textContent = t.category;

                const status = document.getElementById('m_status');
                status.textContent = t.status;
                status.style.backgroundColor = t.status_color + '1a';
                status.style.color = t.status_color;

                document.getElementById('m_description').innerHTML = t.description;

                const cont = document.getElementById('m_attachments');
                if (t.attachments.length === 0) {
                    cont.innerHTML = '<span class="text-muted small">Sin archivos adjuntos.</span>';
                } else {
                    cont.innerHTML = t.attachments.map(a => `
                        <a href="${a.url}" target="_blank"
                           class="btn btn-sm btn-outline-light waves-effect d-inline-flex align-items-center">
                            <i class="fi fi-rr-file me-1"></i> ${a.name}
                            <span class="badge bg-subtle-secondary text-secondary ms-2">${a.type}</span>
                        </a>
                    `).join('');
                }

                document.getElementById('assigned_to').value = t.assigned_to ?? '';
                document.getElementById('priority_id').value = t.priority_id;

                document.getElementById('m_loading').classList.add('d-none');
                document.getElementById('m_content').classList.remove('d-none');
                document.getElementById('m_submit').disabled = false;
            })
            .catch(() => {
                gestionModal.hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar el detalle del ticket.',
                    confirmButtonColor: '#5955D1',
                });
            });
    });
</script>
@endpush
