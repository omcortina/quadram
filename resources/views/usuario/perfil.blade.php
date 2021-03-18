@extends('layouts.principal')
<style>
    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }
    .custom-file-input::before {
        content: 'Seleccione una imagen';
        display: inline-block;
        background: linear-gradient(top, #f9f9f9, #e3e3e3);
        border: 1px solid #999;
        border-radius: 3px;
        padding: 5px 8px;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        text-shadow: 1px 1px #fff;
        font-weight: 700;
        font-size: 10pt;
    }
    .custom-file-input:hover::before {
        border-color: black;
    }
    .custom-file-input:active::before {
        background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
    }
</style>
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-8 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Perfil</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuario/listado') }}">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perfil</li>
        </ol>
        </nav>
    </div>
</div>
@endsection

@section('contenido')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            @if (session('mensaje_perfil'))
                <div id="msg" class="alert alert-success" style="border-bottom-right-radius: 0px !important; border-bottom-left-radius: 0px !important;">
                    <li>{{session('mensaje_perfil')}}</li>
                </div>

                <script>
                    setTimeout(function(){ $('#msg').fadeOut() }, 4000);
                </script>
            @endif
            <div class="card-header border-0">
                {{ Form::open(array('id' => 'form-perfil-usuario', 'files' => true)) }}
                <div class="row align-items-center">
                    <div class="col-sm-4">
                        <center>
                            <img id="url_imagen" src="{{$usuario->obtenerImagen()}}" class="rounded" style="width: 150px; height: 150px;">
                            <input type="file" name="url_imagen" id="archivo" style="margin-top: 15px !important; margin-left: 25px !important;" class="custom-file-input">
                            <p class="file-name" style="font-size: 14px;"></p>
                        </center>
                    </div>

                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><b>*</b>Tipo de documento</label>
                                    <select class="form-control" name="id_dominio_tipo_documento" id="tipo_documento">
                                        <option value = "">Seleccione...</option>
                                        @foreach($tipos_documento as $tipo)
                                            <option 
                                            @if($usuario->id_dominio_tipo_documento == $tipo->id_dominio) selected @endif
                                            value="{{ $tipo->id_dominio }}">{{ $tipo->nombre }}</option>
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
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button type="button" onclick="Actualizar()" class="btn btn-success">Actualizar perfil</button>
                                    <button type="button" class="btn btn-info" onclick="AbrirModal()">Cambiar contraseña</button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
    $( document ).ready(function() {
        const file = document.querySelector('#archivo');
        file.addEventListener('change', (e) => {
            // Get the selected file
            const [file] = e.target.files
            // Get the file name and size
            const { name: fileName, size } = file
            // Convert size in bytes to kilo bytes
            const fileSize = (size / 1000).toFixed(2)
            // Set the text content
            const fileNameAndSize = `${fileName} - ${fileSize}KB`
            document.querySelector('.file-name').textContent = fileNameAndSize
        })

        $('#archivo').change(function(){
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
                var reader = new FileReader();
                reader.onload = function (e) {
                   $('#url_imagen').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                document.querySelector('.file-name').textContent = ""
                let input_imagen = $('#url_imagen')
                input_imagen.val("")
                $('#url_imagen').attr('src', '{{ $usuario->obtenerImagen() }}');
                toastr.error("El archivo seleccionado debe ser una imagen.")
                return false
            }
        })
    })


    function AbrirModal(){
        $("#ModalCambiarPassword").modal("show")
    }

    function CambiarPassword(){
        let password_actual = $("#password_actual").val()
        let password_nueva = $("#password_nueva").val()
        let password_confirmar = $("#password_confirmar").val()

        if($.trim(password_actual) == ""){
            toastr.error("Ingresa tu contraseña actual.");
            return false
        }

        if($.trim(password_nueva) == "" || $.trim(password_confirmar) == ""){
            toastr.error("Ingresa la nueva contraseña.");
            return false
        }

        if($.trim(password_nueva) != $.trim(password_confirmar) || $.trim(password_confirmar) != $.trim(password_nueva)){
            toastr.error("Las contraseñas no coinciden.");
            return false
        }

        let data_form = $("#form-cambiar-password").serialize()
        let token = data_form.split("&")[0].split("=")[1]
        let url = "{{route('usuario/cambiar_password')}}"
        let request = {
            "_token" : token,
            "id_usuario" : {{session('id_usuario')}},
            "password" : password_actual,
            "password_nueva" : password_nueva
        }

        $.post(url, request, (response)=>{
            if(response.error == false){
                toastr.success(response.mensaje)
                setTimeout(()=>{
                    location.reload()
                }, 500)
                return false
            }else{
                toastr.error(response.mensaje)
                return false
            }
        })
    }

    function Actualizar(){
        let tipo_documento = $("#tipo_documento").val()
        let documento = $("#documento").val()
        let nombres = $("#nombres").val()
        let apellidos = $("#apellidos").val()
        let telefono = $("#telefono").val()
        let nombre_usuario = $("#nombre_usuario").val()

        if($.trim(tipo_documento) == "") { toastr.error("El tipo de documento es obligatorio."); return false }
        if($.trim(documento) == "") { toastr.error("El número de documento es obligatorio."); return false; }
        if($.trim(nombres) == "") {  toastr.error("El nombre es obligatorio."); return false; }
        if($.trim(apellidos) == "") { toastr.error("El apellido es obligatorio."); return false; }
        if($.trim(telefono) == "") { toastr.error("El teléfono es obligatorio."); return false; }
        if($.trim(nombre_usuario) == "") { toastr.error("El nombre de usuario es obligatorio."); return false; }

        $("#form-perfil-usuario").submit()
    }

</script>
@endsection

<div class="modal" tabindex="-1" id="ModalCambiarPassword">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#ModalCambiarPassword').modal('hide')"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Contraseña actual</label>
                        <input type="password" class="form-control" id="password_actual">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Nueva contraseña</label>
                        <input type="password" class="form-control" id="password_nueva">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password" class="form-control" id="password_confirmar">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#ModalCambiarPassword').modal('hide')">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="CambiarPassword()">Guardar</button>
            </div>
        </div>
    </div>
</div>
{{ Form::open(array('method' => 'post', 'id' => 'form-cambiar-password')) }}
{{ Form::close() }}