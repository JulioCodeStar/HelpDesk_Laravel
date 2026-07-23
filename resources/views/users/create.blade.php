@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 mt-1">
        <x-breadcrumb :items="[
            ['label' => 'Inicio',              'url' => route('dashboard'),          'icon' => 'fi fi-rr-home'],
            ['label' => 'Mantenimiento',       'url' => '#',                         'icon' => 'fi fi-rr-settings'],
            ['label' => 'Listado de Usuarios', 'url' => route('faqs.index'), 'icon' => 'fi fi-rr-list'],
            ['label' => 'Crear Usuario'],
        ]" />
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Crear Usuario
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" id="userForm" class="row g-3">
                        @csrf
                        @include('users._form', ['departments' => $departments])
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
