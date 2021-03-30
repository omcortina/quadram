@extends('layouts.principal')
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Información</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Almacen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Información alamcen</li>
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Nombre</b></label>
                            <p>Dorguería la rebaja</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Dirección</b></label>
                            <p>Calle 1 # 2 -3</p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Teléfono</b></label>
                            <p>+(57) 313 123 4567</p>
                        </div>
                    </div>
                </div>
                <div class="row" id="div_agregar_ubicacion">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-4">
                        <input type="text" placeholder="Nombre de la ubicación" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success">Guardar</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Ubicaciones</b></label>
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
                                        <tr>
                                            <td>1</td>
                                            <td>Bodega</td>
                                            <td><a href="#">Ver estantes</a></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Ventas</td>
                                            <td><a href="#">Ver estantes</a></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Sub-bodega</td>
                                            <td><a href="#">Ver estantes</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Estantes</b></label>
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
                                        <tr>
                                            <td>1</td>
                                            <td>Estante A</td>
                                            <td><a href="#">Gestionar filas</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#div_agregar_ubicacion").hide()
        $("#div_agregar_estante").hide()
    })

    function AgregarUbicacion(){
        $("#div_agregar_ubicacion").show()
        $("#div_agregar_estante").hide()
    }
</script>
@endsection  