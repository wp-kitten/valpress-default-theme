{{--
    [post type: post]
    The template to display posts from a tag
--}}
@extends('layouts.frontend')

@inject('themeHelper', App\Themes\ContentPressDefaultTheme\ThemeHelper)

@section('title')
    <title>{!! $tag->name !!}</title>
@endsection


@section('content')
    <main class="site-page page-tag">

        <header class="page-subheader bg-white-smoke text-right pt-5 pb-2 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{{__('cpdt::m.Tag: :name', ['name' => $tag->name])}}</h2>
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-sm-12 col-md-9">
                    {{-- POSTS --}}
                    <div class="row">
                        <div class="col-sm-12">
                            @if(!$posts || ! $posts->count())
                                @include('inc.no-content', ['class' => 'info', 'text' => __('cpdt::m.No posts under this tag.')])
                            @else
                                <div class="row">
                                    @foreach($posts as $post)
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <article class="loop-post mb-4">
                                                <header class="article-header">
                                                    {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive') !!}
                                                </header>
                                                <section class="article-meta mt-2">
                                                    <a href="{{cp_get_category_link($post->firstCategory())}}" class="text-dark">
                                                        {!! $post->firstCategory()->name !!}
                                                    </a>
                                                    <span class="text-grey">{{cp_the_date($post)}}</span>
                                                </section>
                                                <section class="article-content mt-1">
                                                    <h5 class="entry-title">
                                                        <a href="{{cp_get_permalink($post)}}">
                                                            {!! wp_kses_post($post->title) !!}
                                                        </a>
                                                    </h5>
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
                <div class="col-sm-12 col-md-3">
                    @include('inc.blog-sidebar')
                </div>
            </div>
        </div>

    </main>
@endsection
