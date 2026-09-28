@extends('layouts.app')

@section('title', 'Nuevo periodo')

@section('page')
@include('admin.periodos.form', ['action' => route('periodos.store'), 'method' => 'POST', 'periodo' => null])
@endsection
