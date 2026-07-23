@extends('layouts.app')

@section('title', 'Tickets')

@push('styles')
<style>
    /* Avatar de iniciales */
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
    /* Fijar altura mínima de filas de tabla para evitar saltos al cargar */
    #tickets-table-wrap tbody tr { height: 52px; }
</style>
@endpush

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',  'url' => route('dashboard'), 'icon' => 'fi fi-rr-home'],
                ['label' => 'Tickets'],
            ]"/>
        </div>
        <a href="{{ route('tickets.create') }}" class="btn btn-primary waves-effect waves-light">
            <i class="fi fi-rr-plus me-2"></i> Nuevo
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden p-0" id="tickets-table-wrap">
                <livewire:tickets-table title="Listado de tickets" />
            </div>
        </div>
    </div>
@endsection
