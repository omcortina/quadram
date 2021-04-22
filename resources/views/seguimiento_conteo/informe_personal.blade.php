@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-9">
        <h6 class="h2 text-white d-inline-block mb-0">Seguimiento de conteo</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a onclick="history.go(-1)">Conteo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Almacen - {{ $conteo->auditoria->inventario->almacen->nombre }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ date("d/m/Y H:i", strtotime($conteo->fecha_inicio)) }} hasta {{ date("d/m/Y H:i", strtotime($conteo->fecha_fin)) }}</li>
        </ol>
        </nav>
    </div>
</div>
@endsection

@section('contenido')
<style type="text/css">
    .span-msg{
            text-align: center;
            margin-left: 10px;
            font-size: small;
    }
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
    .td-locacion .td-estante .td-fila .td-producto{
        padding-top: 19px !important;
        padding-bottom: 19px !important;
    }
    .td-locacion:hover{
        cursor: pointer;
        background-color: aliceblue;
    }

    .td-estante:hover{
        cursor: pointer;
        background-color: aliceblue;
    }

    .td-fila:hover{
        cursor: pointer;
        background-color: aliceblue;
    }

    .td-producto:hover{
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

    .table-responsive{
        max-height: 700px;
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
                    <div class="col-sm-12">
                        <h3>Informe de seguimiento de conteo</h3>
                        <div>
                            <div class="media align-items-center">
                              <span class="avatar avatar-sm rounded">
                                <img alt="Image placeholder" src="{{ $usuario->obtenerImagen() }}">
                              </span>
                              <div class="media-body  ml-3" style="display: grid;">
                                <span class="mb-0 text-sm  font-weight-bold">{{ $usuario->nombre_completo() }}</span>
                                <span class="mb-0 text-sm">{{ $usuario->documento }}</span>
                              </div>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    
                    <div class="col-sm-3">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="seguimiento-tabla-locaciones">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col"><center><b>Locación</b></center></th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="seguimiento-tabla-estantes">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col" colspan="2"><center><b>Estantes</b></center></th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="seguimiento-tabla-filas">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col" colspan="2"><center><b>Filas</b></center></th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="seguimiento-tabla-productos">
                                <thead class="thead-light">
                                  <tr>
                                    <th scope="col" colspan="2"><center><b>Produtos Encontrados</b></center></th>
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
</div>
@csrf

<script>
    var locaciones = []
    function BuscarLocaciones() {
        loading(true, "Consultando información...")
        let url = "{{ route('api/auditor/getLocations') }}?usuario={{ $usuario->id_usuario }}&auditoria={{ $auditoria->id_auditoria }}"

        $.get(url, (response) => {
            console.log(response)
            this.locaciones = response.locaciones
            ActualizarLocaciones()
            EstablecerEstanteBusqueda()
            loading(false)
        })
        .fail((error) => {
            loading(false)
            toastr.error("Ocurrio un error")
        })
    }

    function ActualizarLocaciones() {
        let tabla = ""
        this.locaciones.forEach((locacion) => {
            tabla += '<tr>'+
                        '<td id="td-locacion-'+locacion.id_locacion+'"'+
                            'class="td-locacion"'+ 
                            'onclick="ActualizarEstantes('+locacion.id_locacion+')">'+
                            '<strong>'+locacion.nombre+'</strong>'+
                        '</td>'+
                    '</tr>'
        })
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay locaciones disponibles</span> </center>"
        $("#seguimiento-tabla-locaciones tbody").html(tabla)
        $("#seguimiento-tabla-estantes tbody").html("")
        $("#seguimiento-tabla-filas tbody").html("")
        $("#seguimiento-tabla-productos tbody").html("")
    }


    function ActualizarEstantes(id_locacion) {
        ValidarActive('locacion', id_locacion)
        let locacion = this.locaciones.find(element => element.id_locacion == id_locacion)
        let tabla = ""
        locacion.estantes.forEach((estante) => {
            tabla += '<tr>'+
                        '<td id="td-estante-'+estante.id_estante+'"'+
                            'class="td-estante"'+ 
                            'onclick="ActualizarFilas('+id_locacion+', '+estante.id_estante+')">'+
                            '<strong>Estante '+estante.nombre+'</strong>'+
                        '</td>'+
                    '</tr>'
        })
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay estantes disponibles</span> </center>"
        $("#seguimiento-tabla-estantes tbody").html(tabla)
        $("#seguimiento-tabla-filas tbody").html("")
        $("#seguimiento-tabla-productos tbody").html("")
    }

    function ActualizarFilas(id_locacion, id_estante) {
        ValidarActive('estante', id_estante)
        let locacion = this.locaciones.find(element => element.id_locacion == id_locacion)
        let estante = locacion.estantes.find(element => element.id_estante == id_estante)
        let tabla = ""
        estante.filas.forEach((fila) => {
            tabla += '<tr>'+
                        '<td id="td-fila-'+fila.id_fila+'"'+
                            'class="td-fila"'+ 
                            'onclick="ActualizarProductos('+id_locacion+', '+id_estante+', '+fila.id_fila+')">'+
                            '<strong>Fila '+fila.nombre+'</strong>'+
                        '</td>'+
                    '</tr>'
        })
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay estantes disponibles</span> </center>"
        $("#seguimiento-tabla-filas tbody").html(tabla)
        $("#seguimiento-tabla-productos tbody").html("")
    }

    function ActualizarProductos(id_locacion, id_estante, id_fila) {
        ValidarActive('fila', id_fila)
        let locacion = this.locaciones.find(element => element.id_locacion == id_locacion)
        let estante = locacion.estantes.find(element => element.id_estante == id_estante)
        let fila = estante.filas.find(element => element.id_fila == id_fila)
        let tabla = ""
        fila.productos.forEach((producto) => {
            tabla += '<tr>'+
                        '<td id="td-producto-'+producto.id_estante+'"'+
                            'class="td-producto"'+ 
                            'onclick="VerProducto('+id_locacion+', '+id_estante+', '+id_fila+', '+producto.id_producto+')">'+
                            '<strong>'+producto.nombre+'</strong>'+
                        '</td>'+
                    '</tr>'
        })
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay productos disponibles</span> </center>"
        $("#seguimiento-tabla-productos tbody").html(tabla)
    }



    function ValidarActive(tabla, id) {
        $(".td-"+tabla).each(function(){ $(this).removeClass('td-active') });

        let td = document.getElementById("td-"+tabla+"-"+id)
        let is_active = td.classList.contains('td-active');
        if(is_active){
            $("#td-"+tabla+"-"+id).removeClass('td-active')
        }
        else{
            $("#td-"+tabla+"-"+id).addClass('td-active')
        }
    }

    
    function EstablecerEstanteBusqueda() {
        @if ($estante != null)
            let id_locacion = {{ $estante->id_locacion }};
            let id_estante = {{ $estante->id_estante }};
            this.ActualizarEstantes(id_locacion)
            this.ActualizarFilas(id_locacion, id_estante)
        @endif
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        BuscarLocaciones()
    });
</script>
@endsection

