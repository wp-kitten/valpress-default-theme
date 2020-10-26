@php
    $settings = new \App\Models\Settings();

    /**@var App\Models\Settings $settings*/
    /**@var App\Helpers\Cache $cacheClass*/

    $postStatus = App\Models\PostStatus::where( 'name', 'publish' )->first();
    $postType = App\Models\PostType::where( 'name', 'post' )->first();

    $languageID = cp_get_frontend_user_language_id();
    $cacheClass = app('cp.cache');

    $tagsCacheKey = "blog_tags_cache_{$languageID}";
    $tags = $cacheClass->get($tagsCacheKey, false);
    if(! $tags){
        $tags = App\Models\Tag::where( 'post_type_id', $postType->id )->latest( 'language_id', $languageID )->limit( 20 )->get();
        if($tags && $tags->count()){
            $cacheClass->set($tagsCacheKey, $tags);
        }
    }

    $categoriesCacheKey = "blog_categories_cache_{$languageID}";
    $categories = $cacheClass->get($categoriesCacheKey, false);
    if(! $categories){
        $_categories = App\Models\Category::where( 'post_type_id', $postType->id )->where( 'language_id', $languageID )->where( 'category_id', null )->latest()->get();
        if($_categories && $_categories->count()){
            foreach ( $_categories as $category ) {
                $categories[ $category->id ] = [
                    'category' => $category,
                    'num_posts' => $category->posts()->count(),
                ];
            }
            $cacheClass->set($categoriesCacheKey, $categories);
        }
    }

    //#! Reusable query
    $recentPostsQuery = \App\Models\Post::where('language_id', $languageID)
                        ->where('post_status_id', $postStatus->id)
                        ->where('post_type_id', $postType->id);

    //#! Recent Posts List
    $recentPostsList = $recentPostsQuery->inRandomOrder()->limit(5)->get();

    //#! Recent Posts With Image
    $recentPostsImages = false;
    if( $recentPostsList && $recentPostsList->count()){
        $ignoreIds = \Illuminate\Support\Arr::pluck($recentPostsList,'id');
        $recentPostsImages = $recentPostsQuery->whereNotIn('id', $ignoreIds)->inRandomOrder()->limit(5)->get();
    }

@endphp

<div class="blog-sidebar">

    <div class="widget widget-search mt-0 mb-5">
        <div class="widget-content mt-0">
            {!! cp_search_form(__("cpdt::m.Search..."), '<i class="fas fa-search"></i>') !!}
        </div>
    </div>

    @if($recentPostsList)
        <div class="widget widget-recent-posts widget-recent-posts-list mb-5">
            <div class="widget-title">
                <h3 class="widgettitle">{{__('cpdt::m.Recent Posts')}}</h3>
            </div>
            <div class="widget-content">
                <ul class="list-unstyled posts-list">
                    @foreach($recentPostsList as $post)
                        <li class="mb-3">
                            <a class="post-title link-blue text-capitalize font-weight-bold font-smaller" href="{{cp_get_permalink($post)}}">
                                {!! $post->title !!}
                            </a>
                            <div class="text-grey post-excerpt mt-1 font-smaller">{!! cp_ellipsis(wp_strip_all_tags($post->excerpt), 50) !!}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($categories)
        <div class="widget widget-categories mb-5">
            <div class="widget-title">
                <h3 class="widgettitle">{{__('cpdt::m.Categories')}}</h3>
            </div>
            <div class="widget-content">
                <ul class="list-unstyled categories-list">
                    @foreach($categories as $categoryID => $info)
                        <li>
                            <a class="category-name link-green text-capitalize" href="{{cp_get_category_link($info['category'])}}">
                                {!! $info['category']->name !!}
                            </a>
                            <span class="text-dark">{{$info['num_posts']}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($recentPostsImages)
        <div class="widget widget-recent-posts widget-recent-posts-images mb-5">
            <div class="widget-title">
                <h3 class="widgettitle">{{__('cpdt::m.Recent Posts')}}</h3>
            </div>
            <div class="widget-content">
                <ul class="list-unstyled posts-list">
                    @foreach($recentPostsList as $post)
                        <li class="mb-3">
                            {!! $themeHelper->getPostImageOrPlaceholder($post, '', 'image-responsive') !!}
                            <a class="post-title text-dark text-capitalize font-smaller" href="{{cp_get_permalink($post)}}">
                                {!! $post->title !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($tags && $tags->count())
        <div class="widget widget-tags mb-5">
            <div class="widget-title">
                <h3 class="widgettitle">{{__('cpdt::m.Tags')}}</h3>
            </div>
            <div class="widget-content">
                <ul class="list-unstyled tags-list">
                    <li class="mb-3">
                        @foreach($tags as $tag)
                            <a href="{{cp_get_tag_link($tag)}}" class="link-blue ml-2">
                                {!! wp_kses_post($tag->name) !!}
                            </a>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    @endif

    <div class="widget widget-meta mb-5">
        <div class="widget-title">
            <h3 class="widgettitle">{{__('cpdt::m.Meta')}}</h3>
        </div>
        <div class="widget-content">
            <ul class="list-unstyled tags-list">
                <li class="mb-1">
                    @guest
                        <a class="link-blue" href="{{route('login')}}">{{__("cpdt::m.Login")}}</a>
                    @else
                        <a href="#"
                           class="link-blue"
                           onclick="event.preventDefault();document.getElementById('form-meta-logout').submit();">{{__("cpdt::m.Logout")}}</a>
                        <form id="form-meta-logout" action="{{route('logout')}}" method="post">
                            @csrf
                        </form>
                    @endguest
                </li>

                <li class="mb-1">
                    @guest
                        @if($settings->getSetting('user_registration_open', false))
                            <a class="link-blue" href="{{route('register')}}">{{__("cpdt::m.Register")}}</a>
                        @endif
                    @endguest
                </li>

                <li class="mb-1">
                    <a class="link-blue" href="{{CONTENTPRESS_URL}}" target="_blank">{{__("cpdt::m.ContentPress.news")}}</a>
                </li>
            </ul>
        </div>
    </div>

</div>
