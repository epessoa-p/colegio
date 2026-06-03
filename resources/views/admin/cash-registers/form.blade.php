<div class="mb-3">
    <label for="name" class="form-label">Nombre de la Caja <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" 
        id="name" name="name" value="{{ old('name', $cashRegister->name ?? '') }}" 
        placeholder="Ej: Caja Principal" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="code" class="form-label">Código</label>
    <input type="text" class="form-control @error('code') is-invalid @enderror" 
        id="code" name="code" value="{{ old('code', $cashRegister->code ?? '') }}" 
        placeholder="Ej: CAJA-001" maxlength="50">
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="branch_id" class="form-label">Sucursal</label>
    <select class="form-select @error('branch_id') is-invalid @enderror" 
        id="branch_id" name="branch_id">
        <option value="">Seleccionar sucursal (opcional)</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" 
                @if(old('branch_id', $cashRegister->branch_id ?? null) == $branch->id) selected @endif>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
    @error('branch_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" 
        id="active" name="active" value="1"
        @if(old('active', $cashRegister->active ?? true)) checked @endif>
    <label class="form-check-label" for="active">
        Caja Activa
    </label>
    @error('active')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
