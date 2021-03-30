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
@endsection

@section('contenido')
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
                <th scope="col">Usuario</th>
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

<script>
    var inventarios = []
    var inventario = {
        'id_inventario' : null,
        'id_almacen' : null,
        'almacen' : null,
        'fecha_inicio' : null,
        'fecha_fin' : null,
        'estado' : 1
    }


    function CerrarModal() {
        $('#ModalInventario').modal('hide')
    }

    function AbrirModal(id_almacen = null) {
        $('#ModalInventario').modal('show')
    }

    function BuscarInventarios() {
      loading(true, 'Consultando inventarios...')
       $.get('{{ route("inventario/obtener_listado") }}', (response) => {
          this.inventarios = response.inventarios
          ActualizarTabla()
          loading(false)
       })
    }

    function ActualizarTabla() {
      let tabla = ""
      this.inventarios.forEach((item) => {
          tabla += '<tr>'+
                      '<td>'+item.almacen.nombre+'</td>'+
                      '<td>'+item.fecha_inicio+'</td>'+
                      '<td>'+item.fecha_fin+'</td>'+
                      '<td>'+item.usuario.nombres+' - '+item.usuario.documento+'</td>'+
                      '<td><span class="text-'+EstadoColor(item.estado)+'">'+EstadoTexto(item.estado)+'</span></td>'+
                      '<td><center>'+
                        '<a onclick="Editar('+item.id_inventario+')">Editar</a>'+
                      '</center></td>'+
                   '</tr>'
      })
      $("#bodytable_inventarios").html(tabla)
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

    function EstadoColor(estado) {
      return estado == 1 ? 'success' : 'danger'
    }

    function EstadoTexto(estado) {
      return estado == 1 ? 'Activo' : 'Inactivo'
    }


    setTimeout(()=>{
      $(document).ready(()=>{
        BuscarInventarios()
      })
    },1000)
</script>