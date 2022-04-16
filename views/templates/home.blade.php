{{--
    The template to display the front page
--}}
@extends('layouts.frontend')

@inject('themeHelper', 'App\Themes\ValPress\DefaultTheme\ThemeHelper')
@inject('settings', 'App\Models\Settings')

@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
    /**@var \App\Models\Settings $settings*/
@endphp

<?php
if ( ! isset( $posts ) ) {
    $posts = \App\Models\Post::where( 'language_id', ( new \App\Models\Language() )->getID( \App\Helpers\VPML::getFrontendLanguageCode() ) )
        ->where( 'post_status_id', \App\Models\PostStatus::where( 'name', 'publish' )->first()->id )
        ->where( 'post_type_id', \App\Models\PostType::where( 'name', 'post' )->first()->id )
        ->where( 'translated_post_id', null )
        ->limit( apply_filters( 'valpress/default_theme/homepage/max_blog_posts', 6 ) )
        ->get();
}
?>

@section('title')
    <title>{!! $page->title !!}</title>
@endsection

@section('content')
    <main class="site-page page-home mt-0 mb-4">
        <header class="page-header" style="background-image: url({{vp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/home-header.jpg')}})">
            <div class="header-wrap">
                <h2 class="header-title text-red">{{__('vpdt::m.Your Laravel blogging platform')}}</h2>
                <h4 class="header-subtitle text-red">{{__('vpdt::m.is here, enjoy it!')}}</h4>
            </div>
        </header>

        <section class="pt-5 bg-light">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center __with-margin-lr">
                            <h2 class="mb-3 text-blue-3">{{__("vpdt::m.Whatever your project is, we've got you covered!")}}</h2>
                            <h6 class="mt-4 mb-5 text-blue-3" style="line-height: 1.6">
                                {{__("vpdt::m.From blogging to a one page or to a complete agency website ValPress can handle it with flying colors. All you need is an idea, from thereon worry not!")}}
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{vp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-4.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("vpdt::m.All you need is less")}}</h5>
                            <p class="mt-3">{{__("vpdt::m.Forget the bloated code or the gigantic mess, less is more!")}}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{vp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-5.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("vpdt::m.Beautiful and functional? Oh, yes!")}}</h5>
                            <p class="mt-3">{{__("vpdt::m.Have your idea? Good! You have everything you need to make something great!")}}</p>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 mb-3">
                        <img class="image-responsive" src="{{vp_theme_url(DEFAULT_THEME_DIR_NAME, 'assets/img/unsplash-6.jpg')}}" alt=""/>
                        <div class="mt-3">
                            <h5>{{__("vpdt::m.Coding should be fun")}}</h5>
                            <p class="mt-3">{{__("vpdt::m.Everyone should be able to have their own website without having to pay lots of money for it.")}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center pl-5 pr-5">
                            <h2 class="section-title-blog text-blue-3">{{__("vpdt::m.We have news for you")}}</h2>
                        </div>
                    </div>
                </div>
                <div class="row blog-entries">
                    @forelse($posts as $post)
                        <div class="col-sm-12 col-md-4 mb-4">
                            @include('inc.loop-article-search', [
                                'themeHelper' => $themeHelper,
                                'post' => $post,
                            ])
                        </div>
                    @empty
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <p>{{__("vpdt::m.Yeah, bummer, looks like we don't have any news yet. Why not add some?")}}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @php
                    $blogPage = $settings->getSetting('blog_page');
                @endphp

                <div class="text-center mb-5">
                    <a href="{{vp_get_permalink(\App\Models\Post::find($blogPage))}}" class="btn btnLinkRed">{{__("vpdt::m.View all")}}</a>
                </div>
            </div>
        </section>

    </main>
@endsection
