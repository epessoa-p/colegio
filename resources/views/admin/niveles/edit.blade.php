@extends('layouts.app')

@section('title', 'Editar nivel')

@section('page')
@include('admin.niveles.form', ['action' => route('niveles.update', $nivel), 'method' => 'PUT', 'nivel' => $nivel])
@endsection
