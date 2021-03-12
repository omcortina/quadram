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
      <!-- Page plugins -->
      <!-- Argon CSS -->
      <link rel="stylesheet" href="{{asset('design/assets/css/argon.css?v=1.2.0')}}" type="text/css">
    </head>
    
    <body>
      @include('layouts.menu')
      <!-- Main content -->
      <div class="main-content" id="panel">
        <!-- Topnav -->
        <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
          <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Search form -->
              <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                <div class="form-group mb-0">
                  <div class="input-group input-group-alternative input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input class="form-control" placeholder="Buscar" type="text">
                  </div>
                </div>
                <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </form>
              <!-- Navbar links -->
              <ul class="navbar-nav align-items-center  ml-md-auto ">
                <li class="nav-item d-xl-none">
                  <!-- Sidenav toggler -->
                  <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                      <i class="sidenav-toggler-line"></i>
                      <i class="sidenav-toggler-line"></i>
                      <i class="sidenav-toggler-line"></i>
                    </div>
                  </div>
                </li>
                <li class="nav-item d-sm-none">
                  <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                    <i class="ni ni-zoom-split-in"></i>
                  </a>
                </li>
                @include('layouts.notificacion')
                <li class="nav-item dropdown">
                  <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ni ni-ungroup"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default  dropdown-menu-right ">
                    <div class="row shortcuts px-4">
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                          <i class="ni ni-calendar-grid-58"></i>
                        </span>
                        <small>Calendar</small>
                      </a>
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                          <i class="ni ni-email-83"></i>
                        </span>
                        <small>Email</small>
                      </a>
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                          <i class="ni ni-credit-card"></i>
                        </span>
                        <small>Payments</small>
                      </a>
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                          <i class="ni ni-books"></i>
                        </span>
                        <small>Reports</small>
                      </a>
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                          <i class="ni ni-pin-3"></i>
                        </span>
                        <small>Maps</small>
                      </a>
                      <a href="#!" class="col-4 shortcut-item">
                        <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                          <i class="ni ni-basket"></i>
                        </span>
                        <small>Shop</small>
                      </a>
                    </div>
                  </div>
                </li>
              </ul>
              <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                <li class="nav-item dropdown">
                  <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                      <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{asset('design/assets/img/theme/team-4.jpg')}}">
                      </span>
                      <div class="media-body  ml-2  d-none d-lg-block">
                        <span class="mb-0 text-sm  font-weight-bold">John Snow</span>
                      </div>
                    </div>
                  </a>
                  <div class="dropdown-menu  dropdown-menu-right ">
                    <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Bienvenido!</h6>
                    </div>
                    
                    <a href="#!" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>Mi perfil</span>
                    </a>

                    <a href="#!" class="dropdown-item">
                    <i class="ni ni-settings-gear-65"></i>
                    <span>Ajustes</span>
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    <a href="#!" class="dropdown-item">
                    <i class="ni ni-user-run"></i>
                    <span>Salir</span>
                    </a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- Header -->

        <div class="header bg-primary pb-6">
          <div class="container-fluid">
            <div class="header-body">
              @include('layouts.breadcumb')
              {{--@include('layouts.estadisticas')--}}
            </div>
          </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--6">
            @yield('contenido')
          <!-- Footer -->
          {{--  <footer class="footer pt-0">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-lg-6">
                <div class="copyright text-center  text-lg-left  text-muted">
                  &copy; 2020 <a href="https://www.creative-tim.com" class="font-weight-bold ml-1" target="_blank">Creative Tim</a>
                </div>
              </div>
              <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
                  </li>
                  <li class="nav-item">
                    <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
                  </li>
                  <li class="nav-item">
                    <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
                  </li>
                </ul>
              </div>
            </div>
          </footer>  --}}
        </div>
      </div>
      <!-- Argon Scripts -->
      <!-- Core -->
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
    </body>
    
    </html>
    