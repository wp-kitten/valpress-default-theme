<?php

namespace App\Themes\ContentPressDefaultTheme;

use App\Helpers\ImageHelper;
use App\Helpers\Theme;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CommentStatuses;
use App\Models\Options;
use App\Models\Post;
use App\Models\PostComments;

class ThemeHelper
{
    public function getPostImageOrPlaceholder( Post $post, $sizeName = '', $imageClass = 'image-responsive', $imageAttributes = [] )
    {
        $placeholder = '<img src="' . $this->asset( 'assets/img/placeholder.png' ) . '" alt="" class="' . $imageClass . '"/>';
        if ( cp_post_has_featured_image( $post ) ) {
            $img = ImageHelper::getResponsiveImage( $post, $sizeName, $imageClass, $imageAttributes );
            if ( empty( $img ) ) {
                return $placeholder;
            }
            return $img;
        }
        return $placeholder;
    }

    public function getCategoryImageOrPlaceholder( Category $category )
    {
        if ( $imageUrl = cp_get_category_image_url( $category->id ) ) {
            return $imageUrl;
        }
        return $this->themeClass->url( 'assets/img/placeholder.png' );
    }

    /**
     * @return Theme
     */
    public function getThemeClass()
    {
        return $this->themeClass;
    }

    public function asset( string $path )
    {
        return cp_theme_url( DEFAULT_THEME_DIR_NAME, $path );
    }

    /**
     * Check to see whether the main demo is currently installing or not
     * @return bool|mixed
     */
    public function mainDemoInstalling()
    {
        return ( new Options() )->getOption( DEFAULT_THEME_MAIN_DEMO_INSTALLING_OPT_NAME, false );
    }

//<editor-fold desc=":: COMMENTS ::"

    /**
     * Submit a comment
     * @param Controller $controller
     * @param int $postID
     *
     * @hooked add_action( 'contentpress/submit_comment', [$themeHelper, 'submitComment'], 10, 2 );
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitComment( Controller $controller, int $postID )
    {
        $post = Post::find( $postID );
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
            $commentStatusID = CommentStatuses::where( 'name', 'approve' )->first()->id;
            $commentApproved = true;
        }
        else {
            $csn = $settings->getSetting( 'default_comment_status', 'pending' );
            $commentStatusID = CommentStatuses::where( 'name', $csn )->first()->id;
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

        $comment = PostComments::create( $commentData );

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

    /**
     * Render a comment
     * @param PostComments $comment
     * @param bool $withReplies
     *
     * @hooked add_action( 'contentpress/comment/render', [$themeHelper, 'renderComment'], 10, 2 );
     */
    public function renderComment( PostComments $comment, $withReplies = true )
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
                        <h6 class="author-name">
                            <a href="<?php esc_attr_e( $commentAuthorUrl ); ?>" class="title-link"><?php esc_html_e( $commentAuthorName ); ?></a>
                        </h6>
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

    /**
     * Render a comment's replies
     * @param PostComments $comment
     *
     * @hooked add_action( 'contentpress/comment/replies', [$themeHelper, 'renderCommentReplies'], 10, 1 );
     */
    public function renderCommentReplies( PostComments $comment )
    {
        $replies = PostComments::where( 'post_id', $comment->post->id )
            ->where( 'comment_id', $comment->id )
            ->get();
        if ( $replies && $replies->count() ) {
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
                            <h6 class="author-name">
                                <a href="<?php esc_attr_e( $commentAuthorUrl ); ?>" class="title-link"><?php esc_html_e( $commentAuthorName ); ?></a>
                            </h6>
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

    /**
     * Render a comment's actions
     * @param PostComments $comment
     * @param int $postID
     *
     * @hooked add_action( 'contentpress/comment/actions', [$themeHelper, 'renderCommentActions'], 10, 2 );
     */
    public function renderCommentActions( PostComments $comment, int $postID )
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

            <?php if ( cp_comments_open( Post::find( $postID ) ) ) { ?>
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
//</editor-fold desc=":: COMMENTS ::"
}
