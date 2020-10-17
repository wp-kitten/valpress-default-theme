<?php

use App\Helpers\ScriptsManager;
use App\Helpers\Theme;
use App\Models\Menu;
use App\Models\Options;

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
                    echo '<a href="' . esc_attr( cp_get_tag_link( $tag ) ) . '" class="tag-link ml-2">' . $tag->name . '</a>';
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
    $menuToggleButton = '<a href="#" class="icon btn-toggle-nav js-toggle-menu" title="' . esc_attr( __( 'np::m.Toggle menu' ) ) . '">&#9776;</a>';

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

add_action( 'contentpress/submit_comment', 'cpdt_theme_submit_comment', 10, 2 );

/*
 * Frontend :: Comments
 *
 * These can be overridden by themes/plugins
 */
remove_action( 'contentpress/comment/render', '__contentpress_render_comment', 10 );
remove_action( 'contentpress/comment/replies', '__contentpress_render_comment_replies', 10 );
remove_action( 'contentpress/comment/actions', '__contentpress_render_comment_actions', 10 );

add_action( 'contentpress/comment/render', 'cpdt__contentpress_render_comment', 10, 2 );
function cpdt__contentpress_render_comment( \App\Models\PostComments $comment, $withReplies = true )
{
    $commentUserID = $comment->user_id;
    $commentAuthorName = ( $commentUserID ? $comment->user->display_name : $comment->author_name );
    $commentAuthorUrl = ( $commentUserID ? cp_get_user_meta( '_website_url', $commentUserID ) : $comment->author_url );
    $authorImageUrl = '';
    ?>
    <div class="comment" id="comment-<?php esc_attr_e( $comment->id ); ?>">
        <div class="comment-body bg-white d-flex flex-column flex-md-row align-content-md-start">
            <div class="author-vcard">
                <?php
                if ( $commentUserID ) {
                    $authorImageUrl = cp_get_user_profile_image_url( $commentUserID );
                }
                if ( empty( $authorImageUrl ) ) {
                    $authorImageUrl = asset( 'images/placeholder-200.jpg' );
                }
                ?>
                <img src="<?php esc_attr_e( $authorImageUrl ); ?>" class="img-circle" width="120" height="120" alt=""/>
            </div>
            <div class="comment-content pl-3">
                <div class="comment-meta">
                    <h4 class="">
                        <a href="<?php esc_attr_e( $commentAuthorUrl ); ?>" class="title-link"><?php esc_html_e( $commentAuthorName ); ?></a>
                    </h4>
                    <time datetime="<?php esc_attr_e( $comment->created_at ); ?>" class="text-grey font-smaller"><?php esc_html_e( cp_the_date( $comment, true ) ); ?></time>
                </div>
                <div class="comment-text mt-4 mb-4"><?php echo $comment->content; ?></div>
                <?php do_action( 'contentpress/comment/actions', $comment, $comment->post->id ); ?>
            </div> <!-- //.comment-content -->

        </div> <!-- //.comment-body -->

        <?php
        if ( $withReplies ) {
            echo '<div class="comment-replies">';
            do_action( 'contentpress/comment/replies', $comment );
            echo '</div>';
        }
        ?>
    </div>
    <?php
}

add_action( 'contentpress/comment/replies', 'cpdt__contentpress_render_comment_replies', 10, 1 );
function cpdt__contentpress_render_comment_replies( \App\Models\PostComments $comment )
{
    $replies = \App\Models\PostComments::where( 'post_id', $comment->post->id )->where( 'comment_id', $comment->id )->get();
    if ( $replies ) {
        foreach ( $replies as $reply ) {
            $commentUserID = $reply->user_id;
            $commentAuthorName = ( $commentUserID ? $reply->user->display_name : $reply->author_name );
            $commentAuthorUrl = ( $commentUserID ? cp_get_user_meta( '_website_url', $commentUserID ) : $reply->author_url );
            $authorImageUrl = '';
            ?>
            <div class="comment-body comment-reply bg-white d-flex flex-column flex-md-row align-content-md-start">
                <div class="author-vcard">
                    <?php
                    if ( $commentUserID ) {
                        $authorImageUrl = cp_get_user_profile_image_url( $commentUserID );
                    }
                    if ( empty( $authorImageUrl ) ) {
                        $authorImageUrl = asset( 'images/placeholder-200.jpg' );
                    }
                    ?>
                    <img src="<?php esc_attr_e( $authorImageUrl ); ?>" class="img-circle" width="120" height="120" alt=""/>
                </div>
                <div class="comment-content pl-3">
                    <div class="comment-meta">
                        <h4 class="">
                            <a href="<?php esc_attr_e( $commentAuthorUrl ); ?>" class="title-link"><?php esc_html_e( $commentAuthorName ); ?></a>
                        </h4>
                        <time datetime="<?php esc_attr_e( $reply->created_at ); ?>" class="text-grey font-smaller"><?php esc_html_e( cp_the_date( $reply, true ) ); ?></time>
                    </div>
                    <div class="comment-text mt-4 mb-4"><?php echo $reply->content; ?></div>
                    <?php do_action( 'contentpress/comment/actions', $reply, $reply->post->id ); ?>
                </div> <!-- //.comment-content -->
            </div> <!-- //.comment-body -->
            <div class="comment-replies">
                <?php do_action( 'contentpress/comment/replies', $reply ); ?>
            </div>
            <?php
        }
    }
}

add_action( 'contentpress/comment/actions', 'cpdt__contentpress_render_comment_actions', 10, 2 );
function cpdt__contentpress_render_comment_actions( \App\Models\PostComments $comment, $postID )
{
    ?>
    <div class="comment-actions text-right">
        <?php if ( cp_current_user_can( 'moderate_comments' ) ) {
            $editLink = cp_get_comment_edit_link( $comment->post, $comment->id );
            ?>
            <a href="#!"
               class="js-comment-delete ml-3 btn btn-danger btn-sm"
               data-comment-id="<?php esc_attr_e( $comment->id ); ?>"
               data-confirm="<?php esc_attr_e( __( "cpdt::m.Are you sure you want to delete this comment?" ) ); ?>"
               data-form-id="<?php esc_attr_e( "form-delete-comment-{$comment->id}" ); ?>">
                <?php esc_html_e( __( 'cpdt::m.Delete' ) ); ?>
            </a>
            <form id="form-delete-comment-<?php esc_attr_e( $comment->id ); ?>"
                  action="<?php echo route( 'app.delete_comment', $comment->id ); ?>"
                  class="hidden"
                  method="post">
                <?php echo csrf_field(); ?>
            </form>

            <a href="<?php esc_attr_e( $editLink ); ?>"
               class="js-comment-edit ml-3 btn btn-warning btn-sm"
               data-post-id="<?php esc_attr_e( $postID ); ?>"
               data-comment-id="<?php esc_attr_e( $comment->id ); ?>">
                <?php esc_html_e( __( 'cpdt::m.Edit' ) ); ?>
            </a>
        <?php } ?>

        <?php if ( cp_comments_open( \App\Models\Post::find( $postID ) ) ) { ?>
            <a href="#!"
               class="js-comment-reply ml-3 btn btn-dark btn-sm"
               data-post-id="<?php esc_attr_e( $postID ); ?>"
               data-comment-id="<?php esc_attr_e( $comment->id ); ?>">
                <?php esc_html_e( __( 'cpdt::m.Reply' ) ); ?>
            </a>
        <?php } ?>
    </div>
    <?php
}
