<?php

use App\Helpers\ScriptsManager;
use App\Helpers\Theme;
use App\Models\Menu;
use App\Models\Options;
use App\Themes\ContentPressDefaultTheme\ThemeHelper;

/**
 * Include theme's views into the global scope
 */
add_filter( 'contentpress/register_view_paths', function ( $paths = [] ) {
    $paths[] = path_combine( DEFAULT_THEME_DIR_PATH, 'views' );
    return $paths;
}, 100 );

/**
 * Register the path to the translation file that will be used depending on the current locale
 */
cp_register_language_file( 'cpdt', path_combine(
    DEFAULT_THEME_DIR_PATH,
    'lang'
) );

/*
 * Load|output resources in the head tag
 */
add_action( 'contentpress/site/head', function () {

    $theme = new Theme( DEFAULT_THEME_DIR_NAME );

    ScriptsManager::enqueueStylesheet( 'gfont-montserrat', '//fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap' );
    ScriptsManager::enqueueStylesheet( 'gfont-open-sans', '//fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600;1,700;1,800&display=swap' );

    ScriptsManager::enqueueStylesheet( 'bootstrap.css', $theme->url( 'assets/vendor/bootstrap/css/bootstrap.min.css' ) );
    ScriptsManager::enqueueStylesheet( 'theme-style.css', $theme->url( 'assets/dist/css/theme-styles.css?t=' . now() ) );

    ScriptsManager::enqueueHeadScript( 'jquery.js', $theme->url( 'assets/vendor/jquery.min.js' ) );
    ScriptsManager::enqueueHeadScript( 'popper.js', $theme->url( 'assets/vendor/popper.min.js' ) );
    ScriptsManager::enqueueHeadScript( 'bootstrap.js', $theme->url( 'assets/vendor/bootstrap/js/bootstrap.min.js' ) );
    ScriptsManager::enqueueHeadScript( 'fa-kit.js', '//kit.fontawesome.com/cec4674fec.js' );
    ScriptsManager::enqueueFooterScript( 'theme-scripts.js', $theme->url( 'assets/js/theme-scripts.js' ) );

    if ( cp_is_singular() && ( cp_comments_open( cp_get_post() ) ) ) {
        ScriptsManager::localizeScript( 'comments-locale', 'CommentsLocale', [
            'confirm_cancel' => esc_js( __( 'cpdt::m.Are you sure you want to cancel?' ) ),
        ] );
        ScriptsManager::enqueueFooterScript( 'theme-comments.js', $theme->url( 'assets/js/comments.js' ) );
    }
} );

/*
 * Load|output resources in the site footer
 */
add_action( 'contentpress/site/footer', function () {
    //...
} );

/*
 * Do something when plugins have loaded
 */
add_action( 'contentpress/plugins/loaded', function () {
    //...
} );

/**
 * Output some content right after the <body> tag
 */
add_action( 'contentpress/after_body_open', function () {
    //...
} );

add_action( 'contentpress/post/footer', function ( $post ) {
    $tags = $post->tags()->get();
    if ( $tags ) {
        ?>
        <div class="post-meta">
            <div class="post-tags">
                <strong><?php esc_html_e( __( 'cpdt::m.Tags:' ) ); ?></strong>
                <?php
                foreach ( $tags as $tag ) {
                    echo '<a href="' . esc_attr( cp_get_tag_link( $tag ) ) . '" class="link-blue ml-2">' . $tag->name . '</a>';
                }
                ?>
            </div>
        </div>
        <?php
    }
} );

/**
 * Filter classes applied to the <body> tag
 */
add_filter( 'contentpress/body-class', function ( $classes = [] ) {
    $classes[] = 'contentpress-default-theme';
    return $classes;
} );

//<editor-fold desc=":: MAIN MENU ::">
/**
 * Add custom menu items to the main menu
 */
add_action( 'contentpress/menu::main-menu/before', function ( Menu $menu ) {

    $homeLinkActiveClass = ( Route::is( 'app.home' ) ? 'active' : '' );

    $displayAs = ( new Options() )->getOption( "menu-{$menu->id}-display-as", 'basic' );
    if ( 'basic' == $displayAs ) {
        echo '<ul class="list-unstyled main-menu basic">';
        echo '<li>';
        echo '<a href="' . route( 'app.home' ) . '" class="menu-item ' . $homeLinkActiveClass . '">' . esc_attr( __( 'cpdt::m.Home' ) ) . '</a>';
        echo '</li>';
    }
    else {
        echo '<a href="' . route( 'app.home' ) . '" class="menu-item ' . $homeLinkActiveClass . '">' . esc_attr( __( 'cpdt::m.Home' ) ) . '</a>';
    }
} );
add_action( 'contentpress/menu::main-menu/after', function ( Menu $menu ) {
    $menuToggleButton = '<a href="#" class="menu-item icon btn-toggle-nav js-toggle-menu" title="' . esc_attr( __( 'np::m.Toggle menu' ) ) . '">&#9776;</a>';

    $displayAs = ( new Options() )->getOption( "menu-{$menu->id}-display-as", 'basic' );
    if ( 'basic' == $displayAs ) {
        echo '<li>' . $menuToggleButton . '</li>';
        echo '</ul">';
    }
    else {
        echo $menuToggleButton;
    }
} );
add_action( 'contentpress/menu::main-menu', function ( Menu $menu ) {

} );
//</editor-fold desc=":: MAIN MENU ::">

/*
 * Comments
 *
 * Remove actions registered by the App
 */
remove_action( 'contentpress/comment/render', '__contentpress_render_comment', 10 );
remove_action( 'contentpress/comment/replies', '__contentpress_render_comment_replies', 10 );
remove_action( 'contentpress/comment/actions', '__contentpress_render_comment_actions', 10 );

/*
 * Register our actions
 */
$themeHelper = new ThemeHelper();
add_action( 'contentpress/submit_comment', [ $themeHelper, 'submitComment' ], 10, 2 );
add_action( 'contentpress/comment/render', [ $themeHelper, 'renderComment' ], 10, 2 );
add_action( 'contentpress/comment/replies', [ $themeHelper, 'renderCommentReplies' ], 10, 1 );
add_action( 'contentpress/comment/actions', [ $themeHelper, 'renderCommentActions' ], 10, 2 );
unset( $themeHelper );



/*
* [ADMIN]
* Add the Theme options menu item under Themes in the admin menu
*/
add_action( 'contentpress/admin/sidebar/menu/themes', function () {
    if ( cp_current_user_can( 'manage_options' ) ) {
        ?>
        <li>
            <a class="treeview-item <?php App\Helpers\MenuHelper::activateSubmenuItem( 'admin.themes.contentpress-default-theme-options' ); ?>"
               href="<?php esc_attr_e( route( 'admin.themes.contentpress-default-theme-options' ) ); ?>">
                <?php esc_html_e( __( 'cpdt::m.Theme Options' ) ); ?>
            </a>
        </li>
        <?php
    }
}, 800 );
