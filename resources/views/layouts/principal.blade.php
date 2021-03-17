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
      <!-- Argon CSS -->
      <link rel="stylesheet" href="{{asset('design/assets/css/argon.css?v=1.2.0')}}" type="text/css">
      <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
      <script src="{{asset('design/assets/vendor/jquery/dist/jquery.min.js')}}"></script>
      <script src="{{asset('design/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
      <script src="{{asset('design/assets/vendor/js-cookie/js.cookie.js')}}"></script>
      <script src="{{asset('design/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
      <script src="{{asset('design/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
      <!-- Optional JS -->
      <script src="{{asset('design/assets/vendor/chart.js/dist/Chart.min.js')}}"></script>
      <script src="{{asset('design/assets/vendor/chart.js/dist/Chart.extension.js')}}"></script>
      <!-- Argon JS -->
      <script src="{{asset('design/assets/js/argon.js?v=1.2.0')}}"></script>
      <script src="{{ asset('js/toastr.min.js') }}"></script>
    </head>
    
    <body>
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
      
    </body>
    
    </html>
    