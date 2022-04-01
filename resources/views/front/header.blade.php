@section('header') <!-- Main Header start -->

<!-- Main Header start -->
<header class="main-header">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="logo">
                <a href="{{url($lang)}}">
                    <img src="{{asset('front-assets/img/logo/logo.svg')}}" alt="">
                </a>
            </div>
            <div class="d-flex align-items-center">
                <ul class="menu mob-no">
                    @foreach($header_menu as $one_menu)
                        @if($one_menu->alias == 'calculator')
                            <li class="with-droppy-desktop">
                                <a href="" class="parent-droppy">{{$one_menu->itemByLang->name ?? ''}}<svg class="ml-1">
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#arr-right')}}"></use>
                                    </svg></a>
                                <div class="droppy-block">
                                    <ul>
                                        @foreach($calc_list as $one_calc)
                                        <li>
                                            <a href="{{url($lang,['calculator',$one_calc->alias])}}">{{$one_calc->itemByLang->name ?? ''}}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                            @else
                            <li>
                                <a href="{{url($lang,$one_menu->alias)}}">{{$one_menu->itemByLang->name ?? ''}}</a>
                            </li>
                            @endif

                    @endforeach

                </ul>
               {{-- <div class="in-block mob-no">
                    <div class="icon-openner">
                        <svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#inacc')}}"></use>
                        </svg>
                    </div>
                    <div class="drop-it">
                        @if(Session::get('session-front-user'))
                            <div class="d-flex flex-column user-info">
                                <h4>{{$user_info->name}} {{$user_info->last_name}}</h4>
                                <div class="email-user">({{$user_info->email}})</div>
                            </div>
                        <div class="settings-urls">
                                <a href="">Настройки</a>
                                <a href="{{url($lang,'logout')}}">Выход</a>
                            </div>
                            @else
                        <p>Войти с помощью</p>
                        <div class="social-in">
                            <div class="facebook"><a href="{{url('login','facebook')}}"><img src="{{asset('front-assets/img/icons/fb.svg')}}" alt=""></a></div>
                            <div class="google"><a href="{{url('login','google')}}"><img src="{{asset('front-assets/img/icons/google.svg')}}" alt=""></a></div>
                        </div>
                            @endif
                    </div>
                </div>--}}
                <div class="search-button mob-no">
                    <svg>
                        <use xlink:href="{{asset('front-assets//svg/sprite.svg#search')}}"></use>
                    </svg>
                </div>
                {{--<div class="langs mob-no">
                    <span>{{$lang}}</span>
                    <div class="lang-drop">
                        @foreach($lang_list as $one_lang)
                            @if($one_lang->id != $lang_id)
                        <a href="{{ count(request()->segments()) > 0 ? str_replace($lang, $one_lang->lang, request()->fullUrl()) : url($one_lang->lang) }}">{{$one_lang->lang}}</a>
                            @endif
                        @endforeach
                    </div>
                </div>--}}
                <div class="mob-go">
                    <div class="burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>


</header>

{{--<div class="you-used">--}}
{{--    <h3>История посищений</h3>--}}
{{--    <ul class="history">--}}
{{--        <li>--}}
{{--            <a href="">Калькулятор веса</a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="">Подсчет НДС</a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="">Калькулятор веса</a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="">Подсчет НДС</a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="">Калькулятор веса</a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a href="">Подсчет НДС</a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</div>--}}

<div class="search-popup">
    <div class="plenka"></div>
    <div class="search-form">
        <form action="{{url($lang,'search')}}">
            <input type="text" placeholder="Поиск..." name="q">
            <button>
                <svg>
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#search')}}"></use>
                </svg>
            </button>
            <div class="smart-search">
                <div class="loading-search">
                    <div class="lds-facebook"><div></div><div></div><div></div></div>
                </div>
               <div class="search-result">

                </div>
            </div>
        </form>
    </div>
</div>

<div class="mobile-menu">
   {{-- <div class="langs-block">
      <span>
        Мы так же дотупны на:
      </span>

        <div class="langs-line">
            @foreach($lang_list as $one_lang)
                @if($one_lang->id != $lang_id)
                    <a href="{{ count(request()->segments()) > 0 ? str_replace($lang, $one_lang->lang, request()->fullUrl()) : url($one_lang->lang) }}">{{$one_lang->lang}}</a>
                @endif
            @endforeach
        </div>
    </div>--}}
{{--    <div class="social-in">--}}
{{--        <div class="log-in">Вход</div>--}}
{{--        <div class="reg-in">Регистрация</div>--}}

{{--    </div>--}}
    <div class="d-flex justify-content-center">
        <div class="search-oppener-mobile button-open" data-open='search-popup'>
            <svg>
                <use xlink:href="{{asset('front-assets/svg/sprite.svg#search')}}"></use>
            </svg>
            <span>Поиск</span>

            <svg>
                <use xlink:href="{{asset('front-assets/svg/sprite.svg#search')}}"></use>
            </svg>
        </div>
    </div>
    <ul>
        @foreach($header_menu as $one_menu)
            @if($one_menu->alias == 'calculator')
                <li class="with-drop">
                    <a href="javascript:;" class="droppy-action">{{$one_menu->itemByLang->name ?? ''}}</a>
                    <ul class="droppy">
                        @foreach($calc_list as $one_calc)
                            <li>
                                <a href="{{url($lang,['calculator',$one_calc->alias])}}">{{$one_calc->itemByLang->name ?? ''}}</a>
                            </li>
                        @endforeach

                    </ul>

                </li>
                @else
            <li>
                <a href="{{url($lang,$one_menu->alias)}}">{{$one_menu->itemByLang->name ?? ''}}</a>
            </li>
            @endif
        @endforeach

    </ul>
</div>
<!-- Main Header end -->
@stop
