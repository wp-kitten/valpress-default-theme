<?php
define( 'DEFAULT_THEME_DIR_PATH', untrailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) );
define( 'DEFAULT_THEME_DIR_NAME', basename( dirname( __FILE__ ) ) );

require_once( DEFAULT_THEME_DIR_PATH . '/src/ThemeHelper.php' );
require_once( DEFAULT_THEME_DIR_PATH . '/controllers/DefaultThemeController.php' );
require_once( DEFAULT_THEME_DIR_PATH . '/theme-hooks.php' );

cp_add_image_size( '55', [ 'w' => 55 ] );
cp_add_image_size( 'w210', [ 'w' => 210 ] );
cp_add_image_size( 'w289', [ 'w' => 289 ] );
cp_add_image_size( 'w350', [ 'w' => 350 ] );
cp_add_image_size( 'w510', [ 'w' => 510 ] );
cp_add_image_size( 'w690', [ 'w' => 690 ] );
cp_add_image_size( 'w825', [ 'w' => 825 ] );


function cpdt_theme_submit_comment( \App\Http\Controllers\Controller $controller, $postID )
{
    $post = \App\Models\Post::find( $postID );
    if ( !$post ) {
        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpdt::m.Sorry, an error occurred.' ),
        ] );
    }

    //#! Make sure the comments are open for this post
    if ( !cp_get_post_meta( $post, '_comments_enabled' ) ) {
        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpdt::m.Sorry, the comments are closed for this post.' ),
        ] );
    }

    $request = $controller->getRequest();
    $settings = $controller->getSettings();
    $user = $controller->current_user();

    //#! Make sure the current user is allowed to comment
    if ( !cp_is_user_logged_in() && !$settings->getSetting( 'anyone_can_comment' ) ) {
        return redirect()->back()->with( 'message', [
            'class' => 'danger',
            'text' => __( 'cpdt::m.Sorry, you are not allowed to comment for this post.' ),
        ] );
    }

    $replyToCommentID = null;
    if ( isset( $request->reply_to_comment_id ) && !empty( $request->reply_to_comment_id ) ) {
        $replyToCommentID = intval( $request->reply_to_comment_id );
    }

    $commentApproved = false;

    if ( $user && cp_current_user_can( 'moderate_comments' ) ) {
        $commentStatusID = \App\Models\CommentStatuses::where( 'name', 'approve' )->first()->id;
        $commentApproved = true;
    }
    else {
        $csn = $settings->getSetting( 'default_comment_status', 'pending' );
        $commentStatusID = \App\Models\CommentStatuses::where( 'name', $csn )->first()->id;
    }

    $commentData = [
        'content' => $request->comment_content,
        'author_ip' => esc_html( $request->ip() ),
        'user_agent' => esc_html( $request->header( 'User-Agent' ) ),
        'post_id' => intval( $postID ),
        'comment_status_id' => intval( $commentStatusID ),
        'user_id' => ( $user ? $user->getAuthIdentifier() : null ),
        'comment_id' => ( is_null( $replyToCommentID ) ? null : intval( $replyToCommentID ) ),
    ];

    if ( !$user ) {
        $authorName = $request->get( 'author_name' );
        if ( empty( $authorName ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpdt::m.Your name is required.' ),
                'data' => $request->post(),
            ] );
        }
        $authorEmail = $request->get( 'author_email' );
        if ( empty( $authorEmail ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpdt::m.Your email is required.' ),
                'data' => $request->post(),
            ] );
        }
        if ( !filter_var( $authorEmail, FILTER_VALIDATE_EMAIL ) ) {
            return redirect()->back()->with( 'message', [
                'class' => 'danger',
                'text' => __( 'cpdt::m.The specified email address is not valid.' ),
                'data' => $request->post(),
            ] );
        }
        $authorUrl = $request->get( 'author_website' );
        if ( !empty( $authorUrl ) ) {
            if ( !filter_var( $authorUrl, FILTER_VALIDATE_URL ) || ( false === strpos( $authorUrl, '.' ) ) ) {
                return redirect()->back()->with( 'message', [
                    'class' => 'danger',
                    'text' => __( 'cpdt::m.The specified website URL is not valid.' ),
                    'data' => $request->post(),
                ] );
            }
        }

        $commentData[ 'author_name' ] = $authorName;
        $commentData[ 'author_email' ] = $authorEmail;
        $commentData[ 'author_url' ] = ( empty( $authorUrl ) ? null : wp_strip_all_tags( $authorUrl ) );
        $commentData[ 'author_ip' ] = $request->ip();
        $commentData[ 'user_agent' ] = esc_html( $request->header( 'User-Agent' ) );
    }

    $comment = \App\Models\PostComments::create( $commentData );

    if ( $comment ) {
        //#! If approved
        $m = __( 'cpdt::m.Comment added.' );
        if ( !$commentApproved ) {
            $m = __( 'cpdt::m.Your comment has been added and currently awaits moderation.' );
        }

        return redirect()->back()->with( 'message', [
            'class' => 'success',
            'text' => $m,
        ] );
    }

    return redirect()->back()->with( 'message', [
        'class' => 'danger',
        'text' => __( 'cpdt::m.The comment could not be added.' ),
    ] );
}
