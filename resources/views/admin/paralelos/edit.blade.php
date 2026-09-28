@extends('layouts.app')

@section('title', 'Editar paralelo')

@section('page')
@include('admin.paralelos.form', ['action' => route('paralelos.update', $paralelo), 'method' => 'PUT', 'paralelo' => $paralelo])
@endsection
