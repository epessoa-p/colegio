@extends('layouts.app')

@section('title', 'Editar gestión')

@section('page')
@include('admin.gestiones.form', ['action' => route('gestiones.update', $gestion), 'method' => 'PUT', 'gestion' => $gestion])
@endsection
