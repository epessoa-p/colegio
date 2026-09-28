@extends('layouts.app')

@section('title', 'Grados/Cursos')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-mortarboard"></i> Grados/Cursos</h1>
            <p class="text-muted mb-0">1ro Primaria, 2do Primaria, 1ro Secundaria, etc.</p>
        </div>
        <a href="{{ route('grados.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Grado
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Grado/Curso</th>
                            <th>Nivel</th>
                            <th class="text-center">Orden</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grados as $grado)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $grado->name }}</div></td>
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $grado->nivel?->name ?? '-' }}</span></td>
                                <td class="text-center">{{ $grado->order ?? '-' }}</td>
                                <td>{{ $grado->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $grado->active ? 'bg-success' : 'bg-secondary' }}">{{ $grado->active ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('grados.edit', $grado) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('grados.destroy', $grado) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar grado {{ addslashes($grado->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No hay grados registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $grados->links() }}</div>
    </div>
</div>
@endsection
