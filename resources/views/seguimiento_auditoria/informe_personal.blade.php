@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-9">
        <h6 class="h2 text-white d-inline-block mb-0">Seguimiento de auditoria</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a onclick="history.go(-1)">Auditoria</a></li>
            <li class="breadcrumb-item active" aria-current="page">Almacen - {{ $auditoria->inventario->almacen->nombre }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ date("d/m/Y H:i", strtotime($auditoria->fecha_inicio)) }} hasta {{ date("d/m/Y H:i", strtotime($auditoria->fecha_fin)) }}</li>
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
                        <h3>Informe de seguimiento de auditoria</h3>
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
                    <div class="col-sm-4">
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

                    <div class="col-sm-4">
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

                    <div class="col-sm-4">
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
                </div>
            </div>
        </div>
    </div>
</div>
@csrf

<script>
    var locaciones = []
    var fila_escojida = null;
    var estante_escojido = null;
    var locacion_escojida = null;
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
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay filas disponibles</span> </center>"
        $("#seguimiento-tabla-filas tbody").html(tabla)
        $("#seguimiento-tabla-productos tbody").html("")
    }

    function ActualizarProductos(id_locacion, id_estante, id_fila) {
        ValidarActive('fila', id_fila)
        $("#ModalProductos").modal("show")
        let locacion = this.locaciones.find(element => element.id_locacion == id_locacion)
        let estante = locacion.estantes.find(element => element.id_estante == id_estante)
        let fila = estante.filas.find(element => element.id_fila == id_fila)
        let tabla = ""
        fila.productos.forEach((producto) => {
            tabla += '<tr>'+
                        '<td>'+producto.codigo+'</td>'+
                        '<td><strong>'+producto.nombre+'</strong></td>'+
                        '<td>'+(producto.id_seguimiento_auditoria == -1 ? "Sin contar" : producto.seguimiento.created_at)+'</td>'
            if(producto.id_seguimiento_auditoria != -1){
                tabla += '<td><center><span onclick="BorrarSeguimientoAuditoria('+producto.id_seguimiento_auditoria+')"><i class="fa fa-trash"></i></span></center></td>'
            }     
            tabla +=   '</tr>'
        })
        if(tabla == "") tabla = "<center> <span class='span-msg'>No hay productos disponibles</span> </center>"
        $("#seguimiento-tabla-productos tbody").html(tabla)
        this.fila_escojida = fila
        this.estante_escojido = estante
        this.locacion_escojida = locacion
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

    function BorrarSeguimientoAuditoria(id_seguimiento_auditoria) {
        let confirmacion = confirm("¿Seguro que desea eliminar este seguimiento?")
        if (confirmacion) {
            let url = "{{ route('api/auditor/deleteTracing') }}"
            let request = {'id_seguimiento_auditoria' : id_seguimiento_auditoria}
            loading(true, "Borrando registro...")
            $.ajax({
                url : url,
                type : 'DELETE',
                data : request,
                success: function (response) {
                    location.reload()
                }
            });
        }
    }

    function AgregarProducto() {
        let id_producto = $("#id_producto").val()
        if(id_producto == 0){
            toastr.error("Debe escoger un producto valido")
            return false
        }
        
        let validacion = this.fila_escojida.productos.filter(element => element.id_producto == id_producto)
        if(validacion.length > 0){
            toastr.error("Este producto ya se encuentra en esta fila auditado", "Error")
        }else{
            $("#btn-agregar").prop("disabled", true)
            $("#btn-agregar").html("Validando...")
            let url = "{{ route('api/auditor/saveTracing') }}"
            let request = {
                'id_auditoria_detalle' : this.estante_escojido.id_auditoria_detalle,
                'id_estante' : this.estante_escojido.id_estante,
                'id_fila' : this.fila_escojida.id_fila,
                'id_producto' : id_producto
            }
            $.post(url, request, (response) => {
                $("#btn-agregar").prop("disabled", false)
                $("#btn-agregar").html("Agregar producto")
                toastr.success(response.message)
                location.reload()
            })
            .fail((error) => {
                toastr.error("Ocurrio un error")
                $("#btn-agregar").prop("disabled", true)
                $("#btn-agregar").html("Agregar producto")
            })

        }
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        BuscarLocaciones()
    });
</script>
@endsection

<div class="modal" id="ModalProductos">
    <div class="modal-dialog" style="max-width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Listado productos auditados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalProductos').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8">
                        <label>Producto</label>
                        <select class="my-select2" id="id_producto">
                            @php
                                $productos = \App\Models\Producto::all()->where('estado', 1);
                            @endphp
                            <option value="0">Seleccione un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id_producto }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 text-right">
                        <br>
                        <button onclick="AgregarProducto()" class="btn btn-primary mt-2 w-100" id="btn-agregar">Agregar producto</button>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="seguimiento-tabla-productos">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><b>Código</b></th>
                                        <th scope="col"><b>Nombre</b></th>
                                        <th scope="col"><b>Realización</b></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#ModalProductos').modal('hide')">Cerrar</button>
            </div>
        </div>
    </div>
</div>

