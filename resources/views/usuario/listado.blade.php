@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Listado</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Listado</li>
        </ol>
        </nav>
    </div>

    <div class="col-lg-6 col-5 text-right">
      <a href="{{ route('usuario/formulario') }}" class="btn btn-sm btn-neutral">+ Nuevo usuario</a>
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
                <th scope="col">Identificacion</th>
                <th scope="col">Nombres</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Telefono</th>
                <th scope="col">Tipo usuario</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($usuarios as $usuario)
                <tr>
                  <td>{{ $usuario->documento }}</td>
                  <td>{{ $usuario->nombres }}</td>
                  <td>{{ $usuario->apellidos }}</td>
                  <td>{{ $usuario->telefono }}</td>
                  <td>{{ $usuario->tipo->nombre }}</td>
                  <td><center>
                    <a href="{{ route('usuario/formulario')."?id=".$usuario->id_usuario }}">Editar</a>
                  </center></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
@endsection