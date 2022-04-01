@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

        </div>

        <section class="calculator-page">
            <div class="container">
                {{ Breadcrumbs::render('fav-list',$lang_id) }}
            </div>
            <div class="container">
                <div class="ttl mb-3 mt-2">{{$page->itemByLang->name ?? ''}}</div>
                @if($calcs)
                <div class="list-fav">
                    @foreach($calcs as $one_calc)
                    <div class="fav-item">
                        <a href="{{url($lang,['calculator',$one_calc->parent->alias,$one_calc->alias])}}">
                            {{$one_calc->itemByLang->name ?? ''}}
                        </a>
                        <span class="fav-status active add-to-fav-it wish-{{$one_calc}}" data-id="{{$one_calc->id}}">
                   <svg>
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#saved')}}"></use>
                </svg>
            </span>
                    </div>
                    @endforeach

                </div>
                    @else

        <p>Пусто</p>

                @endif
            </div>
           {{-- @if(Session::get('session-front-user'))


            @else

            <div class="container">
                <div class="reg-info position-relative overflow-hidden">
                    <h3>Зарегистрируйся и смотри свои избранные с любого устройтва</h3>
                    <h4>Используя социальные сети</h4>
                    <div class="social-in">
                        <div class="facebook"><a href="{{url('login','facebook')}}"><img src="{{asset('front-assets/img/icons/fb.svg')}}" alt=""></a></div>
                        <div class="google"><a href="{{url('login','google')}}"><img src="{{asset('front-assets/img/icons/google.svg')}}" alt=""></a></div>
                    </div>
                    <div class="icon-background">
                        <svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#protection')}}"></use>
                        </svg>
                    </div>
                </div>
            </div>
            @endif--}}
        </section>

@stop

@include('front.footer')
