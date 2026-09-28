<div class="container-fluid" style="max-width: 720px;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1">{{ $grado ? 'Editar grado' : 'Nuevo grado' }}</h1>
            <p class="text-muted mb-0">Asocia el grado/curso a un nivel educativo.</p>
        </div>
        <a href="{{ route('grados.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
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
                                    <option value="{{ $company->id }}" {{ (string) old('company_id', $grado?->company_id) === (string) $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label">Nivel <span class="text-danger">*</span></label>
                        <select name="nivel_id" class="form-select @error('nivel_id') is-invalid @enderror" required>
                            <option value="">Seleccionar nivel...</option>
                            @foreach($niveles as $nivel)
                                <option value="{{ $nivel->id }}" {{ (string) old('nivel_id', $grado?->nivel_id) === (string) $nivel->id ? 'selected' : '' }}>{{ $nivel->name }}</option>
                            @endforeach
                        </select>
                        @error('nivel_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($niveles->isEmpty())
                            <small class="text-danger">No hay niveles activos. Crea un nivel primero.</small>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nombre del grado/curso <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $grado?->name) }}" placeholder="Ej: 1ro Primaria" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Orden</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $grado?->order) }}" min="0">
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', $grado?->active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Activo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary px-4" type="submit"><i class="bi bi-save"></i> {{ $grado ? 'Guardar cambios' : 'Crear grado' }}</button>
            <a href="{{ route('grados.index') }}" class="btn btn-light border">Cancelar</a>
        </div>
    </form>
</div>
