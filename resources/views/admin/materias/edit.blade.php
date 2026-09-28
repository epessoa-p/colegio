@extends('layouts.app')

@section('title', 'Editar materia')

@section('page')
@include('admin.materias.form', ['action' => route('materias.update', $materia), 'method' => 'PUT', 'materia' => $materia])
@endsection
