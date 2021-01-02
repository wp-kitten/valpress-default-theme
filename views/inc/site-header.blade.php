<header class="vpdt-site-header">
    <div class="container">
        <div class="inner">
            <h1 class="logo m-0">
                <a href="{{route('app.home')}}">
                    {{env('APP_NAME', 'ValPress')}}
                </a>
            </h1>
            @if(vp_has_menu('main-menu'))
                @php
                    $menu = \App\Models\Menu::where( 'slug', 'main-menu' )->first();
                    $displayAs = ( new \App\Models\Options() )->getOption( "menu-{$menu->id}-display-as", 'basic' );
                    $menuClass = ('megamenu' == $displayAs ? 'vp-mega-menu' : '');
                @endphp
                <nav class="vp-navbar {{$menuClass}} {{$displayAs}}">
                    {!! vp_menu('main-menu') !!}
                </nav>
            @endif
        </div>
    </div>
</header>
