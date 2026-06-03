@extends('layouts.app')

@section('page')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h2 mb-0">Sesión de Caja</h1>
        <p class="text-muted small">{{ $cashSession->cashRegister->name }} - {{ $cashSession->personal->name ?? 'N/A' }}</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="bg-white p-4 rounded shadow-sm mb-4">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase mb-2">Monto de Apertura</h6>
                        <p class="h4 mb-0">
                            <i class="bi bi-currency-dollar text-success"></i>
                            {{ number_format($cashSession->opening_amount, 2) }}
                        </p>
                    </div>
                    @if($cashSession->closing_amount)
                        <div class="col-md-6">
                            <h6 class="text-muted small text-uppercase mb-2">Monto de Cierre</h6>
                            <p class="h4 mb-0">
                                <i class="bi bi-currency-dollar text-danger"></i>
                                {{ number_format($cashSession->closing_amount, 2) }}
                            </p>
                        </div>
                    @endif
                </div>

                @if($cashSession->difference)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small text-uppercase mb-2">Diferencia</h6>
                            <p class="h5 mb-0">
                                @if($cashSession->difference > 0)
                                    <span class="text-success">
                                        <i class="bi bi-arrow-up-right"></i>
                                        +{{ number_format($cashSession->difference, 2) }}
                                    </span>
                                @elseif($cashSession->difference < 0)
                                    <span class="text-danger">
                                        <i class="bi bi-arrow-down-left"></i>
                                        {{ number_format($cashSession->difference, 2) }}
                                    </span>
                                @else
                                    <span class="text-info">
                                        <i class="bi bi-dash-circle"></i> 0.00
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase mb-2">Estado</h6>
                        <p class="mb-0">
                            @if($cashSession->isOpen())
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-play-circle"></i> Abierta
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="bi bi-stop-circle"></i> Cerrada
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted small text-uppercase mb-2">Personal Responsable</h6>
                        <p class="mb-0">{{ $cashSession->personal->name ?? 'No asignado' }}</p>
                    </div>
                </div>
            </div>

            @if($cashSession->notes)
                <div class="bg-white p-4 rounded shadow-sm">
                    <h6 class="text-muted small text-uppercase mb-3">Notas</h6>
                    <p class="mb-0">{{ $cashSession->notes }}</p>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="bg-white p-4 rounded shadow-sm sticky-top" style="top: 20px;">
                <h6 class="text-muted small text-uppercase mb-3">Detalles</h6>

                <div class="mb-3">
                    <small class="text-muted">Abierta por</small>
                    <p class="small mb-2">{{ $cashSession->openedBy->name }}</p>
                </div>

                @if($cashSession->closedBy)
                    <div class="mb-3">
                        <small class="text-muted">Cerrada por</small>
                        <p class="small mb-2">{{ $cashSession->closedBy->name }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted">Fecha de Apertura</small>
                    <p class="small mb-2">{{ $cashSession->opened_at->format('d/m/Y H:i') }}</p>
                </div>

                @if($cashSession->closed_at)
                    <div class="mb-3">
                        <small class="text-muted">Fecha de Cierre</small>
                        <p class="small mb-2">{{ $cashSession->closed_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endif

                <hr class="my-4">

                <a href="{{ route('cash-registers.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
