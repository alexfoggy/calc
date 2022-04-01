@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')
    <section class="calculator-page">
        <div class="container">
            {{ Breadcrumbs::render('calc_parent',$subject) }}
        </div>
        <div class="container">
            <div class="ttl mb-3 mt-2">{{$subject->itemByLang->name ?? ''}}</div>
            <div class="list-fav">
                @foreach($subject->children as $one_calc)
                <div class="fav-item">
                    <a href="{{url($lang,['calculator',$subject->alias,$one_calc->alias])}}">
                        {{$one_calc->itemByLang->name ?? ''}}
                    </a>
                    <span class="fav-status add-to-fav-it <?php if(checkIfWishExist($one_calc->id) == true): ?> active <?php endif; ?>" data-id="{{$one_calc->id}}">
                   <svg>
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#saved')}}"></use>
                </svg>
            </span>
                </div>
                @endforeach
            </div>
        </div>
{{--        <div class="container">--}}
{{--            <div class="reg-info position-relative overflow-hidden">--}}
{{--                <h3>Зарегистрируйся и смотри свои избранные с любого устройтва</h3>--}}
{{--                <h4>Используя социальные сети</h4>--}}
{{--                <div class="social-in">--}}
{{--                    <div class="facebook"><img src="img/icons/fb.svg" alt=""></div>--}}
{{--                    <div class="google"><img src="img/icons/google.svg" alt=""></div>--}}
{{--                </div>--}}
{{--                <div class="icon-background">--}}
{{--                    <svg>--}}
{{--                        <use xlink:href="/svg/sprite.svg#protection"></use>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </section>


@stop

@include('front.footer')
