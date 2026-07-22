@extends('layouts.app')

@section('title', 'Editar Departamento')

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 mt-1">
        <x-breadcrumb :items="[
            ['label' => 'Inicio',              'url' => route('dashboard'),          'icon' => 'fi fi-rr-home'],
            ['label' => 'Mantenimiento',       'url' => '#',                         'icon' => 'fi fi-rr-settings'],
            ['label' => 'Listado de Departamentos', 'url' => route('departments.index'), 'icon' => 'fi fi-rr-list'],
            ['label' => 'Editar Departamento'],
        ]" />
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Actualizar Departamento
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('departments.update', $department) }}" method="POST" id="departmentForm">
                        @csrf
                        @method('PUT')
                        @include('departments._form', ['department' => $department])
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
