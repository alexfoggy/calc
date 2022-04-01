<!--START header-->
<header class="header{{auth()->check() == true && (is_null(request()->cookie('sidebar')) || request()->cookie('sidebar')) ? ' with-sidebar' : ''}}">
    <div class="wrap">
        <div class="header-left">
            <a href="{{url($lang, 'back')}}">
                <img src="{{asset('admin-assets/img/logo.png')}}" alt="logo">
            </a>
        </div>
        <div class="header-right">
            <div class="header-right-block header-lang">
                @foreach($lang_list as $one_lang)
                    <a style="padding-left: 20px;" href="{{url($one_lang->lang, Arr::except(request()->segments(), 0))}}" {{($lang == $one_lang->lang) ? 'class=active' : ''}}><span>{{strtolower($one_lang->lang)}}</span></a>
                @endforeach
            </div>
            <div class="header-right-block header-go-site">
                <a href="{{url('/', $lang)}}" target="_blank">{{trans('variables.go_to_the_site')}}</a>
            </div>
            @if(auth()->check() == true)
                <div class="header-right-block header-user-info">
                    <div class="header-avatar">
                        @if(!empty(auth()->user()->img))
                            <img src="{{asset("upfiles/admin_user/s/" . auth()->user()->img)}}"
                                 alt="{{auth()->user()->name}}" title="{{auth()->user()->name}}">
                        @else
                            <img src="{{asset("admin-assets/img/avatar.png")}}" alt="avatar">
                        @endif
                    </div>
                    <div class="header-user">
                        <span class="header-start-txt">{{startMessage()}} </span>
                        <span class="header-user-txt">{{auth()->user()->name}}</span>
                    </div>
                </div>
                <div class="header-right-block header-logout">
                    <a href="{{url($lang . '/back/auth/logout')}}" title="{{trans('variables.log_out')}}"></a>
                </div>
            @endif
        </div>
    </div>
</header>

<div class="header-space"></div>
<!--END header-->