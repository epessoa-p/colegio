@extends('layouts.app')

@section('title', 'Editar periodo')

@section('page')
@include('admin.periodos.form', ['action' => route('periodos.update', $periodo), 'method' => 'PUT', 'periodo' => $periodo])
@endsection
