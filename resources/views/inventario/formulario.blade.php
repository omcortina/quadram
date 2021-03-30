@extends('layouts.principal')
@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Información</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Inventario</a></li>
            <li class="breadcrumb-item active" aria-current="page">Información inventario</li>
        </ol>
        </nav>
    </div>
    <div class="col-lg-6 col-5 text-right">
      <span id="inventario_estado" class="btn btn-sm btn-success" onclick="EstablecerEstado()">Activo</a>
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
                            <label><b>Almacen</b></label>
                            <select id="inventario_id_almacen" class="form-control">
                                @foreach($almacenes as $item)
                                <option value="{{ $item->id_almacen }}"> {{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Fecha inicio</b></label>
                            <input id="inventario_fecha_inicio" class="form-control" type="datetime-local" name="">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Fecha fin</b></label>
                            <input id="inventario_fecha_fin" class="form-control" type="datetime-local"  name="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <nav>
                          <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Auditorias</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>
                          </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">...</div>
                          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
                          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
<script>
    var inventario = {
        'id_inventario' : null,
        'id_almacen' : null,
        'fecha_inicio' : null,
        'fecha_fin' : null,
        'estado' : 1
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
</script>
@endsection  