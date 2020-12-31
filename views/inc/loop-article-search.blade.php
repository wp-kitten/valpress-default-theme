@php
    /**@var \App\Themes\ValPress\DefaultTheme\ThemeHelper $themeHelper*/
    /**@var \App\Models\Post $post*/
@endphp
<article class="loop-post mb-4">
    <header class="article-header img-zoom-in">
        {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive') !!}
    </header>

    <section class="article-content p-4 pt-0 pb-0">
        <h2 class="entry-title m-0">
            <a href="{{vp_get_permalink($post)}}" class="link-blue">
                {!! wp_kses_post($post->title) !!}
            </a>
        </h2>
        <span class="entry-date text-grey font-smaller d-block mt-2">{{vp_the_date($post, true)}}</span>

        <div class="entry-excerpt mt-3">{!! vp_ellipsis(wp_strip_all_tags($post->excerpt), 80) !!}</div>
    </section>
</article>
