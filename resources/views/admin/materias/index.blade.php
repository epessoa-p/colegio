@extends('layouts.app')

@section('title', 'Materias')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-book"></i> Materias</h1>
            <p class="text-muted mb-0">Matemáticas, Lenguaje, Ciencias, etc.</p>
        </div>
        <a href="{{ route('materias.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Materia
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Materia</th>
                            <th>Código</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materias as $materia)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $materia->name }}</div></td>
                                <td>{{ $materia->code ?: '-' }}</td>
                                <td>{{ $materia->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $materia->active ? 'bg-success' : 'bg-secondary' }}">{{ $materia->active ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('materias.edit', $materia) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar materia {{ addslashes($materia->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No hay materias registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $materias->links() }}</div>
    </div>
</div>
@endsection
