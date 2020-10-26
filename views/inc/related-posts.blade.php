@php
    /**@var App\Models\Post $post*/
    $postCategory = $post->categories()->first();
    if(! $postCategory){
        return;
    }
    $posts = cp_get_related_posts($post->categories()->first(), 3, $post->id);
@endphp
@if($posts && $posts->count())
    <section class="related-posts">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="section-title mt-5 mb-4 text-green"><i class='fa fa-pencil-square-o'></i> {!! $title !!}</h3>
            </div>
            @foreach($posts as $post)
                <div class="col-sm-12 col-md-4">
                    @include('inc.loop-article-search', [
                        'themeHelper' => $themeHelper,
                        'post' => $post,
                    ])
                </div>
            @endforeach
        </div>
    </section>
@endif
