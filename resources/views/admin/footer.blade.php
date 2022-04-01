<!--START footer-->
<div class="footer-space"></div>
<footer class="footer{{auth()->check() == true && (is_null(request()->cookie('sidebar')) || request()->cookie('sidebar')) ? ' with-sidebar' : ''}}">
    <div class="wrap">
        <div class="footer-left">
            <div class="footer-info footer-universal-block">
                <span class="footer-start-txt">E-mail:</span>
                <a href="mailto:info@webit.md" target="_blank" class="footer-txt">info@webit.md</a>
            </div>
            <div class="footer-info footer-universal-block">
                <span class="footer-start-txt">Tel:</span>
                <a href="callto:37322908108" target="_blank" class="footer-txt">+373 22 908 108</a>
            </div>
            <div class="footer-info footer-universal-block">
                <span class="footer-start-txt">Mob:</span>
                <a href="callto:37369304171" target="_blank" class="footer-txt">+373 069 304 171</a>
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-copyright footer-universal-block">
                <span>&copy; 2007 - {{date('Y')}} WEBIT.MD</span>
            </div>
            <div class="footer-logo footer-universal-block">
                <a href="http://webit.md">
                    <img src="{{asset('admin-assets/img/footer-logo.png')}}" alt="logo">
                </a>
            </div>
        </div>
    </div>
</footer>
<!--END footer-->

@if(auth()->check() == true)
    <!--modal-settings-->
    <div style="display: none;">
        <div class="modal modal-settings" id="modal-settings">
            <div class="modal-top">
                <div class="modal-title">Settings</div>
                <div class="modal-close arcticmodal-close"></div>
            </div>
            <div class="modal-center">
                <div class="modal-text">
                    @if(!is_null($menu))
                        @foreach($menu as $m)
                            @if($m->modulesId->alias == 'modules-constructor')
                                <a href="{{url($lang, ['back', $m->modulesId->alias])}}" class="modules" id="{{$m->modulesId->alias}}" title="{{$m->name ?? '' }}"></a>
                            @endif
                            @if($m->modulesId->alias == 'sitemap')
                                <a href="{{url($lang, ['back', $m->modulesId->alias])}}" class="sitemap" id="{{$m->modulesId->alias}}" title="{{$m->name ?? '' }}"></a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--END modal-settings-->
@endif

<button class="go-top"></button>

<div class="alert-parent">
    @if (session('feedform'))
        <div class="alert-notification" id="alert-feedform"><a
                    href="{{url($lang, ['back', 'feedform'])}}">{!! session()->get('feedform') !!}</a></div>
    @else
        <div class="alert-notification hidden" id="alert-feedform"><a
                    href="{{url($lang, ['back', 'feedform'])}}"></a></div>
    @endif
    @if (session('comment'))
        <div class="alert-notification" id="alert-comments"><a
                    href="{{url($lang, ['back', 'comments'])}}">{!! session()->get('comment') !!}</a></div>
    @else
        <div class="alert-notification hidden" id="alert-comments"><a
                    href="{{url($lang, ['back', 'comments'])}}"></a></div>
    @endif
</div>

<div class="remove-many-object">
    <span data-msg="{{trans('variables.remove-all')}}"></span>
</div>

<div class="restore-many-object">
    <span data-msg="{{trans('variables.remove-all')}}"></span>
</div>

<div id="wait-sitemap" class="hidden">{{trans('variables.wait_sitempa_msg')}}</div>

@if (session()->has('message'))
    <div class="alert alert-info">{!! session()->get('message') !!}</div>
@endif
@if (session()->has('error-message'))
    <div class="error-alert alert-info">{!! session()->get('error-message') !!}</div>
@endif
<div class="alert json-info"></div>
<div class="alert json-error"></div>