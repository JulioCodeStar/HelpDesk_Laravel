@extends('layouts.app')

@section('title', 'Crear Departamento')

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 mt-1">
        <x-breadcrumb :items="[
            ['label' => 'Inicio',              'url' => route('dashboard'),          'icon' => 'fi fi-rr-home'],
            ['label' => 'Mantenimiento',       'url' => '#',                         'icon' => 'fi fi-rr-settings'],
            ['label' => 'Listado de Departamentos', 'url' => route('categories.index'), 'icon' => 'fi fi-rr-list'],
            ['label' => 'Crear Departamento'],
        ]" />
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Crear Departamento
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" class="row g-3">
                        @csrf
                        @include('departments._form')
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
