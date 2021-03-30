@extends('layouts.principal')
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Nuevo almacen</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Almacen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo almacen</li>
        </ol>
        </nav>
    </div>
    <div class="col-lg-6 col-5 text-right">
        <a href="#" onclick="AgregarUbicacion()" class="btn btn-sm btn-neutral">+ Nueva ubicaión</a>
    </div>
</div>
@endsection