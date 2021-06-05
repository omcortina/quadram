@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Listado</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado</li>
        </ol>
        </nav>
    </div>

    <div class="col-lg-6 col-5 text-right">
      <a href="{{ route('producto/gestion') }}" class="btn btn-sm btn-warning">Nuevo producto</a>
      <a href="{{ route('producto/cargar_archivo') }}" class="btn btn-sm btn-success">Importar productos</a>
    </div>
</div>
@endsection

@section('contenido')
@if(session('message'))
  <script>
    toastr.success("{{ session('message') }}", "Información");
  </script>
@endif
<div class="row">
  <div class="col-sm-12">
      <div class="card">
        <div class="table-responsive">
          <!-- Projects table -->
          <table class="table align-items-center table-flush" id="tabla_productos">
            <thead class="thead-light">
              <tr>
                <th scope="col">Codigo</th>
                <th scope="col">Nombre</th>
                <th scope="col">Cod. Barras</th>
                <th scope="col">Descipción</th>
                <th scope="col"><center>Estado</center></th>
                <th scope="col"><center>Acciones</center></th>
              </tr>
            </thead>
            <tbody>
              @foreach($productos as $producto)
                <tr>
                  <td>{{ $producto->codigo }}</td>
                  <td>{{ Str::length($producto->nombre) > 50 ? Str::limit($producto->nombre, 47, '...') : $producto->nombre }}</td>
                  <td>
                    @if ($producto->codigo_barras != null)
                      {{ $producto->codigo_barras }}
                    @else
                      No definido
                    @endif
                  </td>
                  <td>
                    @if ($producto->descripcion != null)
                      {{ $producto->descripcion }}
                    @else
                      Sin descripción
                    @endif
                  </td>
                  <td style="color: #2dce89"><center>{{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}</center></td>
                  <td>
                    <center>
                    <a class="icons" title="Editar" href="{{ config('global.servidor') }}/producto/gestion?producto={{ $producto->id_producto }}"><i data-feather="edit"></i></a>
                    </center>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="row"style="margin-left: 10px; margin-top: 20px;">
          <div class="col-sm-12">
            <center>
              {{ $productos->links('pagination::bootstrap-4') }}
            </center>
          </div>
        </div>
      </div>
  </div>
</div>
@endsection
