@extends('layouts.app')

@section('title', 'Niveles')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-layers"></i> Niveles</h1>
            <p class="text-muted mb-0">Inicial, Primaria, Secundaria y otros niveles educativos.</p>
        </div>
        <a href="{{ route('niveles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Nivel
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Nivel</th>
                            <th class="text-center">Orden</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($niveles as $nivel)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $nivel->name }}</div></td>
                                <td class="text-center">{{ $nivel->order ?? '-' }}</td>
                                <td>{{ $nivel->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $nivel->active ? 'bg-success' : 'bg-secondary' }}">{{ $nivel->active ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('niveles.edit', $nivel) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('niveles.destroy', $nivel) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar nivel {{ addslashes($nivel->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No hay niveles registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $niveles->links() }}</div>
    </div>
</div>
@endsection
