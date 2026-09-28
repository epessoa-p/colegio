@extends('layouts.app')

@section('title', 'Nueva materia')

@section('page')
@include('admin.materias.form', ['action' => route('materias.store'), 'method' => 'POST', 'materia' => null])
@endsection
