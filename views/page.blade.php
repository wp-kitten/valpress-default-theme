{{--
    [post type: page]
    The template to display pages
--}}
@extends('layouts.frontend')

@inject('themeHelper', 'App\Themes\ValPress\DefaultTheme\ThemeHelper')
@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
@endphp

@section('content')
    <main class="site-page page-page page-singular">

        <header class="page-subheader bg-white-smoke pt-5 pb-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{!!  $page->title !!}</h2>
                    </div>
                </div>
            </div>
        </header>

        <section class="page-content-wrap">
            <div class="container">
                <div class="{{vp_post_classes()}}">
                    <article class="article-single">
                        <div class="entry-content">
                            {!! $page->content !!}
                        </div>
                    </article>
                </div>

                {{-- Render the post Edit link --}}
                @if(vp_current_user_can('edit_others_posts'))
                    <footer class="entry-footer mt-4 mb-4">
                        <a href="{{vp_get_post_edit_link($page)}}" class="btn bg-danger text-light">{{__('vpdt::m.Edit')}}</a>
                    </footer>
                @endif
            </div> <!-- container -->
        </section> <!-- section-full -->
    </main>

@endsection
