@extends('layouts.app')

@section('page')
<div class="container-fluid" style="max-width: 600px;">
    <div class="mb-4">
        <h1 class="h2 mb-0">Editar Caja</h1>
        <p class="text-muted small">Modifica los datos de la caja</p>
    </div>

    <div class="bg-white p-4 rounded shadow-sm">
        <form action="{{ route('cash-registers.update', $cashRegister) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.cash-registers.form')

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Guardar Cambios
                </button>
                <a href="{{ route('cash-registers.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
