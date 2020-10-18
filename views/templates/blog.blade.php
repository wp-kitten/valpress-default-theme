{{--
    The template to display the blog page
--}}
@extends('layouts.frontend')

@inject('themeHelper', App\Themes\ContentPressDefaultTheme\ThemeHelper)
@inject('settings', App\Models\Settings)
@inject('postStatusClass', App\Models\PostStatus)
@inject('postTypeClass', App\Models\PostType)

@php
    /**@var \App\Themes\ContentPressDefaultTheme\ThemeHelper $themeHelper*/
    /**@var App\Models\Settings $settings*/

    $postStatus = $postStatusClass->where( 'name', 'publish' )->first();
    $postType = $postTypeClass->where( 'name', 'post' )->first();
    $posts = \App\Models\Post::latest()
           ->where( 'post_status_id', $postStatus->id )
           ->where( 'post_type_id', $postType->id )
           ->whereDate( 'created_at', '>', now()->subMonth()->toDateString() )
           ->paginate( $settings->getSetting('posts_per_page', 10) );
@endphp


@section('title')
    <title>{!! $page->title !!}</title>
@endsection

@section('content')
    <main class="site-page page-blog">

        <header class="page-subheader bg-white-smoke pt-5 pb-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{!!  $settings->getSetting('blog_title') !!}</h2>
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-sm-12 col-md-9">

                    {{-- PAGE TITLE --}}
                    <div class="row">

                        {{-- POSTS --}}
                        <div class="col-sm-12">
                            @if(!$posts || ! $posts->count())
                                @include('inc.no-content', ['class' => 'info', 'text' => __('cpdt::m.No posts found.')])
                            @else
                                <div class="row">
                                    @foreach($posts as $post)
                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <article class="loop-post mb-4">
                                                <header class="article-header">
                                                    {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive') !!}

                                                    <h2 class="entry-title mt-4">
                                                        <a href="{{cp_get_permalink($post)}}" class="text-dark">
                                                            {!! wp_kses_post($post->title) !!}
                                                        </a>
                                                    </h2>
                                                </header>

                                                <section class="article-content">
                                                    <p>
                                                        <span class="entry-date text-grey font-smaller">{{cp_the_date($post, true)}}</span>
                                                        <span class="entry-author font-smaller">
                                                            {!! __('cpdt::m.By: :user_link', [
                                                                'user_link' => '<a class="text-danger" href="'.route('blog.author', $post->user->id).'">'.$post->user->display_name.'</a>'
                                                            ]) !!}
                                                        </span>
                                                    </p>
                                                    {!! $post->excerpt !!}
                                                </section>

                                                <section class="article-meta mt-4 pt-2">
                                                    <span class="text-grey font-smaller">
                                                        {!! __("cpdt::m.Published in: :category_link",[
                                                            'category_link' => '<a class="text-danger" href="'.cp_get_category_link($post->firstCategory()).'">'.$post->firstCategory()->name.'</a>',
                                                        ]) !!}
                                                    </span>
                                                </section>

                                            </article>
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
                <div class="col-sm-12 col-md-3 bg-white">
                    @include('inc.blog-sidebar', [
                        'settings' => $settings,
                    ])
                </div>
            </div>
        </div>
    </main>
@endsection
