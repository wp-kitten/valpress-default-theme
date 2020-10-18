@if ( cp_comments_open( $post ) )
    @php
        $name = '';
        $email = '';
        $website = '';
        $message = '';
        if( session('message') && session('message.data') ) {
            $data = session('message.data');
            $name = $data['author_name'];
            $email = $data['author_email'];
            $website = $data['author_website'];
            $message = $data['comment_content'];
        }
    @endphp

    <form action="{{route( 'app.submit_comment', $post->id )}}#_comments" class="comment-form mt-3 mb-3 p-4 bg-dark text-light" method="post">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <h3 class="mb-4">{{__( 'cpdt::m.Leave a Reply' )}}</h3>
                    </div>
                </div>

                @if( ! cp_is_user_logged_in() )
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="cp-author-name" class="input-label">{{__( 'cpdt::m.Your Name' )}}</label>
                            <input id="cp-author-name" name="author_name" class="form-control" required="required" type="text" value="{{$name}}"/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="cp-author-email" class="input-label">{{__( 'cpdt::m.Email Address' )}}</label>
                            <input id="cp-author-email" name="author_email" class="form-control" required="required" type="email" value="{{$email}}"/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="cp-author-website" class="input-label">{{__( 'cpdt::m.Website' )}}</label>
                            <input id="cp-author-website" name="author_website" class="form-control" type="url" value="{{$website}}"/>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <div class="form-group">
                        <label for="cp-comment-content" class="input-label">{{__( 'cpdt::m.Message' )}}</label>
                        <textarea id="cp-comment-content" name="comment_content" required="required" class="form-control mb-0" rows="7">{!! $message !!}</textarea>
                    </div>
                </div>
                <div class="col-12 text-right">
                    <button class="btn btn-md btn-warning text-uppercase mr-3 js-btn-cancel hidden" id="" type="button">{{__( 'cpdt::m.Cancel' )}}</button>
                    <button class="btn btn-primary text-uppercase" type="submit">{{__( 'cpdt::m.Submit' )}}</button>
                </div>
            </div>
        </div>
    </form>

@else
    <div class="alert alert-info mt-3">
        <span>{{__( 'cpdt::m.Comments are closed.' )}}</span>
    </div>
@endif
