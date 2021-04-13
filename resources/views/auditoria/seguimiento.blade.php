@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-9">
        <h6 class="h2 text-white d-inline-block mb-0">Seguimiento de auditoria</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a onclick="history.go(-1)">Auditoria</a></li>
            <li class="breadcrumb-item active" aria-current="page">Almacen - {{ $inventario->almacen->nombre }}</li>
            <li class="breadcrumb-item active" aria-current="page">Desde {{ date('d/m/Y H:i', strtotime($inventario->fecha_inicio)) }} hasta {{ date('d/m/Y H:i', strtotime($inventario->fecha_fin)) }}</li>
        </ol>
        </nav>
    </div>
</div>
@endsection

@section('contenido')
<style type="text/css">
    .panel_stand p{
        font-size: smaller;
        margin-bottom: 0;
    }
    .pd-short{
        display: flex;
        padding: 10px !important;
    }
    .pd-short:hover{
        cursor: pointer;
        background-color: aliceblue;
    }
    .rigth-align{
        cursor: pointer;
        margin-top: 3px;
        position: absolute;
        right: 0;
    }
    .td-location-auditoria{
        padding-top: 19px !important;
        padding-bottom: 19px !important;
    }
    .td-location-auditoria:hover{
        cursor: pointer;
        background-color: aliceblue;
    }
    .td-location-conteo{
        padding-top: 19px !important;
        padding-bottom: 19px !important;
    }
    .td-location-conteo:hover{
        cursor: pointer;
        background-color: aliceblue;
    }
    .td-active{
        background-color: #5e72e47d;
    }
    .td-active:hover{
        background-color: #5e72e47d;
    }
    .btn-conteo{
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .btn-active{
        color: #fff;
        border-color: #5e72e4;
        background-color: #324cdd;
    }
    .btn-active:hover{
        color: #fff;
    }
    input[type="datetime-local"]{
        font-size: small;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    
                    <div class="col-sm-4">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col"><center><b>Locación</b></center></th>
                              </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-4">
                        <table class="table align-items-center table-flush" id="auditoria-tabla-estantes">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col" colspan="2"><center><b>Estantes</b></center></th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="col-sm-4">
                        <table class="table align-items-center table-flush" id="auditoria-tabla-estantes">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col" colspan="2"><center><b>Produtos</b></center></th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@csrf

<script>
    
</script>
@endsection

