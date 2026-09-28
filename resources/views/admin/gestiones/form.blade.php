<div class="container-fluid" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1">{{ $gestion ? 'Editar gestión' : 'Nueva gestión' }}</h1>
            <p class="text-muted mb-0">Define el año de gestión escolar y su estado.</p>
        </div>
        <a href="{{ route('gestiones.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
            <div>
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3">
                    @if($companies->count() > 1)
                        <div class="col-12">
                            <label class="form-label">Empresa</label>
                            <select name="company_id" class="form-select" required>
                                <option value="">Seleccionar empresa</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ (string) old('company_id', $gestion?->company_id) === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">Año <span class="text-danger">*</span></label>
                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $gestion?->year ?? date('Y')) }}" min="2000" max="2100" required>
                        @error('year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="abierta" {{ old('status', $gestion?->status ?? 'abierta') === 'abierta' ? 'selected' : '' }}>Abierta</option>
                            <option value="cerrada" {{ old('status', $gestion?->status) === 'cerrada' ? 'selected' : '' }}>Cerrada</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', $gestion?->active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Activo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary px-4" type="submit"><i class="bi bi-save"></i> {{ $gestion ? 'Guardar cambios' : 'Crear gestión' }}</button>
            <a href="{{ route('gestiones.index') }}" class="btn btn-light border">Cancelar</a>
        </div>
    </form>
</div>
