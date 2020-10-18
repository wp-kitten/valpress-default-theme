<?php

namespace App\Http\Controllers;

use App\Helpers\CPML;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostComments;
use App\Models\PostStatus;
use App\Models\PostType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

class DefaultThemeController extends SiteController
{
    /**
     * Render the website's homepage.
     */
    public function index(): View
    {
        $posts = Post::where( 'language_id', $this->language->getID( CPML::getFrontendLanguageCode() ) )
            ->where( 'post_status_id', PostStatus::where( 'name', 'publish' )->first()->id )
            ->where( 'post_type_id', PostType::where( 'name', 'post' )->first()->id )
            ->where( 'translated_post_id', null )
            ->limit( $this->settings->getSetting( 'posts_per_page', 12 ) )
            ->get();
        return view( 'index' )->with( [
            'posts' => $posts,
        ] );
    }

    /**
     * Frontend. Switch the current language. Can be used by language switchers
     * @param string $code Language code
     * @return RedirectResponse
     */
    public function lang( string $code ): RedirectResponse
    {
        //#! Ensure this is a valid language code
        CPML::setFrontendLanguageCode( $code );

        return redirect()->back();
    }

    public function category( $slug ): View
    {
        //#! Get the current language ID
        $defaultLanguageID = CPML::getDefaultLanguageID();
        //#! Get the selected language in frontend
        $frontendLanguageID = cp_get_frontend_user_language_id();

        $category = Category::where( 'slug', $slug )->where( 'language_id', $frontendLanguageID )->first();

        //#! Redirect to the translated category if exists
        if ( !$category ) {
            $categories = Category::where( 'slug', $slug )->get();
            if ( $categories && $categories->count() ) {
                foreach ( $categories as $_category ) {
                    $translatedCatID = $_category->translated_category_id;

                    //#! Default language -> other language ( EN -> RO ) //
                    if ( empty( $translatedCatID ) ) {
                        $category = Category::where( 'translated_category_id', $_category->id )->where( 'language_id', $frontendLanguageID )->first();
                        if ( !$category ) {
                            return $this->_not_found();
                        }
                        return redirect( cp_get_category_link( $category ) );
                    }

                    //#! Other language -> default language ( RO -> EN ) //
                    elseif ( $frontendLanguageID == $defaultLanguageID ) {
                        $category = Category::where( 'id', $_category->translated_category_id )->where( 'language_id', $frontendLanguageID )->first();
                        if ( !$category ) {
                            return $this->_not_found();
                        }
                        return redirect( cp_get_category_link( $category ) );
                    }

                    //#! other language -> other language ( ES -> RO )
                    $category = Category::where( 'translated_category_id', $_category->translated_category_id )->where( 'language_id', $frontendLanguageID )->first();
                    if ( !$category ) {
                        return $this->_not_found();
                    }
                    return redirect( cp_get_category_link( $category ) );
                }
            }
            else {
                return $this->_not_found();
            }
        }

        $cacheName = "blog:children-categories:{$category->id}:{$frontendLanguageID}";

        $childrenCategories = $this->cache->get( $cacheName );
        if ( !$childrenCategories ) {
            $childrenCategories = $category->childrenCategories()->where( 'language_id', $frontendLanguageID )->latest()->get();
            $this->cache->set( $cacheName, $childrenCategories );
        }

        //#! Specific template
        $view = $category->post_type()->first()->name . '-category';
        if ( view()->exists( $view ) ) {
            return view( $view )->with( [
                'category' => $category,
                'subcategories' => $childrenCategories,
                'posts' => $category->posts()->latest()->paginate( $this->settings->getSetting( 'posts_per_page' ) ),
            ] );
        }

        //#! General template
        return view( 'category' )->with( [
            'category' => $category,
            'subcategories' => $childrenCategories,
            'posts' => $category->posts()->latest()->paginate( $this->settings->getSetting( 'posts_per_page' ) ),
        ] );
    }

