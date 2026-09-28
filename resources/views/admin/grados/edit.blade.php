@extends('layouts.app')

@section('title', 'Editar grado')

@section('page')
@include('admin.grados.form', ['action' => route('grados.update', $grado), 'method' => 'PUT', 'grado' => $grado])
@endsection
