@extends('layouts.app')

@section('title', 'Gestiones')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1"><i class="bi bi-calendar3"></i> Gestiones</h1>
            <p class="text-muted mb-0">Administra los años de gestión escolar y su estado.</p>
        </div>
        <a href="{{ route('gestiones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Gestión
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Año</th>
                            <th>Empresa</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Activo</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gestiones as $gestion)
                            <tr>
                                <td class="ps-3"><div class="fw-semibold">{{ $gestion->year }}</div></td>
                                <td>{{ $gestion->company?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $gestion->status === 'abierta' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($gestion->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $gestion->active ? 'bg-success' : 'bg-secondary' }}">{{ $gestion->active ? 'Sí' : 'No' }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('gestiones.edit', $gestion) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('gestiones.destroy', $gestion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar la gestión {{ $gestion->year }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">No hay gestiones registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $gestiones->links() }}</div>
    </div>
</div>
@endsection
