@extends('layouts.app')

@section('title', 'Nuevo grado')

@section('page')
@include('admin.grados.form', ['action' => route('grados.store'), 'method' => 'POST', 'grado' => null])
@endsection
