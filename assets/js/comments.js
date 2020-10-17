//#! Comments
jQuery( function ($) {
    "use strict";

    var locale = ( typeof ( window.CommentsLocale ) !== 'undefined' ? window.CommentsLocale : false );
    if ( !locale ) {
        throw new Error( 'CommentsLocale not found.' );
    }

    $( '.comment-actions .js-comment-reply' ).on( 'click', function (ev) {
        ev.preventDefault();
        var self = $( this ),
            postID = self.data( 'postId' ),
            commentId = self.data( 'commentId' ),
            commentForm = $( '.comment-form' ),
            commentBodyWrap = self.parents( '.comment-body' ).first();

        var cForm = commentForm.clone();
        commentForm.hide();

        var cancelButton = cForm.find( '.js-btn-cancel' );
        if ( cancelButton ) {
            cancelButton
                .removeClass( 'hidden' )
                .on( 'click', function (ev) {
                    if ( confirm( locale.confirm_cancel ) ) {
                        cForm.remove();
                        commentForm.show();
                    }
                } );
        }
        cForm.append( '<input type="hidden" name="reply_to_comment_id" value="' + commentId + '"/>' );
        cForm.insertAfter( commentBodyWrap );
    } );

    $( '[data-confirm]' ).on( 'click', function (ev) {
        ev.preventDefault();
        var self = $( this ),
            formID = self.attr( 'data-form-id' ),
            message = self.attr( 'data-confirm' ),
            theForm = $( '#' + formID );
        if ( message && message.length ) {
            if ( confirm( message ) ) {
                theForm.trigger( 'submit' );
            }
        }
        else {
            theForm.trigger( 'submit' );
        }
    } );
} );
