@extends('layouts.principal')
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Información</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Almacen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Información almacen</li>
        </ol>
        </nav>
    </div>
    <div class="col-lg-6 col-5 text-right">
        <a href="#" onclick="$('#div_agregar_locacion').show()" class="btn btn-sm btn-neutral">+ Nueva ubicación</a>
    </div>
</div>
@endsection
@section('contenido')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <div class="form-group">
                            <label><b>Nombre</b></label>
                            <p>{{ $almacen->nombre }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center">
                        <div class="form-group">
                            <label><b>Dirección</b></label>
                            <p>{{ $almacen->direccion }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center">
                        <div class="form-group">
                            <label><b>Teléfono</b></label>
                            <p>{{ $almacen->telefono }}</p>
                        </div>
                    </div>
                </div>
                <div class="row" id="div_agregar_locacion">
                    @csrf
                    <div class="col-sm-1"></div>
                    <div class="col-sm-4">
                        <input type="text" id="nombre_locacion" placeholder="Nombre de la locacion" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" id="descripcion_locacion" placeholder="Descripción" class="form-control">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" onclick="AgregarLocacion()">Guardar</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Locaciones</b></label>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <!-- Projects table -->
                                <table class="table align-items-center table-flush" id="tabla_locacion">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col" style="width: 60px;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodytable_locacion">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Estantes</b></label>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <!-- Projects table -->
                                <table class=" table align-items-center table-flush" id="tabla_estante">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col" style="width: 60px;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodytable_estante">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Filas</b></label>
                            <div class="table-responsive" style="margin-top: 10px;">
                                <!-- Projects table -->
                                <table class=" table align-items-center table-flush" id="tabla_fila_estante">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col" style="width: 60px;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodytable_fila_estante">

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
    var estante = {
        "nombre" : null,
        "id_locacion" : null,
        "estado" : 1
    }

    var fila = {
        "nombre" : null,
        "id_estante" : null,
        "estado" : 1
    }

    var locaciones = []
    var estantes = []
    $(document).ready(function(){
        $("#div_agregar_locacion").hide()
        ListadoLocacion()
        setTimeout(()=>{
            $("#tabla_locacion").DataTable({
                "searching": false,
                language: {
                    paginate: {
                        next: '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-right"></i></span>',
                        previous: '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-left"></i></span>'
                    }
                }
            })
        }, 200)

        setTimeout(()=>{
            $("#tabla_estante").DataTable({
                "searching": false,
                language : {
                    paginate : {
                        next : '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-right"></i></span>',
                        previous : '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-left"></i></span>'
                    }
                }
            })
        }, 300)

        setTimeout(()=>{
            $("#tabla_fila_estante").DataTable({
                "searching": false,
                language : {
                    paginate : {
                        next : '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-right"></i></span>',
                        previous : '<span class="icons"><i style="margin-left:10px;" data-feather="chevron-left"></i></span>'
                    }
                }
            })
        }, 300)

    })

    function ListadoLocacion(){
        let url = "{{ route('locacion/listado', $almacen->id_almacen) }}"
        $.get(url, (response)=>{
            let fila_locacion = ""
            let fila_estante = ""
            if(response.locaciones.length > 0){
                ConsultarEstantesPorLocacion(response.locaciones[0].id_locacion)
                if(response.locaciones[0].estantes.length > 0){
                    ConsultarFilasPorEstante(response.locaciones[0].estantes[0].id_estante)
                }

                response.locaciones.forEach((locacion)=>{
                    fila_locacion += "<tr>"+
                                        "<td>"+locacion.id_locacion+"</td>"+
                                        "<td>"+locacion.nombre+"</td>"+
                                        "<td><center><span class='icons' onclick='ModalEstante("+locacion.id_locacion+")'><i data-feather='plus-circle'></i></span><span class='icons' onclick='ConsultarEstantesPorLocacion("+locacion.id_locacion+")'><i data-feather='box'></i></span><span class='icons'><i data-feather='trash'></i></span></center></td>"
                                    "</tr>"
                })
                $("#bodytable_locacion").html(fila_locacion)
                feather.replace()
            }
        })
    }

    function AgregarLocacion(){
        let nombre_locacion = $("#nombre_locacion").val()
        let descripcion = $("#descripcion_locacion").val()

        if($.trim(nombre_locacion) == ""){
            toastr.error("El nombre de la locacion no puede ser vacío")
            return false
        }
        if($.trim(descripcion) == ""){
            descripcion = null
        }

        let token = $('input[name=_token]')[0].value
        let request = {
            "_token" : token,
            "nombre_locacion" : nombre_locacion,
            "descripcion" : descripcion,
            "id_almacen" : {{ $almacen->id_almacen }}
        }
        let url = "{{ route('locacion/guardar') }}"
        $.post(url, request, (response)=>{
            if(response.error == false){
                $("#nombre_locacion").val(null)
                $("#descripcion_locacion").val(null)
                toastr.success(response.mensaje)
                ListadoLocacion()
                return false
            }else{
                toastr.error(response.mensaje)
                return false
            }
        })
    }

    function ModalEstante(id_locacion){
        $("#ModalNuevoEstante").modal("show")
        $("#modal_id_locacion").val(id_locacion)
        LimpiarModalEstante()
    }

    function ModalFila(id_estante){
        $("#ModalNuevaFila").modal("show")
        $("#modal_id_estante").val(id_estante)
    }


    function EstablecerEstadoEstante() {
        if(this.estante.estado == 1) {
            $("#estante_estado").removeClass("btn-success")
            $("#estante_estado").addClass("btn-danger")
            $("#estante_estado").html("Inactivo")
            this.estante.estado = 0
        }else{
            $("#estante_estado").addClass("btn-success")
            $("#estante_estado").removeClass("btn-danger")
            $("#estante_estado").html("Activo")
            this.estante.estado = 1
        }
    }

    function EstablecerEstadoFila(){
        if(this.fila.estado == 1) {
            $("#fila_estado").removeClass("btn-success")
            $("#fila_estado").addClass("btn-danger")
            $("#fila_estado").html("Inactivo")
            this.fila.estado = 0
        }else{
            $("#fila_estado").addClass("btn-success")
            $("#fila_estado").removeClass("btn-danger")
            $("#fila_estado").html("Activo")
            this.fila.estado = 1
        }
    }

    function GuardarEstante(){
        let nombre_estante = $("#nombre_estante").val()
        let id_locacion = $("#modal_id_locacion").val()
        if($.trim(nombre_estante) == ""){
            toastr.error("El nombre es obligatorio")
            return false
        }
        this.estante.nombre = nombre_estante
        this.estante.id_locacion = id_locacion
        let token = $('input[name=_token]')[0].value
        let request = {
            "_token" : token,
            "estante" : this.estante
        }
        let url = "{{ route('estante/guardar') }}"
        $.post(url, request, (response)=>{
            if(response.error == false){
                toastr.success(response.mensaje)
                ListadoLocacion()
                LimpiarModalEstante()
                return false
            }else{
                toastr.error(response.mensaje)
                return false
            }
        })
    }

    function GuardarFila(){
        let nombre_fila = $("#nombre_fila").val()
        let id_estante = $("#modal_id_estante").val()
        if($.trim(nombre_fila) == ""){
            toastr.error("El nombre es obligatorio")
            return false
        }
        this.fila.nombre = nombre_fila
        this.fila.id_estante = id_estante
        let token = $('input[name=_token]')[0].value
        let request = {
            "_token" : token,
            "fila" : this.fila
        }
        let url = "{{ route('fila/guardar') }}"
        $.post(url, request, (response)=>{
            if(response.error == false){
                toastr.success(response.mensaje)
                return false
            }else{
                toastr.error(response.mensaje)
                return false
            }
        })

    }

    function LimpiarModalEstante(){
        $("#nombre_estante").val(null)
    }

    function ConsultarEstantesPorLocacion(id_locacion){
        let url = "{{ config('global.servidor') }}/locacion/estantes_por_locacion/"+id_locacion
        $.get(url, (response)=>{
            let fila = "";
            if(response.estantes.length > 0){
                response.estantes.forEach((estante)=>{
                    fila += "<tr>"+
                                "<td>"+estante.id_estante+"</td>"+
                                "<td>"+estante.nombre+"</td>"+
                                "<td><center><span class='icons' onclick='ModalFila("+estante.id_estante+")'><i data-feather='plus-circle'></i></span><span class='icons' onclick='ConsultarFilasPorEstante("+estante.id_estante+")'><i data-feather='list'></i></span><span class='icons'><i data-feather='trash'></i></span></center></td>"
                            "</tr>"
                })
                $("#bodytable_estante").html(fila)
                feather.replace()
            }
        })
    }

    function ConsultarFilasPorEstante(id_estante){
        let url = "{{ config('global.servidor') }}/estante/filas_por_estante/"+id_estante
        $.get(url, (response)=>{
            let item = "";
            if(response.filas.length > 0){
                response.filas.forEach((fila)=>{
                    item += "<tr>"+
                                "<td>"+fila.id_fila_estante+"</td>"+
                                "<td>"+fila.nombre+"</td>"+
                                "<td><center><span class='icons'><i data-feather='trash'></i></span></center></td>"
                            "</tr>"
                })
                $("#bodytable_fila_estante").html(item)
                feather.replace()
            }
        })
    }
</script>
@endsection


<div class="modal" tabindex="-1" id="ModalNuevoEstante">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo estante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalNuevoEstante').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_id_locacion">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nombre_estante" placeholder="Nombre">
                    </div>
                </div>

                <div class="col-sm-12 text-right">
                    <span id="estante_estado" onclick="EstablecerEstadoEstante()" class="btn btn-sm btn-success">Activo</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#ModalNuevoEstante').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarEstante()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="ModalNuevaFila">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva fila</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalNuevaFila').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_id_estante">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nombre_fila" placeholder="Nombre">
                    </div>
                </div>

                <div class="col-sm-12 text-right">
                    <span id="fila_estado" onclick="EstablecerEstadoFila()" class="btn btn-sm btn-success">Activo</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#ModalNuevaFila').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarFila()">Guardar</button>
            </div>
        </div>
    </div>
</div>
