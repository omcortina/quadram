@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Listado</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Inventarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado</li>
        </ol>
        </nav>
    </div>

    <div class="col-lg-6 col-5 text-right">
      <a onclick="AbrirModal()" class="btn btn-sm btn-neutral">+ Nuevo inventario</a>
    </div>
</div>
<div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-6">
    <input id="filtro" type="text" class="form-control pull-right text-center" placeholder="Consulta aqui" name="">
  </div>
</div>
@endsection

@section('contenido')

<br>
<div class="row">
  <div class="col-sm-12">
      <div class="card">
        <div class="table-responsive">
          <!-- Projects table -->
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">Almacen</th>
                <th scope="col">Fecha Inicio</th>
                <th scope="col">Fecha Fin</th>
                <th scope="col">Supervisor</th>
                <th scope="col">Auditoria</th>
                <th scope="col">Conteo</th>
                <th scope="col">Conteo Actual</th>
                <th scope="col">Estado</th>
                <th scope="col"><center>Acciones</center></th>
              </tr>
            </thead>
            <tbody id="bodytable_inventarios">
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
@endsection

<div class="modal" tabindex="-1" id="ModalInventario">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información del inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="CerrarModal()"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Almacen</label>
                         <select id="inventario_id_almacen" class="form-control">
                                @foreach($almacenes as $item)
                                <option value="{{ $item->id_almacen }}"> {{ $item->nombre }}</option>
                                @endforeach
                         </select>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Fecha inicio</label>
                        <input id="inventario_fecha_inicio" class="form-control" type="datetime-local" name="">
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label><b>Fecha fin</b></label>
                        <input id="inventario_fecha_fin" class="form-control" type="datetime-local"  name="">
                    </div>
                </div>
                <div class="col-sm-12 text-right">
                    <span id="inventario_estado" class="btn btn-sm btn-success" onclick="EstablecerEstado()">Activo</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="CerrarModal()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="Guardar()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@csrf

<script>

    var inventarios = []
    var inventario = {}

    function ReiniciarInventario() {
      inventario = {
        'id_inventario' : null,
        'id_almacen' : null,
        'almacen' : null,
        'fecha_inicio' : null,
        'fecha_fin' : null,
        'estado' : 1
      }
    }

    function CerrarModal() {
      $('#ModalInventario').modal('hide')
    }

    function AbrirModal(id_inventario = null) {
      ReiniciarInventario()
      LimpiarModal()
      if(id_inventario != null){
        let inv = this.inventarios.find(item => item.id_inventario == id_inventario)
        this.inventario.id_inventario = inv.id_inventario
        $('#inventario_id_almacen').val(inv.id_almacen).prop('selected', true);
        $('#inventario_fecha_inicio').val(inv.fecha_inicio.replace(" ", "T"))
        $('#inventario_fecha_fin').val(inv.fecha_fin.replace(" ", "T"))
        EstablecerEstadoActual(inv.estado)
      }
      $('#ModalInventario').modal('show')
    }

    function LimpiarModal() {
      $('#inventario_fecha_inicio').val(null)
      $('#inventario_fecha_fin').val(null)
      $("#inventario_estado").addClass("btn-success")
      $("#inventario_estado").removeClass("btn-danger")
      $("#inventario_estado").html("Activo")
    }

    function BuscarInventarios(_loading = true) {
      if(_loading) loading(true, 'Consultando inventarios...')
       $.get('{{ route("inventario/obtener_listado") }}', (response) => {
          this.inventarios = response.inventarios
          ActualizarTabla()
          loading(false)
       })
    }

    function Guardar() {
      if($("#inventario_fecha_inicio").val() == null || $("#inventario_fecha_fin").val() == null){
        alert("Por favor debe suministrar las fechas del invntario")
        return;
      }

      let url = '{{ route("inventario/guardar") }}'
      this.inventario.id_almacen = $("#inventario_id_almacen").val()
      this.inventario.fecha_inicio = $("#inventario_fecha_inicio").val()
      this.inventario.fecha_fin = $("#inventario_fecha_fin").val()
      let _token = $('input[name=_token]')[0].value
      let request = {
        '_token' : _token,
        'inventario' : this.inventario
      }

      $.post(url, request, (response) =>{
          
          if(!response.error){
            toastr.success(response.mensaje)
            this.BuscarInventarios(false)
            this.CerrarModal()
          }else{
            toastr.error(response.mensaje)
          }
      })
      .fail((error) => {
          toastr.error("Ocurrio un error")
      })

    }

    function ActualizarTabla() {
      let tabla = ""
      this.inventarios.forEach((item) => {
        let ruta_auditorias = "{{ config('global.servidor') }}/auditoria/listado/"+item.id_inventario
          tabla += '<tr>'+
                      '<td>'+item.almacen.nombre+'</td>'+
                      '<td>'+item.fecha_inicio+'</td>'+
                      '<td>'+item.fecha_fin+'</td>'+
                      '<td>'+item.usuario.nombres+' - '+item.usuario.documento+'</td>'+
                      '<td>'+item.estado_auditoria+'</td>'+
                      '<td>'+item.estado_conteo+'</td>'+
                      '<td>'+item.conteo_actual+'</td>'+
                      '<td><span class="text-'+EstadoColor(item.estado)+'">'+EstadoTexto(item.estado)+'</span></td>'+
                      '<td><center>'+
                        '<a class="icons" title="Editar" onclick="AbrirModal('+item.id_inventario+')"><i data-feather="edit"></i></a>'+
                        '<a class="icons" title="Auditorias" href="'+ruta_auditorias+'"><i data-feather="clipboard"></i></a>'
          if(item.conteo_tiene_seguimientos){
            let ruta_conteo = "{{ config('global.servidor') }}/inventario/seguimiento-general/"+item.id_inventario
            tabla += '<a class="icons" title="Informe de inventario" href="'+ruta_conteo+'"><i  data-feather="bar-chart-2"></i></a>'
          }
         tabla +=     '</center></td>'+
                   '</tr>'
      })
      $("#bodytable_inventarios").html(tabla)
      feather.replace() //PARA VISUALIZAR LOS ICONOS
    }

    function EstablecerEstado() {
        if(this.inventario.estado == 1) {
            $("#inventario_estado").removeClass("btn-success")
            $("#inventario_estado").addClass("btn-danger")
            $("#inventario_estado").html("Inactivo")
            this.inventario.estado = 0
       
        }else{

            $("#inventario_estado").addClass("btn-success")
            $("#inventario_estado").removeClass("btn-danger")
            $("#inventario_estado").html("Activo")
            this.inventario.estado = 1
        }
    }

    function EstablecerEstadoActual(estado) {
        if(estado == 0) {
            $("#inventario_estado").removeClass("btn-success")
            $("#inventario_estado").addClass("btn-danger")
            $("#inventario_estado").html("Inactivo")
        }else{
            $("#inventario_estado").addClass("btn-success")
            $("#inventario_estado").removeClass("btn-danger")
            $("#inventario_estado").html("Activo")
        }
    }

    function EstadoColor(estado) {
      return estado == 1 ? 'success' : 'danger'
    }

    function EstadoTexto(estado) {
      return estado == 1 ? 'Activo' : 'Inactivo'
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        ReiniciarInventario()
        BuscarInventarios()
        setFilter("filtro", "bodytable_inventarios")
    });
    
</script>