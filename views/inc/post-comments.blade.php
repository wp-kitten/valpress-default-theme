{{--
    The comments template
    [REQUIRES: Post $post, Settings $settings]
--}}

<div class="comments-container mt-5 mb-5">
    <h3 class="mb-4 comments-title" id="_comments">{{__('cpdt::m.Comments')}}</h3>

    @include('inc.notices')

    {{-- Comments List --}}
    <div class="comment-list">
        @php
            $walker = new App\Helpers\CommentsWalker($post->id, $post->post_type->name, [
                            'per_page' => $settings->getSetting('comments_per_page', 10),
                            'comment_status' => (new \App\Models\CommentStatuses())->where('name', 'approve')->first()->id,
                            'sort' => 'DESC',
                        ]);
            if($walker->hasComments()){
                // Render comments
                $__comments = $walker->renderComments();
                // Render pagination
                if($__comments){
                    $__comments->render();
                }
            }
            else {
                wp_kses_e('<div class="alert alert-info">'.__('cpdt::m.No comments found.').'</div>', ['div' => [
                 'class' => []
                 ] ] );
            }
        @endphp

    </div> <!-- //.comment-list -->

    {{-- Comment Reply Form --}}
    {!! cp_comments_reply_form($post, 'inc.comment_form') !!}
    <!-- //.comment-form -->
</div>
<!-- end .comments-container -->
