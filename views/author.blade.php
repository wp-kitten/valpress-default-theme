{{--
    The template to display an author's posts
--}}
@extends('layouts.frontend')

@inject('themeHelper', App\Themes\ValPress\DefaultTheme\ThemeHelper)
@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
@endphp

@section('content')
    <main class="site-page page-page page-singular">

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
                                    <article class="loop-post mb-4">
                                        <header class="article-header">
                                            {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive') !!}
                                        </header>
                                        <section class="article-meta mt-2">
                                            <a href="{{vp_get_category_link($post->firstCategory())}}" class="text-dark">
                                                {!! $post->firstCategory()->name !!}
                                            </a>
                                            <span class="text-grey">{{vp_the_date($post)}}</span>
                                        </section>
                                        <section class="article-content mt-1">
                                            <h5 class="entry-title">
                                                <a href="{{vp_get_permalink($post)}}">
                                                    {!! wp_kses_post($post->title) !!}
                                                </a>
                                            </h5>
                                        </section>
                                    </article>
                                </div>
                            @endforeach
                        @else
                            @include('inc.no-content', ['class' => 'info', 'text' => __('vpdt::m.No posts found.')])
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
