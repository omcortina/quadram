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
        <a href="#" onclick="$('#ModalNuevoAlmacen').modal('show')" class="btn btn-sm btn-neutral">+ Nuevo almacen</a>
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
                                        <th scope="col">Dirección</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="bodytable">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var almacen = {
        "nombre" : null,
        "direccion" : null,
        "telefono" : null,
        "estado" : 1
    }

    $(document).ready(function(){
        Listado()
    })

    function Listado(_loading = true){
        if(_loading) loading(true, 'Consultando almacenes...')
        let url = "{{ route('almacen/listado') }}"
        $.get(url, (response)=>{
            if(response.almacenes.length > 0){
                let fila = ""
                response.almacenes.forEach((almacen)=>{
                    fila += "<tr>"+
                                "<td>"+almacen.id_almacen+"</td>"+
                                "<td>"+almacen.nombre+"</td>"+
                                "<td>"+almacen.direccion+"</td>"+
                                "<td>"+almacen.telefono+"</td>"
                                if(almacen.estado == 1){
                                    fila += "<td><span style='color: #2dce89'>Activo</span></td>"
                                }else{
                                    fila += "<td><span style='color: #f5365c'>Inactivo</span></td>"
                                }
                                fila += "<td><a class='icons' href='{{ config('global.servidor') }}/almacen/informacion/"+almacen.id_almacen+"'><i data-feather='info'></i></a></td>"+
                            "</tr>"

                })

                $("#bodytable").html(fila)
                feather.replace()
                loading(false)
            }
        })
    }

    function EstablecerEstado() {
        if(this.almacen.estado == 1) {
            $("#almacen_estado").removeClass("btn-success")
            $("#almacen_estado").addClass("btn-danger")
            $("#almacen_estado").html("Inactivo")
            this.almacen.estado = 0
        }else{
            $("#almacen_estado").addClass("btn-success")
            $("#almacen_estado").removeClass("btn-danger")
            $("#almacen_estado").html("Activo")
            this.almacen.estado = 1
        }
    }

    function NuevoAlmacen(){
        let nombre = $("#nombre").val()
        let direccion = $("#direccion").val()
        let telefono = $("#telefono").val()

        if($.trim(nombre) == ""){
            toastr.error("El nombre es obligatorio")
            return false
        }

        if($.trim(direccion) == ""){
            toastr.error("La direccion es obligatoria")
            return false
        }

        if($.trim(telefono) == ""){
            toastr.error("El telefono es obligatorio")
            return false
        }

        this.almacen.nombre = nombre
        this.almacen.direccion = direccion
        this.almacen.telefono = telefono

        let url = "{{ route('almacen/nuevo_almacen') }}"
        let data_form = $("#form-nuevo-almacen").serialize()
        let token = data_form.split("&")[0].split("=")[1]
        let request = {
            "_token" : token,
            "almacen" : this.almacen
        }

        $.post(url, request, (response)=>{
            if(response.error == false){
                $("#bodytable").html('')
                toastr.success(response.mensaje)
                Listado()
                LimpiarCamposModal()
                return false
            }else{
                toastr.error(response.mensaje)
                return false
            }
        })
    }

    function LimpiarCamposModal(){
        $("#nombre").val(null)
        $("#direccion").val(null)
        $("#telefono").val(null)
    }
</script>
@endsection

<div class="modal" tabindex="-1" id="ModalNuevoAlmacen">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo almacen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalNuevoAlmacen').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nombre" placeholder="Nombre">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="direccion" placeholder="Dirección">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <input type="text" class="form-control" id="telefono" placeholder="Teléfono">
                    </div>
                </div>

                <div class="col-sm-12 text-right">
                    <span id="almacen_estado" onclick="EstablecerEstado()" class="btn btn-sm btn-success">Activo</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#ModalNuevoAlmacen').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="NuevoAlmacen()">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{ Form::open(array('method' => 'post', 'id' => 'form-nuevo-almacen')) }}
{{ Form::close() }}
