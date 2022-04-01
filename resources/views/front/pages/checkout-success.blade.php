@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    <div class="center">
        <div class="py-100">
            <img src="{{asset('front-assets/img/icons/done.png')}}" alt="">
            <div class="done-succes">{{ShowLabelById(109,$lang_id)}}</div>
            <div class=""><a href="{{url($lang)}}" class="back-main"><img src="{{asset('front-assets/img/icons/arr-left.png')}}" alt="">{{ShowLabelById(110,$lang_id)}}</a></div>
        </div>

    </div>

@stop

@include('front.footer')
