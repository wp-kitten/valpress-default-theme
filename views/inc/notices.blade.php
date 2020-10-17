@if( session('message') || ($errors && $errors->any() ) )
    @if( session('message'))
        <div class="alert alert-{{session('message.class')}}">
            <span>{{session('message.text')}}</span>
        </div>
    @endif

    @if ($errors && $errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">
                <span>{{$error}}</span>
            </div>
        @endforeach
    @endif
@endif
