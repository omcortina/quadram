@extends('layouts.principal')
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Listado</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Almacen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado almacenes</li>
        </ol>
        </nav>
    </div>
    <div class="col-lg-6 col-5 text-right">
        <a href="#" onclick="AgregarUbicacion()" class="btn btn-sm btn-neutral">+ Nueva ubicaión</a>
    </div>
</div>
@endsection
@section('contenido')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col" style="width: 60px;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection