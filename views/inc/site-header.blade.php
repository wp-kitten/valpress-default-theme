<header class="cpdt-site-header">
    <div class="container">
        <div class="inner">
            <h1 class="logo m-0">
                <a href="{{route('app.home')}}">
                    {{env('APP_NAME', 'ContentPress')}}
                </a>
            </h1>
            @if(cp_has_menu('main-menu'))
                <nav class="cpdt-navbar mt-3 mt-md-0">
                    <div class="cpdt-navbar-nav topnav">
                        {!! cp_menu('main-menu') !!}
                    </div>
                </nav>
            @endif
        </div>
    </div>
</header>
