@extends('admin.layouts.base')

@section('page-title')
    <title>{{__('cpdt::m.Theme Options')}}</title>
@endsection

@section('main')
    <div class="app-title">
        <div class="cp-flex cp-flex--center cp-flex--space-between">
            <div>
                <h1>{{__('cpdt::m.Theme Options')}}</h1>
            </div>

            @if(cp_current_user_can('manage_options'))
                <form method="post" action="{{route('admin.themes.contentpress-default-theme-options.save')}}" class="cpdt-theme-options-page-wrap">
                    @csrf
                    <ul class="list-unstyled list-inline mb-0">
                        <li>
                            <button type="submit" class="btn btn-primary">{{__('cpdt::m.Save')}}</button>
                        </li>
                    </ul>
                </form>
            @endif
        </div>
    </div>

    @include('admin.partials.notices')

    @if(cp_current_user_can('manage_options'))

        {{-- GENERAL OPTIONS --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="tile">
                    <h3 class="tile-title">{{__('cpdt::m.Main Demo')}}</h3>

                    <div class="form-group">

                        <p>{{__("cpdt::m.You can use the button bellow to install the main demo. This action will have as result the following:")}}</p>
                        <div>
                            <ul style="list-style-type: circle">
                                <li>{{__("cpdt::m.Categories will be created")}}</li>
                                <li>{{__("cpdt::m.Tags will be created")}}</li>
                                <li>{{__("cpdt::m.Posts will be created")}}</li>
                                <li>{{__("cpdt::m.Pages will be created")}}</li>
                                <li>{{__("cpdt::m.Menus will be created")}}</li>
                                <li>{{__("cpdt::m.Reading settings will be updated")}}</li>
                            </ul>
                        </div>

                        @if($previous_install)
                            <div class="alert alert-warning">
                                <p class="mb-0">{{__("cpdt::m.Looks like you've already installed the main demo once. Installing it again will duplicate entries if they exist.")}}</p>
                            </div>
                        @endif

                        <form method="post" action="{{route('admin.themes.contentpress-default-theme-options.install-main-demo')}}" class="cpdt-theme-options-page-wrap">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{__("cpdt::m.Install Main Demo")}}</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
