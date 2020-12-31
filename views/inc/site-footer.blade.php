<footer class="vpdt-site-footer">
    <div class="container">
        <div class="footer-content-wrap">
            <h4 class="logo mt-0">
                <a href="{{route('app.home')}}">
                    {{env('APP_NAME', 'ValPress')}}
                </a>
            </h4>

            @if(cp_has_menu('footer-menu'))
                <nav class="footer-menu-nav">
                    <ul class="list-unstyled footer-menu-wrap">
                        {!! cp_menu('footer-menu') !!}
                        <li>
                            <a href="https://github.com/wp-kitten/valpress" target="_blank" title="{{__('vpdt::m.ValPress on Github')}}">
                                <i class="fab fa-github"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>

    </div>
</footer>
