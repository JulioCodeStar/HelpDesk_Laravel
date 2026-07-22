@extends('layouts.app')

@section('title', 'Departamentos')

@section('content')
    {{-- Encabezado de página + breadcrumb --}}
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',        'url' => route('dashboard'),         'icon' => 'fi fi-rr-home'],
                ['label' => 'Mantenimiento', 'url' => '#',                        'icon' => 'fi fi-rr-settings'],
                ['label' => 'Departamentos'],
            ]"/>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary waves-effect waves-light">
            <i class="fi fi-rr-plus me-2"></i> Nuevo
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0">
                        Listado de departamentos
                        <span class="badge bg-subtle-primary text-primary ms-1">{{ $departments->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0 pb-2">
                    <table id="departmentsTable" class="table display w-100">
                        <thead class="table-light">
                        <tr>
                            <th class="minw-100px">#</th>
                            <th class="minw-250px">Nombre</th>
                            <th class="minw-150px">Usuarios</th>
                            <th class="minw-150px">Creado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <td>#{{ $department->id }}</td>
                                <td class="fw-medium">{{ $department->name }}</td>
                                <td>
                                        <span class="badge bg-subtle-secondary text-secondary">
                                            {{ $department->users_count }}
                                        </span>
                                </td>
                                <td>{{ $department->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <a href="{{ route('departments.edit', $department) }}"
                                           class="btn btn-sm btn-subtle-primary waves-effect">
                                            <i class="fi fi-rr-edit me-1"></i> Editar
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-subtle-danger waves-effect btn-delete"
                                                data-action="{{ route('departments.destroy', $department) }}"
                                                data-name="{{ $department->name }}">
                                            <i class="fi fi-rr-trash me-1"></i> Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Form oculto para el borrado --}}
    <form id="deleteForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
    <script>
        initDT('departmentsTable');

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;

            Swal.fire({
                title: '¿Eliminar departamento?',
                html: `Se eliminará <strong>${btn.dataset.name}</strong>. Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = btn.dataset.action;
                    form.submit();
                }
            });
        });
    </script>
@endpush
