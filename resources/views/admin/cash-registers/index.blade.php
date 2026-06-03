@extends('layouts.app')

@section('page')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Cajas</h1>
            <p class="text-muted small">Gestiona las cajas de tu empresa</p>
        </div>
        <a href="{{ route('cash-registers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nueva Caja
        </a>
    </div>

    @if($cashRegisters->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay cajas registradas aún.
            <a href="{{ route('cash-registers.create') }}" class="alert-link">Crear la primera caja</a>
        </div>
    @else
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th class="px-4 py-3" style="width: 5%">#</th>
                        <th class="px-4 py-3" style="width: 25%">Nombre</th>
                        <th class="px-4 py-3" style="width: 15%">Código</th>
                        <th class="px-4 py-3" style="width: 20%">Sucursal</th>
                        <th class="px-4 py-3" style="width: 10%">Estado</th>
                        <th class="px-4 py-3" style="width: 15%">Sesiones Activas</th>
                        <th class="px-4 py-3 text-end" style="width: 10%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashRegisters as $register)
                        <tr>
                            <td class="px-4 py-3 text-muted small">{{ $register->id }}</td>
                            <td class="px-4 py-3 fw-500">
                                <i class="bi bi-safe text-primary"></i>
                                {{ $register->name }}
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $register->code ?? '-' }}</td>
                            <td class="px-4 py-3 text-muted">
                                {{ $register->branch?->name ?? 'General' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($register->active)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-info">{{ $register->activeSessions->count() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($register->active)
                                        @if($register->activeSessions->isEmpty())
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" 
                                                data-bs-target="#openSessionModal" data-register-id="{{ $register->id }}"
                                                data-register-name="{{ $register->name }}" title="Abrir sesión">
                                                <i class="bi bi-play-circle"></i> Abrir
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" 
                                                data-bs-target="#closeSessionModal" data-register-id="{{ $register->id }}"
                                                data-session-id="{{ $register->activeSessions->first()->id }}"
                                                data-register-name="{{ $register->name }}" title="Cerrar sesión">
                                                <i class="bi bi-stop-circle"></i> Cerrar
                                            </button>
                                        @endif
                                    @endif
                                    <a href="{{ route('cash-registers.edit', $register) }}" class="btn btn-outline-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('cash-registers.destroy', $register) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal para abrir sesión -->
<div class="modal fade" id="openSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Abrir Sesión de Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="openSessionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Caja</label>
                        <p id="registerNameDisplay" class="mb-0 fw-bold text-primary"></p>
                    </div>
                    <div class="mb-3">
                        <label for="personal_id" class="form-label">Personal Responsable <span class="text-danger">*</span></label>
                        <select class="form-select" id="personal_id" name="personal_id" required>
                            <option value="">Seleccionar personal...</option>
                            @foreach(\App\Models\Personal::all() as $personal)
                                <option value="{{ $personal->id }}">{{ $personal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="opening_amount" class="form-label">Monto de Apertura <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="opening_amount" name="opening_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas (opcional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-play-circle"></i> Abrir Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para cerrar sesión -->
<div class="modal fade" id="closeSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Cerrar Sesión de Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="closeSessionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Caja</label>
                        <p id="registerNameDisplay2" class="mb-0 fw-bold text-primary"></p>
                    </div>
                    <div class="mb-3">
                        <label for="closing_amount" class="form-label">Monto de Cierre <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="closing_amount" name="closing_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="close_notes" class="form-label">Notas (opcional)</label>
                        <textarea class="form-control" id="close_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-stop-circle"></i> Cerrar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openSessionModal = document.getElementById('openSessionModal');
        const closeSessionModal = document.getElementById('closeSessionModal');
        const openSessionForm = document.getElementById('openSessionForm');
        const closeSessionForm = document.getElementById('closeSessionForm');

        openSessionModal.addEventListener('show.bs.modal', function(e) {
            const button = e.relatedTarget;
            const registerId = button.getAttribute('data-register-id');
            const registerName = button.getAttribute('data-register-name');
            
            document.getElementById('registerNameDisplay').textContent = registerName;
            openSessionForm.action = `/admin/cash-sessions/${registerId}/open`;
        });

        closeSessionModal.addEventListener('show.bs.modal', function(e) {
            const button = e.relatedTarget;
            const registerId = button.getAttribute('data-register-id');
            const sessionId = button.getAttribute('data-session-id');
            const registerName = button.getAttribute('data-register-name');
            
            document.getElementById('registerNameDisplay2').textContent = registerName;
            closeSessionForm.action = `/admin/cash-sessions/${sessionId}/close`;
        });
    });
</script>
@endsection