    public function tag( $slug ): View
    {
        //#! Get the current language ID
        $defaultLanguageID = CPML::getDefaultLanguageID();
        //#! Get the selected language in frontend
        $frontendLanguageID = cp_get_frontend_user_language_id();

        $tag = Tag::where( 'slug', $slug )->where( 'language_id', $frontendLanguageID )->first();

        //#! Redirect to the translated tag if exists
        if ( !$tag ) {
            $tags = Tag::where( 'slug', $slug )->get();
            if ( $tags && $tags->count() ) {
                foreach ( $tags as $_tag ) {
                    $translatedTagID = $_tag->translated_tag_id;

                    //#! Default language -> other language ( EN -> RO ) //
                    if ( empty( $translatedTagID ) ) {
                        $tag = Tag::where( 'translated_tag_id', $_tag->id )->where( 'language_id', $frontendLanguageID )->first();
                        if ( !$tag ) {
                            return $this->_not_found();
                        }
                        return redirect( cp_get_tag_link( $tag ) );
                    }

                    //#! Other language -> default language ( RO -> EN ) //
                    elseif ( $frontendLanguageID == $defaultLanguageID ) {
                        $tag = Tag::where( 'id', $_tag->translated_tag_id )->where( 'language_id', $frontendLanguageID )->first();
                        if ( !$tag ) {
                            return $this->_not_found();
                        }
                        return redirect( cp_get_tag_link( $tag ) );
                    }

                    //#! other language -> other language ( ES -> RO )
                    $tag = Tag::where( 'translated_tag_id', $_tag->translated_tag_id )->where( 'language_id', $frontendLanguageID )->first();
                    if ( !$tag ) {
                        return $this->_not_found();
                    }
                    return redirect( cp_get_tag_link( $tag ) );
                }
            }
            else {
                return $this->_not_found();
            }
        }

        $postType = PostType::where( 'name', 'post' )->first();
        if ( !$postType ) {
            return $this->_not_found();
        }

        $postStatus = PostStatus::where( 'name', 'publish' )->first();
        if ( !$postStatus ) {
            return $this->_not_found();
        }

        //#! Make sure the post is published if the current user is not allowed to "edit_private_posts"
        $_postStatuses = PostStatus::all();
        $postStatuses = [];
        if ( cp_current_user_can( 'edit_private_posts' ) ) {
            $postStatuses = Arr::pluck( $_postStatuses, 'id' );
        }
        else {
            $postStatuses[] = PostStatus::where( 'name', 'publish' )->first()->id;
        }

        $posts = $tag->posts()
            ->where( 'language_id', $tag->language_id )
            ->where( 'post_type_id', $postType->id )
            ->whereIn( 'post_status_id', $postStatuses )
            ->latest()
            ->paginate( $this->settings->getSetting( 'posts_per_page' ) );

        //#! Specific template
        $view = $tag->post_type()->first()->name . '-tag';
        if ( view()->exists( $view ) ) {
            return view( $view )->with( [
                'tag' => $tag,
                'posts' => $posts,
            ] );
        }

        return view( 'tag' )->with( [
            'tag' => $tag,
            'posts' => $posts,
        ] );
    }

    public function search(): View
    {
        $postType = PostType::where( 'name', '!=', 'page' )->get();

        $s = cp_get_search_query();

        if ( !$postType || empty( $s ) ) {
            return view( 'search' )->with( [
                'posts' => null,
                'numResults' => 0,
                'order' => 'desc',
            ] );
        }

        $postTypesArray = Arr::pluck( $postType, 'id' );

        //#! Filters
        $order = $this->request->get( 'order' );
        if ( empty( $order ) ) {
            $order = 'desc';
        }
        else {
            $order = strtolower( wp_kses( $order, [] ) );
            $order = ( in_array( $order, [ 'asc', 'desc' ] ) ? $order : 'desc' );
        }

        $posts = Post::where( 'language_id', cp_get_frontend_user_language_id() )
            ->where( 'post_status_id', PostStatus::where( 'name', 'publish' )->first()->id )
            ->whereIn( 'post_type_id', $postTypesArray )
            ->where( function ( $query ) use ( $s ) {
                return
                    $query->where( 'title', 'LIKE', '%' . $s . '%' )
                        ->orWhere( 'content', 'LIKE', '%' . $s . '%' )
                        ->orWhere( 'excerpt', 'LIKE', '%' . $s . '%' );
            } )
            //#! Only include results from within the last month
//            ->whereDate( 'created_at', '>', now()->subMonth()->toDateString() )
            ->orderBy( 'id', $order );

        $numResults = $posts->count();

        $posts = $posts->paginate( $this->settings->getSetting( 'posts_per_page', 12 ) );

        return view( 'search' )->with( [
            'posts' => $posts,
            'numResults' => $numResults,
            'order' => $order,
        ] );
    }

