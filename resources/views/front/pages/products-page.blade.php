@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    <section class="mt-5 one_project">
        <div class="container">
            <div class="ttl mb-0">Проект {{$curient_project->itemByLang->name}}</div>
            <h2>Услуга: <span>{{$curient_project->itemByLang->short_descr}}</span></h2>
            @if(!empty($techs))
            <div class="ablts">
                <h3>Технологи используемы в данном проекте</h3>
                <div class="d-flex flex-wrap">
                    @foreach($techs as $one_tech)
                    <a href="javascript:;">{{$one_tech->name}}</a>
                        @endforeach

                </div>
            </div>
            @endif

            @if($curient_project)
                @foreach($curient_project->allImages as $one_image)
                    @if($loop->index > 0)
            <div class="project-image">
                <a href="{{asset('upfiles/gallery/'.$one_image->img)}}" data-fancybox="gallery">
                    <img src="{{asset('upfiles/gallery/'.$one_image->img)}}" alt="">
                </a>
            </div>
                    @endif
                @endforeach
            @endif
        </div>
    </section>


@stop

@include('front.footer')
