@extends('layouts.app')

@section('title', 'Nuevo nivel')

@section('page')
@include('admin.niveles.form', ['action' => route('niveles.store'), 'method' => 'POST', 'nivel' => null])
@endsection
