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
            <div class="text-block-text">
                {!! @$parent_menu->itemBylang->body ?? '' !!}
            </div>
        </div>
    </div>

@stop

@include('front.footer')
