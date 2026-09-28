@extends('layouts.app')

@section('title', 'Paralelos')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-columns"></i> Paralelos</h1>
            <p class="text-muted mb-0">Paralelos o secciones: A, B, C, etc.</p>
        </div>
        <a href="{{ route('paralelos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Paralelo
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Paralelo</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paralelos as $paralelo)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $paralelo->name }}</div></td>
                                <td>{{ $paralelo->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $paralelo->active ? 'bg-success' : 'bg-secondary' }}">{{ $paralelo->active ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('paralelos.edit', $paralelo) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('paralelos.destroy', $paralelo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar paralelo {{ addslashes($paralelo->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No hay paralelos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $paralelos->links() }}</div>
    </div>
</div>
@endsection
