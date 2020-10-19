<header class="cpdt-site-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="logo-wrap">
                    <h1 class="logo mb-0">
                        <a href="{{route('app.home')}}" class="text-dark">
                            {{env('APP_NAME', 'ContentPress')}}
                        </a>
                    </h1>
                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                @if(cp_has_menu('main-menu'))
                    <nav class="cpdt-navbar">
                        <div class="cpdt-navbar-nav topnav">
                            {!! cp_menu('main-menu') !!}
                        </div>
                    </nav>
                @endif
            </div>
        </div>
    </div>
</header>
