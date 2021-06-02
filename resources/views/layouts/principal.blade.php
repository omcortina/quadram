    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
        <meta name="author" content="Creative Tim">
        <title>Quadram</title>
        <!-- Favicon -->
        <link rel="icon" href="{{asset('design/assets/img/brand/favicon.png')}}" type="image/png">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <!-- Icons -->
        <link rel="stylesheet" href="{{asset('design/assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
        <link rel="stylesheet" href="{{asset('design/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="{{asset('design/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('design/assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('design/assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
        <!-- Argon CSS -->
        <link rel="stylesheet" href="{{asset('design/assets/css/argon.css?v=1.1.0')}}" type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="{{asset('loader/css-loader.css') }}">
        <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">

        <script src="{{asset('design/assets/vendor/jquery/dist/jquery.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        

        <script>
            function loading(open = true,message = "Por favor espere...", timeout = null) {
            if(timeout){
                $("#loader").html('<div class="loader loader-default is-active" data-text="'+message+'"></div>')
                setTimeout(function() {
                $("#loader").html('')
                }, timeout);
                return true
            }
            if(open) $("#loader").html('<div class="loader loader-default is-active" data-text="'+message+'"></div>')
            if(!open) $("#loader").html('')
            }

            function setFilter(id_input, id_bodytable) {
            $('#'+id_input).keyup(function () {
                var rex = new RegExp($(this).val(), 'i');
                $('#'+id_bodytable+' tr').hide();
                $('#'+id_bodytable+' tr').filter(function () {
                    return rex.test($(this).text());
                }).show();

            })
            }
        </script>

        <style>
            a{
            cursor: pointer;
            }
            .icons svg{
            margin-right: 8px;
            width: 18px;
            height: 18px;
            color: #5e72e4 !important;
            cursor: pointer;
            }

            .icons-btn svg{
                margin-right: 5px;
                width: 18px;
                height: 18px;
                color: #fff !important;
                cursor: pointer;
            }

            .avatar img {
                width: 100%;
                border-radius: .375rem;
                width: 40px;
                height: 40px;
            }

            .avatar{
            background-color: transparent;
            }

            .btn-danger{
                color: white !important;
            }
            .select2-container .select2-selection--single {
                height: auto !important;
                padding-top: 8px !important;
            }
        </style>
    </head>

    <body>
        <div id="loader"></div>
        @include('layouts.menu')
        <!-- Main content -->
        <div class="main-content" id="panel">
            <!-- Topnav -->
            @include('layouts.navbar')
            <!-- Header -->

            <div class="header bg-primary pb-6">
            <div class="container-fluid">
                <div class="header-body">
                @yield('breadcumb')
                {{--@include('layouts.estadisticas')--}}
                </div>
            </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--6">
                @yield('contenido')

            </div>
        </div>
      <!-- Argon Scripts -->
      <!-- Core -->
        


        <script src="{{asset('design/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/js-cookie/js.cookie.js')}}"></script>
        <script src="{{asset('design/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>

        <!-- Argon JS -->
        <script src="{{ asset('js/toastr.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

        <!-- Optional JS -->
        <script src="{{asset('design/assets/vendor/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/datatables.net-select/js/dataTables.select.min.js')}}"></script>
        <!-- Argon JS -->
        <script src="{{asset('design/assets/js/argon.js?v=1.1.0')}}"></script>
        <!-- Demo JS - remove this in your project -->
        <script src="{{asset('design/assets/js/demo.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}">
        <script src="{{asset('js/select2.min.js')}}"></script>
         
        <script>
            setTimeout(()=>{ feather.replace() }, 1000)
            $(document).ready(function() {
                $('.my-select2').select2({
                  tags: "true",
                  placeholder: "Seleccione...",
                  allowClear: true
                });
            });
        </script>
    </body>

</html>
