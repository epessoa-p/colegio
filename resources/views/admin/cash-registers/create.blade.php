@extends('layouts.app')

@section('page')
<div class="container-fluid" style="max-width: 600px;">
    <div class="mb-4">
        <h1 class="h2 mb-0">Nueva Caja</h1>
        <p class="text-muted small">Registra una nueva caja en tu empresa</p>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <form action="{{ route('cash-registers.store') }}" method="POST">
            @csrf
            @include('admin.cash-registers.form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Crear Caja
                </button>
                <a href="{{ route('cash-registers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
