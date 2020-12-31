{{--
        The template to display the search results
--}}
@extends('layouts.frontend')
@inject('themeHelper', App\Themes\ValPress\DefaultTheme\ThemeHelper)


@section('title')
    <title>{{__('vpdt::m.Search for: :query_string', [ 'query_string' => vp_get_search_query()]) }}</title>
@endsection


@section('content')
    <main class="site-page page-search">

        <header class="page-subheader bg-white-smoke pt-5 pb-2 mb-5">
            <div class="container">
                {{-- FILTERS --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="search-filters-wrap pt-3 pb-3 mb-4">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <div class="search-results">
                                        <small class="text-dark">
                                            {{__('vpdt::m.Found :num_results results for:', [ 'num_results' => number_format( $numResults, 0, ',', '.') ])}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-8">
                                    <div class="search-form-wrap">
                                        {!! vp_search_form(__('vpdt::m.Search...')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4 text-right">
                                    <div class="orderby-wrap mt-2">
                                        <form id="form-filter-search" method="get" action="<?php esc_attr_e( route( 'blog.search' ) ); ?>">
                                            <input name="s" value="{{vp_get_search_query()}}" class="hidden"/>
                                            <select name="order" id="js-sort-results" data-form-id="form-filter-search">
                                                @php $selected = ('desc' == $order ? 'selected' : ''); @endphp
                                                <option value="desc" {!! $selected !!}>{{__('vpdt::m.Sort by Newest')}}</option>
                                                @php $selected = ('asc' == $order ? 'selected' : ''); @endphp
                                                <option value="asc" {!! $selected !!}>{{__('vpdt::m.Sort by Oldest')}}</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
