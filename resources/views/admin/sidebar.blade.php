<!--START sidebar-->
<div class="sidebar-wrap{{(!is_null(request()->cookie('sidebar')) && !request()->cookie('sidebar')) ? ' sidebar-hidden' : ''}}">
    <aside class="sidebar">
        <div class="sidebar-top">
            @if(auth()->check() == true)
                <div class="sidebar-avatar">
                    <a href="{{url($lang, 'back')}}">
                        @if(!empty(auth()->user()->img))
                            <img src="{{asset("upfiles/admin_user/s/" . auth()->user()->img)}}"
                                 alt="{{auth()->user()->name}}" title="{{auth()->user()->name}}">
                        @else
                            <img src="{{asset("admin-assets/img/avatar.png")}}" alt="avatar">
                        @endif
                    </a>
                </div>
                <div class="sidebar-user">
                    {{--<span class="sidebar-start-txt">{{startMessage()}} </span>--}}
                    <span class="sidebar-user-txt">{{auth()->user()->name}}</span>
                </div>
{{--                @if(auth()->user()->root == 1)--}}
                    <div class="sidebar-settings">
                        <a href="#modal-settings" class="getModal">Settings</a>
                    </div>
{{--                @endif--}}
            @endif
        </div>
        @if(!is_null($menu))
            <div class="menu-items-list">
                @foreach($menu as $m)
                    @if($m->modulesId->alias != 'sitemap' && $m->modulesId->alias != 'modules-constructor')
                        <div class="menu-item{{!empty(IfHasChildModules($m->modules_id, $lang_id, $lang)) ? ' has-child' : ''}}">
                            <a href="{{url($lang.'/back', $m->modulesId->alias)}}"
                               {{request()->segment(3) == $m->modulesId->alias ? 'class=active' : ''}} {{'id=' . $m->modulesId->alias}} title="{{!empty(IfHasName($m->modules_id, $lang_id, 'modules')) ? IfHasName($m->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}">
                                {{!empty(IfHasName($m->modules_id, $lang_id, 'modules')) ? IfHasName($m->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}
                                <sup class="alert-info" data-count="{{$m->modulesId->alias == 'feedform' && !empty($new_feedform) && $new_feedform > 0 ? $new_feedform : ''}}{{$m->modulesId->alias == 'comments' && !empty($new_comment) && $new_comment > 0 ? $new_comment : ''}}">{{$m->modulesId->alias == 'feedform' && !empty($new_feedform) && $new_feedform > 0 ? ($new_feedform > 99 ? '99+' : $new_feedform) : ''}} {{$m->modulesId->alias == 'comments' && !empty($new_comment) && $new_comment > 0 ? ($new_comment > 99 ? '99+' : $new_comment) : ''}}</sup>
                                @if(!empty(IfHasChildModules($m->modules_id, $lang_id, $lang)))
                                    <span class="menu-more-items"></span>
                                @endif
                            </a>
                            @if(!empty(IfHasChildModules($m->modules_id, $lang_id, $lang)))
                                <div class="hidden-menu-items">
                                    <div class="menu-item">
                                        <a href="{{url($lang.'/back', $m->modulesId->alias)}}"
                                           {{request()->segment(3) == $m->modulesId->alias ? 'class=active' : ''}} {{'id=' . $m->modulesId->alias}} title="{{!empty(IfHasName($m->modules_id, $lang_id, 'modules')) ? IfHasName($m->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}">
                                            {{!empty(IfHasName($m->modules_id, $lang_id, 'modules')) ? IfHasName($m->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}
                                        </a>
                                    </div>
                                    {!! IfHasChildModules($m->modules_id, $lang_id, $lang) !!}
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </aside>
    <button type="button" class="hide-menu-btn{{!request()->cookie('sidebar') ? ' close' : ''}}"></button>
</div>
<!--END sidebar-->