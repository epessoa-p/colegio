@extends('layouts.app')

@section('title', 'Periodos')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-clock-history"></i> Periodos</h1>
            <p class="text-muted mb-0">Bimestre 1, Bimestre 2, etc., por gestión.</p>
        </div>
        <a href="{{ route('periodos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Periodo
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Periodo</th>
                            <th>Gestión</th>
                            <th class="text-center">Orden</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodos as $periodo)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $periodo->name }}</div></td>
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $periodo->gestion?->year ?? '-' }}</span></td>
                                <td class="text-center">{{ $periodo->order ?? '-' }}</td>
                                <td>{{ $periodo->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $periodo->active ? 'bg-success' : 'bg-secondary' }}">{{ $periodo->active ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('periodos.destroy', $periodo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar periodo {{ addslashes($periodo->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No hay periodos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $periodos->links() }}</div>
    </div>
</div>
@endsection
