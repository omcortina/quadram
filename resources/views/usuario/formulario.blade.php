@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-8 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">@if($usuario->id_usuario) Actualizar @else Nuevo @endif ususario</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuario/listado') }}">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">@if($usuario->id_usuario) Actualizar @else Nuevo @endif ususario</li>
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
                {{ Form::open(array('id' => 'form-usuario')) }}
                @if($usuario->id_usuario)
                <input type="hidden" name="id" value="{{ $usuario->id_usuario }}">
                @endif
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Tipo de documento</label>
                            <select class="form-control" name="id_dominio_tipo_documento" id="tipo_documento">
                                <option>Seleccione...</option>
                                @foreach($tipos_documento as $tipo)
                                    <option 
                                    @if($usuario->id_dominio_tipo_documento == $tipo->id_dominio) selected @endif
                                    value="{{ $tipo->id_dominio }}" 
                                    >{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>No. de documento</label>
                            <input class="form-control" value="{{ $usuario->documento }}" type="text" id="documento" name="documento">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <input class="form-control" value="{{ $usuario->fecha_nacimiento }}" type="date" min="01-01-1980" max="31-12-2030" name="fecha_nacimiento">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Nombres</label>
                            <input class="form-control" value="{{ $usuario->nombres }}" type="text" id="nombres" name="nombres">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Apellidos</label>
                            <input class="form-control" value="{{ $usuario->apellidos }}" type="text" id="apellidos" name="apellidos">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Teléfono</label>
                            <input class="form-control" value="{{ $usuario->telefono }}" type="text" id="telefono" name="telefono">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Dirección</label>
                            <input class="form-control" value="{{ $usuario->direccion }}" type="text" id="direccion" name="direccion">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Nombre de usuario</label>
                            <input class="form-control" value="{{ $usuario->nombre_usuario }}" type="text" id="nombre_usuario" name="nombre_usuario">
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>*</b>Tipo de usuario</label>
                            <select class="form-control" id="tipo_usuario" name="id_dominio_tipo_usuario">
                                <option value>Selecione...</option>
                                @foreach($tipos_usuario as $tipo)
                                    <option 
                                    @if($usuario->id_dominio_tipo_usuario == $tipo->id_dominio) selected @endif
                                    value="{{ $tipo->id_dominio }}" 
                                    >{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <center>
                            <button type="button" class="btn btn-success" onclick="GuardarUsuario()">Guardar</button>
                        </center>
                    </div>
                </div>

                {{ Form::close() }}
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

        if($.trim(tipo_documento) == "") { Alertar("El tipo de documento es obligatorio."); return false; }
        if($.trim(documento) == "") { Alertar("El número de documento es obligatorio."); return false; }
        if($.trim(nombres) == "") {  Alertar("El nombre es obligatorio."); return false; }
        if($.trim(apellidos) == "") { Alertar("El apellido es obligatorio."); return false; }
        if($.trim(telefono) == "") { Alertar("El teleéfono es obligatorio."); return false; }

        if($.trim(nombre_usuario) == "") { Alertar("El nombre de usuario es obligatorio."); return false; }
        if($.trim(tipo_usuario) == "") { Alertar("El tipo de usuario es obligatorio."); return false; }

        $("#form-usuario").submit()
    }

    function Alertar(mensaje, error = true) {
        if(error)
            toastr.error(mensaje)
        else
            toastr.success(mensaje)
    }
</script>