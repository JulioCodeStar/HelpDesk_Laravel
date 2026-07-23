@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',        'url' => route('dashboard'), 'icon' => 'fi fi-rr-home'],
                ['label' => 'Mantenimiento', 'url' => '#',                'icon' => 'fi fi-rr-settings'],
                ['label' => 'Categorías'],
            ]"/>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary waves-effect waves-light">
            <i class="fi fi-rr-plus me-2"></i> Nueva categoría
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden p-0">
                <livewire:categories-table title="Listado de categorías" />
            </div>
        </div>
    </div>
@endsection
