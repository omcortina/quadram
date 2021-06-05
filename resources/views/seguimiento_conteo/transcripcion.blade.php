@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-9">
        <h6 class="h2 text-white d-inline-block mb-0">Gestion conteo</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a onclick="history.go(-1)">Conteo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Almacen - {{ $conteo->auditoria->inventario->almacen->nombre }}</li>
            <li class="breadcrumb-item active" aria-current="page">Desde {{ date('d/m/Y H:i', strtotime($conteo->fecha_inicio)) }} hasta {{ date('d/m/Y H:i', strtotime($conteo->fecha_fin)) }}</li>
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
                        <h3><b>Transcripción de Conteos</b></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Contador</label>
                        <select id="select-usuarios" onchange="BuscarLocaciones(this.value)" class="my-select2" >
                            @foreach ($usuarios as $item)
                                <option value="{{ $item->id_usuario }}">{{ $item->presentacion() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-sm-4">
                        <center>
                            <button class="btn btn-active btn-conteo w-100" id="btn-conteo-1" onclick="EscogerConteo(1)">
                                Primero
                            </button>
                        </center>
                    </div>
                    <div class="col-sm-4">
                        <center>
                            <button class="btn btn-conteo w-100" id="btn-conteo-2" onclick="EscogerConteo(2)">
                                Segundo
                            </button>
                        </center>
                    </div>
                    <div class="col-sm-4">
                        <center>
                            <button class="btn btn-conteo w-100" id="btn-conteo-3" onclick="EscogerConteo(3)">
                                Tercero
                            </button>
                        </center>
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
    var conteo_actual = 1;

    function EscogerConteo(conteo) {
        let id_usuario = $("#select-usuarios").val()
        if (conteo != this.conteo_actual && id_usuario) {
            this.conteo_actual = conteo
            $(".btn-conteo").each(function(){ $(this).removeClass('btn-active') });
            $("#btn-conteo-"+conteo).addClass('btn-active')
            BuscarLocaciones(id_usuario)
        }
    }

    function BuscarLocaciones(id_usuario) {
        loading(true, "Consultando información...")
        let url = "{{ route('api/counter/getLocations') }}?usuario="+id_usuario+"&conteo={{ $conteo->id_conteo }}&num_conteo="+this.conteo_actual

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
            if (!producto.tiene_seguimiento_conteo) {
                tabla += '<tr>'+
                            '<td>'+producto.codigo+'</td>'+
                            '<td><strong>'+producto.nombre+'</strong></td>'+
                            '<td>Sin contar</td>'+
                            '<td></td>'+
                            '<td></td>'+
                            '<td></td>'+
                            '<td></td>'+
                            '</tr>'
                
            }else{
                producto.seguimientos.forEach((pro_seguimiento) => {
                    tabla += '<tr>'+
                        '<td>'+producto.codigo+'</td>'+
                        '<td><strong>'+producto.nombre+'</strong></td>'+
                        '<td>'+pro_seguimiento.created_at+'</td>'+
                        '<td>'+pro_seguimiento.lote+'</td>'+
                        '<td>'+pro_seguimiento.fecha_vencimiento+'</td>'+
                        '<td>'+pro_seguimiento.cantidad+'</td>'+
                        '<td><center><span onclick="BorrarSeguimientoConteo('+pro_seguimiento.id_seguimiento_conteo+')"><i class="fa fa-trash"></i></span></center></td>'+
                        '</tr>'
                })
            }
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

    function BorrarSeguimientoConteo(id_seguimiento_conteo) {
        let confirmacion = confirm("¿Seguro que desea eliminar este seguimiento?")
        if (confirmacion) {
            let url = "{{ route('api/counter/deleteTracing') }}"
            let request = {'id_seguimiento_conteo' : id_seguimiento_conteo}
            loading(true, "Borrando registro...")
            $.ajax({
                url : url,
                type : 'DELETE',
                data : request,
                success: function (response) {
                    loading(false)
                    EliminarProductoLista(id_seguimiento_conteo)
                }
            });
        }
    }

    function AgregarProducto() {
        let id_producto = $("#id_producto").val()
        let lote = $("#lote").val()
        let fecha_vencimiento = $("#fecha_vencimiento").val()
        let cantidad = $("#cantidad").val()
        if(id_producto == 0){
            toastr.error("Debe escoger un producto valido")
            return false
        }
        if(cantidad < 0 || cantidad.trim() == ""){
            toastr.error("La cantidad debe ser mayor o igual a 0")
            return false
        }
        
        let validacion = this.fila_escojida.productos.filter(element => element.id_producto == id_producto &&element.seguimiento.lote == lote && element.seguimiento.fecha_vencimiento)
        if(validacion.length > 0){
            toastr.error("Este producto ya se encuentra en esta fila auditado con el mismo lote y fecha de vencimiento", "Error")
        }else{
            $("#btn-agregar").prop("disabled", true)
            $("#btn-agregar").html("Validando...")
            let url = "{{ route('api/counter/saveTracing') }}"
            let request = {
                'id_conteo_detalle' : this.estante_escojido.id_conteo_detalle,
                'id_estante' : this.estante_escojido.id_estante,
                'id_fila' : this.fila_escojida.id_fila,
                'id_producto' : id_producto,
                'lote' : lote,
                'fecha_vencimiento' : fecha_vencimiento,
                'cantidad' : cantidad,
            }

            $.post(url, request, (response) => {
                $("#btn-agregar").prop("disabled", false)
                $("#btn-agregar").html("Agregar producto")
                toastr.success(response.message)
                AgregarProductoLista(this.estante_escojido.id_estante, this.fila_escojida.id_fila, response.product)
            })
            .fail((error) => {
                toastr.error("Ocurrio un error")
                $("#btn-agregar").prop("disabled", true)
                $("#btn-agregar").html("Agregar producto")
            })
        }
    }

    function AgregarProductoLista(id_estante, id_fila, producto) {
        this.locaciones.forEach((locacion) => {
            locacion.estantes.forEach((estante) => {
                if(estante.id_estante == id_estante){
                    estante.filas.forEach((fila) => {
                        if(fila.id_fila == id_fila){
                            fila.productos.forEach((pro) => {
                                if (pro.id_producto == producto.id_producto) {
                                    pro.tiene_seguimiento_conteo = producto.tiene_seguimiento_conteo;
                                    pro.seguimientos = producto.seguimientos
                                }
                            })
                        }
                    })
                }
            })
        })
        ActualizarProductos(this.locacion_escojida.id_locacion, id_estante, id_fila)
    }

    function EliminarProductoLista(id_seguimiento_conteo) {
        this.locaciones.forEach((locacion) => {
            locacion.estantes.forEach((estante) => {
                estante.filas.forEach((fila) => {
                    let pos = 0
                    fila.productos.forEach((pro) => {
                        if (pro.tiene_seguimiento_conteo) {
                            let pos_seguimientos = 0
                            pro.seguimientos.forEach((pro_seguimiento) => {
                                if(pro_seguimiento.id_seguimiento_conteo == id_seguimiento_conteo){
                                    if (pro.seguimientos.length == 1) pro.tiene_seguimiento_conteo = false
                                    pro.seguimientos.splice(pos_seguimientos, 1)
                                }
                                pos_seguimientos++
                            })
                        }
                        pos++
                    })
                })
            })
        })
        ActualizarProductos(this.locacion_escojida.id_locacion, this.estante_escojido.id_estante, this.fila_escojida.id_fila)
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        @if ($usuario != null)
            BuscarLocaciones({{ $usuario->id_usuario }})
        @else
            @if (count($usuarios) > 0)
                BuscarLocaciones({{ $usuarios[0]->id_usuario }})
            @endif
        @endif
    });
</script>

<div class="modal" id="ModalProductos">
    <div class="modal-dialog" style="max-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Listado productos auditados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalProductos').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
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
                    <div class="col-sm-4"><br>
                        <label>Lote</label>
                        <input type="text" class="form-control" id="lote">
                    </div>
                    <div class="col-sm-4"><br>
                        <label>F. Vencimiento</label>
                        <input type="date" class="form-control" id="fecha_vencimiento">
                    </div>
                    <div class="col-sm-4"><br>
                        <label>Cantidad</label>
                        <input type="number" class="form-control" id="cantidad">
                    </div>
                    <div class="col-sm-12">
                        <br>
                        <center>
                            <button onclick="AgregarProducto()" class="btn btn-primary mt-2 w-100" id="btn-agregar">Agregar producto</button>
                        </center>
                        
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
                                        <th scope="col"><b>Lote</b></th>
                                        <th scope="col"><b>Fecha Vencimiento</b></th>
                                        <th scope="col"><b>Cantidad</b></th>
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
@endsection