    public function author( $id ): View
    {
        $user = User::find( $id );
        if ( !$user ) {
            return $this->_not_found();
        }

        $posts = $user->posts()
            ->where( 'post_type_id', PostType::where( 'name', 'post' )->first()->id )
            ->where( function ( $query ) use ( $user ) {
                if ( !cp_user_can( $user, 'read_private_posts' ) ) {
                    $query->where( 'post_status_id', PostStatus::where( 'name', 'published' )->first()->id );
                }
            } )
            ->where( 'translated_post_id', null )
            ->paginate( $this->settings->getSetting( 'posts_per_page', 12 ) );

        return view( 'author' )->with( [
            'posts' => $posts,
            'user' => $user,
        ] );
    }

    public function __submitComment( $post_id ): RedirectResponse
    {
        do_action( 'contentpress/submit_comment', $this, $post_id );
        return redirect()->back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function __deleteComment( $id ): RedirectResponse
    {
        if ( !cp_current_user_can( 'moderate_comments' ) ) {
            return redirect()->to( URL::previous() . "#_comments" )->with( 'message', [
                'class' => 'danger', // success or danger on error
                'text' => __( 'cpdt::m.You are not allowed to perform this action.' ),
            ] );
        }

        $result = PostComments::destroy( $id );
        if ( !$result ) {
            return redirect()->to( URL::previous() . "#_comments" )->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpdt::m.The specified comment could not be deleted.' ),
            ] );
        }

        do_action( 'contentpress/comment/deleted', $id );
        return redirect()->to( URL::previous() . "#_comments" )->with( 'message', [
            'class' => 'success',
            'text' => __( 'cpdt::m.Comment deleted.' ),
        ] );
    }

    /**
     * Display the theme options page [Themes/Theme Options]
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function themeOptionsPageView(): View
    {
        return view( '_admin.theme-options' )->with( [
            'previous_install' => $this->options->getOption( DEFAULT_THEME_MAIN_DEMO_INSTALLED_OPT_NAME, false ),
        ] );
    }

    //#! TBD when needed
    public function themeOptionsSave(): RedirectResponse
    {
        return redirect()->back()->with( 'message', [
            'class' => 'warning',
            'text' => __( "cpdt::m.Not yet implemented." ),
        ] );
    }

    public function installMainDemo(): RedirectResponse
    {
        $theme = cp_get_current_theme();
        $themeDirPath = trailingslashit( $theme->getDirPath() );

        //#! Will store any errors
        $errors = [];

        //#! Load main demo data
        require_once( path_combine( $themeDirPath, 'src/MainDemoData.php' ) );

        //#! Load seeders
        $seeders = [
            'CpdtContentSeeder' => path_combine( $themeDirPath, 'seeders/CpdtContentSeeder.php' ),
            'CpdtMenuSeeder' => path_combine( $themeDirPath, 'seeders/CpdtMenuSeeder.php' ),
            'CpdtSettingsSeeder' => path_combine( $themeDirPath, 'seeders/CpdtSettingsSeeder.php' ),
        ];

        try {
            foreach ( $seeders as $className => $filePath ) {
                require_once( $filePath );
                Artisan::call( 'db:seed', [
                    '--class' => $className,
                ] );
            }
        }
        catch ( \Exception $e ) {
            $errors[] = $e->getMessage();
        }

        if ( !empty( $errors ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'warning',
                'text' => __( "cpdt::m.Something didn't go as planed: :errors", [ 'errors' => implode( '<br/>', $errors ) ] ),
            ] );
        }

        $this->options->addOption( DEFAULT_THEME_MAIN_DEMO_INSTALLED_OPT_NAME, true );
        return redirect()->back()->with( 'message', [
            'class' => 'success',
            'text' => __( "cpdt::m.Main demo installed successfully." ),
        ] );
    }
}
