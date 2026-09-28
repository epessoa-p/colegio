@extends('layouts.app')

@section('title', 'Nuevo paralelo')

@section('page')
@include('admin.paralelos.form', ['action' => route('paralelos.store'), 'method' => 'POST', 'paralelo' => null])
@endsection
