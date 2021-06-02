@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-8 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Gestionar productos</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('producto/listado') }}">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Gestion de producto</li>
        </ol>
        </nav>
    </div>
</div>

@endsection

@section('contenido')
@if(isset($mensaje) and $mensaje != null)
    <script>
      toastr.error("{{ $mensaje }}", "Error");
    </script>
@endif
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header border-0">
                 <form id="form-producto"
                       method="POST" 
                       enctype="multipart/form-data">
                  @csrf
                   <div class="row">
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Codigo</label>
                         <input type="text" value="{{ $producto->codigo }}" class="form-control" name="codigo">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Nombre</label>
                         <input type="text" value="{{ $producto->nombre }}" class="form-control" name="nombre">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Descripción</label>
                         <input type="text" value="{{ $producto->descripcion }}" class="form-control" name="descripcion">
                       </div>
                     </div>
                   </div> 

                   <div class="row">
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Codigo de barras</label>
                         <input type="text" value="{{ $producto->codigo_barras }}" class="form-control" name="codigo_barras">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Cantidad a la mano</label>
                         <input type="text" value="{{ $producto->cantidad_mano }}" class="form-control" name="cantidad_mano">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Precio de venta</label>
                         <input type="text" value="{{ $producto->precio_venta }}" class="form-control" name="precio_venta">
                       </div>
                     </div>
                   </div>

                   <div class="row">
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Unidad de medida</label>
                         <input type="text" value="{{ $producto->unidad_medida }}" class="form-control" name="unidad_medida">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Codigo invima</label>
                         <input type="text" value="{{ $producto->codigo_invima }}" class="form-control" name="codigo_invima">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Fecha Venc Invima</label>
                         <input type="date" value="{{ $producto->fecha_vencimiento_invima }}" class="form-control" name="fecha_vencimiento_invima">
                       </div>
                     </div>
                   </div>

                   <div class="row">
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Codigo ATC</label>
                         <input type="text" value="{{ $producto->codigo_atc }}" class="form-control" name="codigo_atc">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Codigo CUM</label>
                         <input type="text" value="{{ $producto->codigo_cum }}" class="form-control" name="codigo_cum">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Presentación</label>
                         <input type="text" value="{{ $producto->presentacion }}" class="form-control" name="presentacion">
                       </div>
                     </div>
                   </div>

                   <div class="row">
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Marca</label>
                         <input type="text" value="{{ $producto->marca }}" class="form-control" name="marca">
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Estado</label>
                         <select class="custom-select2 form-control" name="estado" id="estado" style="width: 100%; height: 45px;">
                          <option @if ($producto->estado == 1) selected @endif value="1">Activo</option>
                          <option @if ($producto->estado == 0) selected @endif value="0">Inactivo</option>
                          </select>
                       </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                         <label>Creación</label>
                         <input disabled type="text" value="{{ $producto->created_at ? $producto->created_at : "No definida" }}" class="form-control">
                       </div>
                     </div>
                   </div>
                   <div class="row">
                     <div class="col-sm-12">
                       <center>
                         <button type="submit" class="btn btn-primary">Guardar cambios</button>
                       </center>
                     </div>
                   </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
@endsection
<script>
   
        
</script>