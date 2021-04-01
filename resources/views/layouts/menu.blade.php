<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
            <img src="{{asset('design/assets/img/brand/blue.png')}}" class="navbar-brand-img" alt="...">
        </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('producto/listado') }}">
                        <i class="ni ni-books text-primary"></i>
                        <span class="nav-link-text">Productos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuario/listado') }}">
                        <i class="ni ni-single-02 text-primary"></i>
                        <span class="nav-link-text">Usuarios</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('almacen/ver_listado') }}">
                        <i class="ni ni-tv-2 text-primary"></i>
                        <span class="nav-link-text">Almacen</span>
                        </a>
                    </li>
                    <!--
                    <li class="nav-item">
                        <a class="nav-link" href="examples/icons.html">
                        <i class="ni ni-planet text-orange"></i>
                        <span class="nav-link-text">Icons</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="examples/map.html">
                        <i class="ni ni-pin-3 text-primary"></i>
                        <span class="nav-link-text">Google</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="examples/tables.html">
                        <i class="ni ni-bullet-list-67 text-default"></i>
                        <span class="nav-link-text">Tables</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="examples/login.html">
                        <i class="ni ni-key-25 text-info"></i>
                        <span class="nav-link-text">Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="examples/register.html">
                        <i class="ni ni-circle-08 text-pink"></i>
                        <span class="nav-link-text">Register</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="examples/upgrade.html">
                        <i class="ni ni-send text-dark"></i>
                        <span class="nav-link-text">Upgrade</span>
                        </a>
                    </li>
                -->
                </ul>
            </div>
        </div>
    </div>
</nav>