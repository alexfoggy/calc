@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    <section class="mt-5 one_service">
        <div class="container">
            <h1 class="ttl mb-3">Услуга: <span class="red"> {{$curient_project->itemByLang->name ?? ''}} </span></h1>
            <div class="row mt-4 justify-content-center">
                <div class="grid">

                @foreach($curient_project->children as $one_sub_proj)
                        <div class="grid-item">
                    <div class="service-info">
                        <h2>{{$one_sub_proj->itemByLang->name ?? ''}}</h2>
                        <h3>Включает в себя:</h3>
                      {!! $one_sub_proj->itemByLang->body ?? '' !!}
                        <div class="price">
                            Цена: {{$one_sub_proj->itemByLang->h1_title ?? ''}}
                        </div>
                        <div class="qoa">
                            {{ShowLabelById(213,$lang_id)}}
                        </div>
                    </div>
                </div>
                @endforeach
                </div>

            </div>
            @if($curient_project->itemByLang != null)
                @if($curient_project->itemByLang->body != null)
            <div class="text-service mt-5">
                <h2>{{$curient_project->itemByLang->h1_title}}</h2>
             {!! $curient_project->itemByLang->body !!}
            </div>
                    @endif
            @endif
            @if($works && $works->isNotEmpty())
            <div class="">
                <h2 class="ttl">
                    Примеры работ
                </h2>
                <div class="position-relative">
                    <div class="swiper-container exemples">
                        <div class="swiper-wrapper">
                            @foreach($works as $one_work)
                            <div class="swiper-slide">
                                <a href="{{url($lang,['projects',$one_work->alias])}}">
                                    <img src="{{asset('upfiles/gallery/'.$one_work->oImage->img)}}" alt="">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            </div>
                @endif
        </div>
    </section>

@stop

@include('front.footer')
