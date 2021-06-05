@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Listado</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Conteo</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado</li>
        </ol>
        </nav>
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
                <th scope="col">Usuario registra</th>
                <th scope="col">Estado</th>
                <th scope="col"><center>Acciones</center></th>
              </tr>
            </thead>
            <tbody id="bodytable_conteos">
              @foreach($conteos as $conteo)
                <tr>
                  <td>{{ $conteo->auditoria->inventario->almacen->nombre }}</td>
                  <td>{{ date('d/m/Y H:i', strtotime($conteo->fecha_inicio)) }}</td>
                  <td>{{ date('d/m/Y H:i', strtotime($conteo->fecha_fin)) }}</td>
                  <td>{{ $conteo->usuario->nombre_completo() }}</td>
                  <td>
                    @if ($conteo->estado == 1)
                        <span style="color: #2dce89">Activo</span>
                    @else
                        <span style="color: #f5365c">Inactivo</span>
                    @endif</td>
                  <td>
                    <center>
                        <a class="icons" href="{{ route('conteo/seguimiento/transcripcion') }}?conteo={{ $conteo->id_conteo }}" title="Trascribir"><i data-feather="truck"></i></a>
                    </center>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
@endsection
@csrf

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        setFilter("filtro", "bodytable_conteos")
    });

</script>
