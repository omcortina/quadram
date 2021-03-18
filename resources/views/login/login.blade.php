<!--
    =========================================================
    * Argon Dashboard - v1.2.0
    =========================================================
    * Product Page: https://www.creative-tim.com/product/argon-dashboard
    
    * Copyright  Creative Tim (http://www.creative-tim.com)
    * Coded by www.creative-tim.com
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    <!DOCTYPE html>
    <html>
    
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
      <meta name="author" content="Creative Tim">
      <title>Argon Dashboard - Free Dashboard for Bootstrap 4</title>
      <!-- Favicon -->
      <link rel="icon" href="{{asset('design/assets/img/brand/favicon.png')}}" type="image/png">
      <!-- Fonts -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
      <!-- Icons -->
      <link rel="stylesheet" href="{{asset('design/assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
      <link rel="stylesheet" href="{{asset('design/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
      <!-- Argon CSS -->
      <link rel="stylesheet" href="{{asset('design/assets/css/argon.css?v=1.2.0')}}" type="text/css">
    </head>
    
    <body class="bg-default">
        
        <!-- Main content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header bg-gradient-primary py-7 py-lg-6 pt-lg-9" style="background: #172b4d  !important">
                
            </div>
            <!-- Page content -->
            <div class="container mt--8 pb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div class="card bg-secondary border-0 mb-0">
                            @if (session('mensaje_login'))
                                <div id="msg" class="alert alert-danger" style="border-bottom-right-radius: 0px !important; border-bottom-left-radius: 0px !important;">
                                    <li>{{session('mensaje_login')}}</li>
                                </div>

                                <script>
                                    setTimeout(function(){ $('#msg').fadeOut() }, 4000);
                                </script>
                            @endif
                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <medium style="font-size: 22px"><b>Bienvenido</b></medium>
                                </div>
                                {{ Form::open(array('method' => 'post', 'id' => 'form-validar', 'route' => 'usuario/validar')) }}
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Usuario" type="text" name="nombre_usuario" id="nombre_usuario">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Contraseña" type="password" name="password" id="passsword">
                                    </div>
                                </div>
                                <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">Ingresar</button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="py-5" id="footer-main">
            <div class="container">
                <div class="row align-items-center justify-content-xl-between">
                    <div class="col-xl-12">
                        <div class="copyright text-center text-xl-left text-muted">
                            <center>
                                &copy; 2020 <a href="#" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Argon Scripts -->
        <!-- Core -->
        <script src="{{asset('design/assets/vendor/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/js-cookie/js.cookie.js')}}"></script>
        <script src="{{asset('design/assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js')}}"></script>
        <script src="{{asset('design/assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js')}}"></script>
        <!-- Argon JS -->
        <script src="{{asset('design/assets/js/argon.js?v=1.2.0')}}"></script>
    </body>
</html>