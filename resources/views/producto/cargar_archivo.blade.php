@extends('layouts.principal')

@section('breadcumb')
<div class="row align-items-center py-4">
    <div class="col-lg-8 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">Importar productos</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-user"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('producto/listado') }}">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Importar productos</li>
        </ol>
        </nav>
    </div>
</div>
<style type="text/css">
    .file-upload {
      background-color: #ffffff;
      width: 300px;
      height: 200px;
      padding: 20px;
      margin:0;
    }
    .div_img{
      width: 240px;
      height: 240px;
      margin-top:20px;
      background-size: cover;
      border-radius: 5px;
    }
    .encima:hover{
      cursor: pointer;
      background-color: #000000 !important;
      position: absolute;
    }

    .file-upload-btn {
      width: 100%;
      margin: 0;
      color: #fff;
      background: #f44336;
      border: none;
      padding: 10px;
      border-radius: 4px;
      border-bottom: 4px solid #15824B;
      transition: all .2s ease;
      outline: none;
      text-transform: uppercase;
      font-weight: 700;
    }




    .file-upload-btn:hover {
      background: #1AA059;
      color: #ffffff;
      transition: all .2s ease;
      cursor: pointer;
    }

    .file-upload-btn:active {
      border: 0;
      transition: all .2s ease;
    }

    .file-upload-content {
      display: none;
      text-align: center;
    }

    .file-upload-input {
      position: absolute;
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      outline: none;
      opacity: 0;
      cursor: pointer;
    }

    .image-upload-wrap {
      margin-top: 0px;
      border: 4px dashed #f44336;
      position: relative;
    }

    .image-dropping,
    .image-upload-wrap:hover {
      background-color: #f44336;
      border: 4px dashed #ffffff;
    }

    .image-dropping,
    .image-upload-wrap:hover  .drag-text b{
      color: white !important;
    }

    .image-title-wrap {
      padding: 0 15px 15px 15px;
      color: #222;
    }

    .drag-text {
      margin:110px 0px 110px 0px;
      text-align: center;
    }

    .drag-text h3 {
      font-weight: 100;
      text-transform: uppercase;
      color: #15824B;
      padding: 60px 0;
    }

    .file-upload-image {
      max-height: 200px;
      max-width: 200px;
      margin: auto;
      padding: 20px;
    }

    .remove-image {
      width: 200px;
      margin: 0;
      color: #fff;
      background: #cd4535;
      border: none;
      padding: 10px;
      border-radius: 4px;
      border-bottom: 4px solid #b02818;
      transition: all .2s ease;
      outline: none;
      text-transform: uppercase;
      font-weight: 700;
    }

    .remove-image:hover {
      background: #c13b2a;
      color: #ffffff;
      transition: all .2s ease;
      cursor: pointer;
    }

    .remove-image:active {
      border: 0;
      transition: all .2s ease;
    }
</style>
@endsection

@section('contenido')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header border-0">
                     <form id="form-cargar-producto"
                           action="{{ route('producto/importar_excel') }}" 
                           method="POST" 
                           enctype="multipart/form-data">
                         @csrf
                            
                            <p>Por favor seleccione un archivo excel (.xls) para importar al sistemas un listado de productos</p>
                            @if(isset($mensaje) and $mensaje != null)
                                <div id="alert" 
                                @if ($error) class="alert alert-danger" 
                                @else class="alert alert-success" 
                                @endif>
                                    {{ $mensaje }}
                                    @php $mensaje = null; @endphp
                                </div>
                                <script>setTimeout(()=>{$("#alert").fadeOut()},5000)</script>
                            @endif
                        <div class="image-upload-wrap">
                          <input class="file-upload-input" name="file" type="file" id="file" accept=".xlsx, .xls, .csv">
                          <div class="drag-text" style="margin: 20px 0px 20px 0px;">
                            <b class="drag-text" id="name_file">Selecciona o arrastra tu archivo</b>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <center>
                                    <button id="btn" type="button" class="btn btn-success" onclick="ValidarArchivo()">Importar Productos</button>
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
    setTimeout(()=>{
        $("#file").on('change',function(){
            if ($("#file").val() != "") {
                document.getElementById('name_file').innerHTML = "Archivo cargado - "+this.files[0].name 
            }else{
                document.getElementById('name_file').innerHTML = "Ningun archivo seleccionado"
                setTimeout(()=>{
                    document.getElementById('name_file').innerHTML = "Selecciona o arrastra tu archivo"
                }, 3000)
            }
        });
        console.log("Change file configured")
    }, 2000)  

    function ValidarArchivo() {
        if ($("#file").val() == "") {
            alert("Por favor, seleccione un archivo valido")
        }
        else{
            $("#btn").prop("disabled", true)
            $("#form-cargar-producto").submit()
        }
            
    }  
   
        
</script>