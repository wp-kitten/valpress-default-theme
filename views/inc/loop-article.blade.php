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

        <div class="entry-excerpt mt-3">{!! $post->excerpt !!}</div>

        <section class="article-meta mt-3 pt-2">
            <img src="{{$themeHelper->getAuthorImageOrPlaceholder($post->user->id)}}" alt="" class="author-image img-circle"/>

            <p class="entry-date text-grey font-smaller mb-0 ml-3">
                <span class="entry-date text-grey font-smaller d-block">
                    {!! __('vpdt::m.By: :user_link', [
                    'user_link' => '<a class="text-dark" href="'.route('blog.author', $post->user->id).'">'.$post->user->display_name.'</a>'
                ]) !!}
                </span>
                <span class="entry-date text-grey font-smaller d-block">
                    {!! __("vpdt::m.Published in: :category_link",[
                    'category_link' => '<a class="text-dark" href="'.vp_get_category_link($post->firstCategory()).'">'.$post->firstCategory()->name.'</a>',
                ]) !!}
                </span>
            </p>
        </section>
    </section>
</article>
