@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-8 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Gestion auditoria</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a onclick="history.go(-1)">Auditoria</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestion</li>
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
    input[type="datetime-local"]{
        font-size: small;
    }
</style>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-sm-12">
                        <h3><b>Auditoria</b></h3>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fecha incio</label>
                            <input type="datetime-local" class="form-control" >
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fecha fin</label>
                            <input type="datetime-local" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col"><center><b>Locación</b></center></th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($inventario->almacen->locaciones as $locacion)
                                    <tr>
                                        <td id="td-locacion-auditoria-{{ $locacion->id_locacion }}" 
                                            class="td-location-auditoria" 
                                            onclick="AuditoriaEscogerLocacion({{ $locacion->id_locacion }})">
                                            <strong>{{ $locacion->nombre }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-sm-6">
                        <table class="table align-items-center table-flush" id="auditoria-tabla-estantes">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col" colspan="2"><center><b>Estantes</b></center></th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-sm-12">
                        <h3><b>Conteo</b></h3>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fecha incio</label>
                            <input type="datetime-local" class="form-control" >
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Fecha fin</label>
                            <input type="datetime-local" class="form-control" >
                        </div>
                    </div>
                </div>
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
                    <div class="col-sm-6">
                        <table class="table align-items-center table-flush" id="conteo-tabla-locaciones">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col"><center><b>Locación</b></center></th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="col-sm-6">
                        <table class="table align-items-center table-flush" id="conteo-tabla-estantes">
                            <thead class="thead-light">
                              <tr>
                                <th scope="col" colspan="2"><center><b>Estantes</b></center></th>
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

<div class="modal" tabindex="-1" id="ModalAuditoria">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Auditoria del estante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" 
                        onclick='$("#ModalAuditoria").modal("hide")'></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="auditoria-modal-id-locacion" name="">
                <input type="hidden" id="auditoria-modal-id-estante" name="">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Estante</label>
                        <input type="text" id="auditoria-modal-estante" class="form-control" disabled>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Encargado</label>
                        <select id="auditoria-modal-encargado" class="form-control">
                            <option value="0">No asignado</option>
                            @foreach($usuarios_auditoria as $usuario)
                                <option value="{{ $usuario->id_usuario }}"> {{ $usuario->nombre_completo() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="alert alert-danger" style="display: none;" id="alert-auditoria"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick='$("#ModalAuditoria").modal("hide")'>Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarEstanteAuditoria()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="ModalConteo">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conteo del estante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" 
                        onclick='$("#ModalConteo").modal("hide")'></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="conteo-modal-id-locacion" name="">
                <input type="hidden" id="conteo-modal-id-estante" name="">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Estante</label>
                        <input type="text" id="conteo-modal-estante" class="form-control" disabled>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Encargado</label>
                        <select id="conteo-modal-encargado" class="form-control">
                            <option value="0">No asignado</option>
                            @foreach($usuarios_conteo as $usuario)
                                <option value="{{ $usuario->id_usuario }}"> {{ $usuario->nombre_completo() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="alert alert-danger" style="display: none;" id="alert-conteo"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick='$("#ModalConteo").modal("hide")'>Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="GuardarEstanteConteo()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@csrf

<script>
    var auditoria_locaciones = []
    var conteo_locaciones = []

    var conteo_actual = 1

    function AuditoriaEscogerLocacion(id_locacion) {
        $(".td-location-auditoria").each(function(){ $(this).removeClass('td-active') });

        let td = document.getElementById("td-locacion-auditoria-"+id_locacion)
        let is_active = td.classList.contains('td-active');
        if(is_active){
            $("#td-locacion-auditoria-"+id_locacion).removeClass('td-active')
        }
        else{
            $("#td-locacion-auditoria-"+id_locacion).addClass('td-active')
        }

        AuditoriaActualizarTablaEstantes(id_locacion)
    }

    function AuditoriaActualizarTablaEstantes(id_locacion) {
        let locacion = this.auditoria_locaciones.find(element => element.id_locacion == id_locacion)
        let tabla = ""
        locacion.estantes.forEach((estante) => {
            tabla += '<tr onclick="AuditoriaConfigurarEstante('+id_locacion+', '+estante.id_estante+')">'+
                        '<td class="pd-short">'+
                            '<div class="panel_stand">'+
                                '<strong>Estante '+estante.nombre+'</strong>'+
                                '<p>'+estante.encargado.nombre+'</p>'+
                            '</div>'+
                        '</td>'+
                    '</tr>'
        })
        $("#auditoria-tabla-estantes tbody").html(tabla)   
        ConteoActualizarTablaLocaciones(id_locacion)
    }

    function AuditoriaCargarLocaciones() {
        loading(true, "Cargando usuarios disponibles...")
        let ruta = '{{ route('auditoria/buscar_locaciones', $inventario->id_almacen) }}'
        $.get(ruta, (response) => { this.auditoria_locaciones = response.data; loading(false)})
        .fail((error) => {toastr.error("Ocurrio un error"); loading(false)})
    }

    function AuditoriaConfigurarEstante(id_locacion, id_estante) {
        let locacion = this.auditoria_locaciones.find(element => element.id_locacion == id_locacion)
        let estante = locacion.estantes.find(element => element.id_estante == id_estante)
        $("#auditoria-modal-id-locacion").val(id_locacion)
        $("#auditoria-modal-id-estante").val(id_estante)
        if(estante.encargado.id_usuario)
            $('#auditoria-modal-encargado').val(estante.encargado.id_usuario).prop('selected', true);
        else
            $('#auditoria-modal-encargado').val(0).prop('selected', true);
        $('#auditoria-modal-estante').val(estante.nombre)
        $('#ModalAuditoria').modal("show")
    }

    function GuardarEstanteAuditoria() {
        let id_locacion = $("#auditoria-modal-id-locacion").val()
        let id_estante = $("#auditoria-modal-id-estante").val()
        let id_usuario = $("#auditoria-modal-encargado").val()
        let usuario = $( "#auditoria-modal-encargado option:selected").text()

        //SE VALIDA SI HAY CONTEOS ASIGNADOS A ESTE ESTANTE
        if(!ValidarExistenciaUsuarioConteo(id_estante) && id_usuario == 0){
            $("#alert-auditoria").html("No se puede cambiar el encargado de este estante a 'No asignado' debido a que hay un usuario asignado al conteo del mismo estante")
            $("#alert-auditoria").fadeIn()
            setTimeout(function() { $("#alert-auditoria").fadeOut() }, 10000);
            return false
        }
        this.auditoria_locaciones.forEach((locacion) => {
            let posicion = 0
            locacion.estantes.forEach((estante) => {
                if(estante.id_estante == id_estante){
                    estante.encargado.id_usuario = id_usuario
                    estante.encargado.nombre = usuario
                    locacion.estantes.splice(posicion, 1, estante)
                }
                posicion++
            })
        })
        AuditoriaActualizarTablaEstantes(id_locacion)
        $('#ModalAuditoria').modal("hide")
    }

    function ValidarExistenciaUsuarioAuditoria(id_estante) {
        let estante = null
        this.auditoria_locaciones.forEach((locacion) => {
            locacion.estantes.forEach((_estante) => {
                if(_estante.id_estante == id_estante) estante = _estante
            })
        })
        if(estante){
            return estante.encargado.id_usuario == 0 ? false : true 
        }else{
            return true
        }
    }

    function ValidarExistenciaUsuarioConteo(id_estante) {
        let estante = null
        this.conteo_locaciones.forEach((locacion) => {
            locacion.estantes.forEach((_estante) => {
                if(_estante.id_estante == id_estante) estante = _estante
            })
        })
        if(estante){
            return estante.encargados.find(element => element.id_usuario != 0) ? false : true 
        }else{
            return true
        }
    }


    function ConteoActualizarTablaLocaciones() {
        let tabla = ""
        let tmp_conteo_locaciones = []
        this.auditoria_locaciones.forEach((locacion) => {
            let agregar_locacion = true
            locacion.estantes.forEach((estante) => {
                if(agregar_locacion == true && estante.encargado.id_usuario != 0){
                    tabla += '<tr>'+
                                '<td id="td-locacion-conteo-'+locacion.id_locacion+'"'+
                                    'class="td-location-conteo"'+ 
                                    'onclick="ConteoEscogerLocacion('+locacion.id_locacion+')">'+
                                    '<strong>'+locacion.nombre+'</strong>'+
                                '</td>'+
                            '</tr>'
                    agregar_locacion = false
                }
                let posicion = locacion.estantes.indexOf(estante)
                let encargado_auditoria = locacion.estantes[posicion].encargado
                locacion.estantes[posicion].encargados = this.ConteoValidarExistenciaEncargados(estante)
                tmp_conteo_locaciones.push(locacion)
            })
        })

        this.conteo_locaciones = tmp_conteo_locaciones
        $("#conteo-tabla-locaciones tbody").html(tabla)
        $("#conteo-tabla-estantes tbody").html("")
    }

    function ConteoValidarExistenciaEncargados(estante) {
        let encargados = []
        for (var i = 1; i <= 3; i++) {
            let encargado = {
                'id_usuario' : 0,
                'nombre' : 'No asignado',
                'conteo' : i
            }

            this.conteo_locaciones.forEach((locacion) => {
                locacion.estantes.forEach((_estante) => {
                    if (_estante == estante) {
                        _estante.encargados.forEach((_encargado) => {
                            if(_encargado.conteo == i){
                                console.log("encontro igual ")
                                encargado = _encargado
                            }
                        })
                    }
                })
            })

            encargados.push(encargado)
        }
        

        return encargados
    }

    function ConteoEscogerLocacion(id_locacion) {
        $(".td-location-conteo").each(function(){ $(this).removeClass('td-active') });

        let td = document.getElementById("td-locacion-conteo-"+id_locacion)
        let is_active = td.classList.contains('td-active');
        if(is_active){
            $("#td-locacion-conteo-"+id_locacion).removeClass('td-active')
        }
        else{
            $("#td-locacion-conteo-"+id_locacion).addClass('td-active')
        }

        ConteoActualizarTablaEstantes(id_locacion)
    }

    function ConteoActualizarTablaEstantes(id_locacion) {
        let locacion = this.conteo_locaciones.find(element => element.id_locacion == id_locacion)
        let tabla = ""
        locacion.estantes.forEach((estante) => {
            let encargado = estante.encargados.find(element => element.conteo == this.conteo_actual)
            tabla += '<tr onclick="ConteoConfigurarEstante('+id_locacion+', '+estante.id_estante+')">'+
                        '<td class="pd-short">'+
                            '<div class="panel_stand">'+
                                '<strong>Estante '+estante.nombre+'</strong>'+
                                '<p>'+encargado.nombre+'</p>'+
                            '</div>'+
                        '</td>'+
                    '</tr>'
        })
        $("#conteo-tabla-estantes tbody").html(tabla)   
    }

    function EscogerConteo(conteo) {
        this.conteo_actual = conteo
        $(".btn-conteo").each(function(){ $(this).removeClass('btn-active') });
        $("#btn-conteo-"+conteo).addClass('btn-active')
        $(".td-location-conteo").each(function(){ $(this).removeClass('td-active') });
        $("#conteo-tabla-estantes tbody").html("") 
    }

    function ConteoConfigurarEstante(id_locacion, id_estante) {
        let locacion = this.conteo_locaciones.find(element => element.id_locacion == id_locacion)
        let estante = locacion.estantes.find(element => element.id_estante == id_estante)
        let encargado = estante.encargados.find(element => element.conteo == this.conteo_actual)
        $("#conteo-modal-id-locacion").val(id_locacion)
        $("#conteo-modal-id-estante").val(id_estante)
        if(encargado.id_usuario != 0)
            $('#conteo-modal-encargado').val(encargado.id_usuario).prop('selected', true);
        else
            $('#conteo-modal-encargado').val(0).prop('selected', true);
        $('#conteo-modal-estante').val(estante.nombre)
        $('#ModalConteo').modal("show")
    }

    function GuardarEstanteConteo() {
        let id_locacion = $("#conteo-modal-id-locacion").val()
        let id_estante = $("#conteo-modal-id-estante").val()
        let id_usuario = $("#conteo-modal-encargado").val()
        let usuario = $( "#conteo-modal-encargado option:selected").text()
        //SE VALIDA SI HAY UN USUARIO ASIGNADO EN LA AUDITORIA PREVIAMENTE
        if(!ValidarExistenciaUsuarioAuditoria(id_estante) && id_usuario != 0){
            $("#alert-conteo").html("No se puede asignar un encargado a este estante debido a que no hay un usuario encargado en la auditoria del mismo estante")
            $("#alert-conteo").fadeIn()
            setTimeout(function() { $("#alert-conteo").fadeOut() }, 10000);
            return false
        }

        //SE VALIDA SI EL USUARIO ESCOJIDO YA HIZO CONTEO EN EL ESTANTE
        if(!ValidarDuplicidadUsuarioConteo(id_usuario, id_estante) && id_usuario != 0){
            $("#alert-conteo").html("No se puede asignar el encargado a este estante debido a que ya fue asignado a este estante en otro conteo")
            $("#alert-conteo").fadeIn()
            setTimeout(function() { $("#alert-conteo").fadeOut() }, 10000);
            return false
        }

        this.conteo_locaciones.forEach((locacion) => {
            let posicion = 0
            locacion.estantes.forEach((estante) => {
                if(estante.id_estante == id_estante){
                    estante.encargados.forEach((encargado) => {
                        if(encargado.conteo == this.conteo_actual){
                            encargado.id_usuario = id_usuario
                            encargado.nombre = usuario
                            locacion.estantes.splice(posicion, 1, estante)
                        }
                       
                    })
                    
                }
                posicion++
            })
        })
        ConteoActualizarTablaEstantes(id_locacion)
        $('#ModalConteo').modal("hide")
    }

    function ValidarDuplicidadUsuarioConteo(id_usuario, id_estante) {
        //SOLO SE VALIDA DUPLICIDAD DE USUARIOS N EL PRIMER Y SEGUNDO CONTEO
        if(this.conteo_actual == 1 || this.conteo_actual == 2){
            let estante = null
            this.conteo_locaciones.forEach((locacion) => {
                locacion.estantes.forEach((_estante) => {
                    if(_estante.id_estante == id_estante) estante = _estante
                })
            })
            console.log("kjhfskjd")
            if(estante != null){
                console.log(estante)
                let conteo_comparacion = this.conteo_actual == 1 ? 2 : 1
                return estante.encargados.find(element => element.id_usuario == id_usuario && element.conteo == conteo_comparacion) ? false : true 
            }else{
                return true
            }
        }else{
            return true
        }
        return true
    }


    document.addEventListener("DOMContentLoaded", function(event) {
        AuditoriaCargarLocaciones()
    });
</script>
@endsection

