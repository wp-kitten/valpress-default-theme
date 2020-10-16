{{--
    The template to display the front page
--}}
@inject('themeHelper', App\DefaultTheme\ThemeHelper)
@extends('layouts.frontend')

@section('title')
    <title>{!! $page->title !!}</title>
@endsection

@section('content')
    <main class="site-page page-home mt-4 mb-4">

    </main>
@endsection
