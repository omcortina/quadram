<title>Nuevo usuario</title>
@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Nuevo ususario</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo ususario</li>
        </ol>
        </nav>
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
                            <label>Tipo de documento</label>
                            <select class="form-control" id="tipo_documento">
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>No. de documento</label>
                            <input class="form-control" type="text" id="documento">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input class="form-control" type="date" min="01-01-1980" max="31-12-2030" id="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Nombres</label>
                            <input class="form-control" type="text" id="nombres">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Apellidos</label>
                            <input class="form-control" type="text" id="apellidos">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input class="form-control" type="text" id="telefono">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input class="form-control" type="text" id="">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Nombre de usuario</label>
                            <input class="form-control" type="text" id="nombre_usuario">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tipo de usuario</label>
                            <select id="" class="form-control" id="tipo_usuario">
                                <option value="">Selecione...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <center>
                            <button class="btn btn-success" onclick="GuardarUsuario()">Guardar</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function GuardarUsuario(){
        let tipo_documento = $("#tipo_documento").val()
        let documento = $("#documento").val()
        let nombres = $("#nombres").val()
        let apellidos = $("#apellidos").val()
        let telefono = $("#telefono").val()
        let nombre_usuario = $("#nombre_usuario").val()
        let tipo_usuario = $("#tipo_usuario").val()

        if($.trim(tipo_documento) == ""){
            toastr.error("El tipo de documento es obligatorio.")
            return false
        }

        if($.trim(documento) == ""){
            toastr.error("El número de documento es obligatorio.")
            return false
        }

        if($.trim(nombres) == "El nombre es obligatorio."){
            toastr.error("")
            return false
        }

        if($.trim(apellidos) == "El apellido es obligatorio."){
            toastr.error("")
            return false
        }

        if($.trim(telefono) == "El teleéfono es obligatorio."){
            toastr.error("")
            return false
        }

        if($.trim(nombre_usuario) =="El nombre de usuario es obligatorio."){
            toastr.error("")
            return false
        }

        if($.trim(tipo_usuario) == "El tipo de usuario es obligatorio."){
            toastr.error("")
            return false
        }
    }
</script>