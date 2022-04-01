@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    @if($parent_menu->itemByLang)
        <div class="breadcrumbs-wrapper">
            <div class="container">
                {{ Breadcrumbs::render($parent_menu->alias, $parent_menu) }}
            </div>
        </div>
    @endif

    <div class="text-block">
        <div class="container">
            <h1 class="h2"><span>{{ @$parent_menu->itemByLang->name ?? '' }}</span></h1>
            <div class="sitemapBlock">
                @foreach($menu as $one_menu)
                    <a href="{{url($lang,$one_menu->alias)}}">{{$one_menu->itemByLang->name ?? ''}}</a>
                @endforeach

                @foreach($calc_subjects as $one_menu)
                    <a href="{{url($lang,['calculator',$one_menu->alias])}}">{{$one_menu->itemByLang->name ?? ''}}</a>
                @endforeach

                @foreach($calcs as $one_menu)
                    <a href="{{url($lang,['calculator',$one_menu->parent->alias,$one_menu->alias])}}">{{$one_menu->itemByLang->name ?? ''}}</a>
                @endforeach
            </div>
        </div>
    </div>

@stop

@include('front.footer')
