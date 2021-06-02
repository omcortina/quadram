<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
            <img src="{{asset('design/assets/img/brand/blue.png')}}" class="navbar-brand-img" alt="...">
        </a>
        </div>
        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                @php
                   \App\Models\Menu::loadMenu();
                @endphp
            </div>
        </div>
    </div>
</nav>