{{--
    The template to display the front page
--}}
@inject('themeHelper', App\Themes\ContentPressDefaultTheme\ThemeHelper)
@extends('layouts.frontend')

@php
/**@var \App\Themes\ContentPressDefaultTheme\ThemeHelper $themeHelper*/
@endphp


@section('title')
    <title>{!! $page->title !!}</title>
@endsection

@section('content')
    <main class="site-page page-home mt-0 mb-4">
        <header class="page-header" style="background-image: url({{cp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/home-header.jpg')}})">
            <div class="header-wrap">
                <h2 class="header-title">{{__('cpdt::m.Your Laravel blogging platform')}}</h2>
                <h4 class="header-subtitle">{{__('cpdt::m.is here, enjoy it!')}}</h4>
            </div>
        </header>

        <section class="pt-5 bg-light">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center __with-margin-lr">
                            <h2 class="mb-3">{{__("cpdt::m.Whatever your project is, we've got you covered!")}}</h2>
                            <h6 class="mt-4 mb-5" style="line-height: 1.6">
                                {{__("cpdt::m.From blogging to a one page or to a complete agency website ContentPress can handle it with flying colors. All you need is an idea, from thereon worry not!")}}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{cp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-4.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("cpdt::m.All you need is less")}}</h5>
                            <p class="mt-3">{{__("cpdt::m.Forget the bloated code or the gigantic mess, less is more!")}}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{cp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-5.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("cpdt::m.Beautiful and functional? Oh, yes!")}}</h5>
                            <p class="mt-3">{{__("cpdt::m.Have your idea? Good! You have everything you need to make something great!")}}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{cp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-6.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("cpdt::m.Coding should be fun")}}</h5>
                            <p class="mt-3">{{__("cpdt::m.Everyone should be able to have their own website without having to pay lots of money for it.")}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center pl-5 pr-5">
                            <h2 class="mb-5">{{__("cpdt::m.We have news for you")}}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @forelse($posts as $post)
                        <div class="col-sm-12 col-md-4 mb-5">
                            <article class="loop-article">
                                <header class="article-header">
                                    {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive', ['alt' => $post->title]) !!}
                                </header>
                                <h4 class="article-title">
                                    <a href="{{cp_get_permalink($post)}}">
                                        {!! $post->title !!}
                                    </a>
                                </h4>
                                <section class="article-content">
                                    {!! cp_post_excerpt($post) !!}
                                </section>
                            </article>
                        </div>
                    @empty
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <p>{{__("cpdt::m.Yeah, bummer, looks like we don't have any news yet. Why not add some?")}}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

    </main>
@endsection
