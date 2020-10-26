{{--
    [post type: post]
    The general template to display a single post type
--}}
@extends('layouts.frontend')

@inject('themeHelper', App\Themes\ContentPressDefaultTheme\ThemeHelper)
@inject('settings', App\Models\Settings)
@php
    /**@var \App\Themes\ContentPressDefaultTheme\ThemeHelper $themeHelper*/
@endphp

@section('content')
    <main class="site-page page-singular">

        <header class="page-subheader bg-white-smoke pt-5 pb-5 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="page-title mb-4 mt-4">{!! wp_kses_post($post->title) !!}</h2>

                        <!-- POST META -->
                        <section class="entry-meta mt-2 mb-2">
                            <span class="mr-2 text-dark"><i class="fa fa-clock-o"></i> {{cp_the_date($post, true)}}</span>
                            <span class="mr-2"><i class="fa fa-user"></i>
                                <a href="{{route('blog.author', $post->user->id)}}" class="link-red">{{$post->user->display_name}}</a>
                            </span>
                            @if($post->categories()->count())
                                <span>
                                    <i class="fa fa-folder-open"></i>
                                    @foreach($post->categories()->get() as $category)
                                        <a href="{{cp_get_category_link($category)}}" class="category-link link-green mr-2">{!! $category->name !!}</a>
                                    @endforeach
                                </span>
                            @endif
                        </section>

                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">

                {{-- MAIN CONTENT --}}
                <div class="col-sm-12 col-md-9">
                    <!-- SINGLE POST START -->
                    <article class="post article-post article-single">

                        <!-- POST IMAGE -->
                        <header class="entry-header">
                            {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive', [ 'alt' => $post->title ]) !!}
                        </header>

                        <!-- POST CONTENT -->
                        <main class="entry-content mt-4 mb-4">
                            {!! $post->content !!}
                        </main>

                        {{-- Render tags & social links --}}
                        <footer class="entry-footer">
                            {!! do_action('contentpress/post/footer', $post) !!}
                        </footer>

                        {{-- Render the post Edit link --}}
                        @if(cp_current_user_can('edit_others_posts'))
                            <footer class="entry-footer mt-4 mb-4">
                                <a href="{{cp_get_post_edit_link($post)}}" class="btn btnLinkRed">{{__('cpdt::m.Edit')}}</a>
                            </footer>
                        @endif

                        {{-- RELATED POSTS --}}
                        @include('inc.related-posts', [
                            'post' => $post,
                            'title' => __('cpdt::m.In the same category'),
                            'themeHelper' => $themeHelper,
                        ])

                        @if(cp_has_comments($post) || cp_comments_open($post))
                            @include('inc.post-comments', [
                                'post' => $post,
                                'settings' => $settings
                            ])
                        @endif

                    </article><!-- SINGLE POST END -->
                </div>

                {{-- SIDEBAR --}}
                <div class="col-sm-12 col-md-3 bg-white">
                    @include('inc.blog-sidebar')
                </div>
            </div>
        </div>
    </main>
@endsection
