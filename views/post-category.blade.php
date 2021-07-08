{{--
    [post type: post]
    The template to display posts from a category
--}}
@extends('layouts.frontend')

@inject('themeHelper', 'App\Themes\ValPress\DefaultTheme\ThemeHelper')
@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
@endphp

@section('title')
    <title>{!! $category->name !!}</title>
@endsection

@section('content')
    <main class="site-page page-category">

        <header class="page-subheader bg-white-smoke text-right pt-5 pb-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{{__('vpdt::m.Category: :name', ['name' => $category->name])}}</h2>
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-sm-12 col-md-9">

                    {{-- SUBCATEGORIES --}}
                    <div class="row">
                        <div class="col-sm-12">
                            @php
                                $parentCategories = $category->parentCategories();
                                $catsTree = [];
                                if( ! empty($parentCategories)){
                                    foreach($parentCategories as $cat){
                                        $catsTree[] = '<a href="'.esc_attr(vp_get_category_link($cat)).'">'.$cat->name.'</a>';
                                    }
                                }
                                $catsTree[] = '<a href="'.esc_attr(vp_get_category_link($category)).'">'. $category->name.'</a>';
                            @endphp
                            @if(count($catsTree) > 1)
                                <span class="d-block text-description">{!! implode('/', $catsTree) !!}</span>
                            @endif
                        </div>
                    </div>

                    {{-- POSTS --}}
                    <div class="row">
                        <div class="col-sm-12">
                            @if(!$posts || ! $posts->count())
                                @include('inc.no-content', ['class' => 'info', 'text' => __('vpdt::m.No posts in this category.')])
                            @else
                                <div class="row masonry-grid">
                                    <div class="col-xs-12 col-sm-6 col-md-4 masonry-grid-sizer"></div>
                                    @foreach($posts as $post)
                                        <div class="col-xs-12 col-sm-6 col-md-4 masonry-grid-item">
                                            @include('inc.loop-article-search', [
                                                'themeHelper' => $themeHelper,
                                                'post' => $post,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pagination-wrap mt-4 mb-4">
                                            {!! $posts->render() !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div class="col-sm-12 col-md-3">
                    @include('inc.blog-sidebar')
                </div>

            </div>
        </div>

    </main>
@endsection
