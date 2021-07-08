{{--
    The template to display the blog page
--}}
@extends('layouts.frontend')

@inject('themeHelper', 'App\Themes\ValPress\DefaultTheme\ThemeHelper')
@inject('settings', 'App\Models\Settings')
@inject('postStatusClass', 'App\Models\PostStatus')
@inject('postTypeClass', 'App\Models\PostType')

@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
    /**@var App\Models\Settings $settings*/

    $postStatus = $postStatusClass->where( 'name', 'publish' )->first();
    $postType = $postTypeClass->where( 'name', 'post' )->first();
    $posts = \App\Models\Post::latest()
           ->where( 'post_status_id', $postStatus->id )
           ->where( 'post_type_id', $postType->id )
           ->whereDate( 'created_at', '>', now()->subMonth()->toDateString() )
           ->paginate( $settings->getSetting('posts_per_page', 10) );

    $postFeatured = \App\Models\Post
           ::where( 'post_status_id', $postStatus->id )
           ->where( 'post_type_id', $postType->id )
           ->whereDate( 'created_at', '>', now()->subMonth()->toDateString() )
           ->inRandomOrder()
           ->first();
@endphp


@section('title')
    <title>{!! $page->title !!}</title>
@endsection

@section('content')
    <main class="site-page page-blog">

        <header class="page-subheader" style="background-image: url({{vp_post_get_featured_image_url($page->id)}});">
            <div class="container">
                <div class="inner">
                    @if($postFeatured)
                        <div class="featured-post">
                            <span class="entry-date text-grey font-smaller">{{vp_the_date($postFeatured, true)}}</span>
                            <span class="text-grey font-smaller">
                                {!! __("vpdt::m.Published in: :category_link",[
                                    'category_link' => '<a class="link-red" href="'.vp_get_category_link($postFeatured->firstCategory()).'">'.$postFeatured->firstCategory()->name.'</a>',
                                ]) !!}
                            </span>
                            <h2 class="entry-title mt-3 mb-3">
                                <a href="{{vp_get_permalink($postFeatured)}}" title="{{$postFeatured->title}}">
                                    {!! vp_ellipsis(wp_kses_post($postFeatured->title), 35) !!}
                                </a>
                            </h2>
                            <div class="excerpt font-smaller text-grey">
                                {!! $postFeatured->excerpt !!}
                            </div>
                            <p class="entry-author font-smaller mt-3 mb-0">
                                {!! __('vpdt::m.By: :user_link', [
                                    'user_link' => '<a class="link-red" href="'.route('blog.author', $postFeatured->user->id).'">'.$postFeatured->user->display_name.'</a>'
                                ]) !!}
                            </p>
                        </div>
                    @endif

                    <h2 class="page-title mb-4 mt-md-4">{!!  $settings->getSetting('blog_title') !!}</h2>
                </div>
            </div>
        </header>

        <div class="container pt-5">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-sm-12 col-md-9">

                    {{-- PAGE TITLE --}}
                    <div class="row">

                        {{-- POSTS --}}
                        <div class="col-sm-12">
                            @if(!$posts || ! $posts->count())
                                @include('inc.no-content', ['class' => 'info', 'text' => __('vpdt::m.No posts found.')])
                            @else
                                <div class="row masonry-grid">
                                    <div class="col-xs-12 col-sm-6 col-md-6 masonry-grid-sizer"></div>
                                    @foreach($posts as $post)
                                        <div class="col-xs-12 col-sm-6 col-md-6 masonry-grid-item">
                                            @include('inc.loop-article', [
                                                'themeHelper' => $themeHelper,
                                                'post' => $post,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pagination-wrap flex-wrap mt-4 mb-4">
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
