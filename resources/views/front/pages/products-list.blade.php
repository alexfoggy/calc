@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    <section class="our-works" id='our-works'>
        <div class="container">
            <div class="ttl">Наши работы</div>
            <div class="row">
                @php
                    $i = 0;
                @endphp
                @foreach($projects_list->goodsItemId as $one_project)

                    @if($loop->index == 2 || $loop->index == 3 || $loop->index == 4)
                        <div class="col-lg-4">
                            <a href="{{url($lang,['projects',$one_project->alias])}}">
                                <img src="{{asset('upfiles/gallery/'.optional($one_project->oImage)->img)}}" alt="">
                                <h2>{{$one_project->itemByLang->name}}</h2>
                            </a>
                        </div>
                    @else
                        <div class="col-lg-6">
                            <a href="{{url($lang,['projects',$one_project->alias])}}">
                                <img src="{{asset('upfiles/gallery/m/'.optional($one_project->oImage)->img)}}" alt="">
                                <h2>{{$one_project->itemByLang->name}}</h2>
                            </a>
                        </div>

                    @endif



                @endforeach
{{--                <div class="col-lg-12 d-flex justify-content-center">--}}
{{--                    <a href="{{url($lang,'projects')}}" class="more">--}}
{{--                        Смотреть больше проектов--}}
{{--                    </a>--}}
{{--                </div>--}}
            </div>
        </div>
    </section>

@stop

@include('front.footer')
