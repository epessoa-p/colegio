@extends('layouts.app')

@section('title', 'Nueva gestión')

@section('page')
@include('admin.gestiones.form', ['action' => route('gestiones.store'), 'method' => 'POST', 'gestion' => null])
@endsection
