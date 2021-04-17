{{--
    The template to display an author's posts
--}}
@extends('layouts.frontend')

@inject('themeHelper', 'App\Themes\ValPress\DefaultTheme\ThemeHelper')
@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
@endphp

@section('content')
    <main class="site-page page-page page-author">

        <header class="page-subheader bg-white-smoke text-right pt-5 pb-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{{__("vpdt::m.Author: :name", ['name' => $user->display_name])}}</h2>
                    </div>
                </div>
            </div>
        </header>


        <div class="container">
            <div class="row">
                {{-- MAIN CONTENT --}}
                <div class="col-xs-12 col-md-9">
                    {{-- POSTS GRID --}}
                    <div class="row">
                        @if($posts)
                            @foreach($posts as $post)
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    @include('inc.loop-article-search', [
                                        'themeHelper' => $themeHelper,
                                        'post' => $post,
                                    ])
                                </div>
                            @endforeach
                        @else
                            <div class="col-sm-12">
                                @include('inc.no-content', ['class' => 'info', 'text' => __('vpdt::m.No posts found.')])
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pagination-wrap mt-4 mb-4">
                                {!! $posts ? $posts->appends([ 's' => vp_get_search_query() ])->render() : '' !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="col-sm-12 col-md-3 bg-white">
                    @include('inc.blog-sidebar')
                </div>
            </div>
        </div>
    </main>

@endsection
