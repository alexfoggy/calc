@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    {{ Breadcrumbs::render($parent_menu->alias, $parent_menu) }}

<div class="container">
    <h1>{{$parent_menu->itemByLang->name}}</h1>
    <div class="row mt-3">
        @if($goods_special)
        @foreach($goods_special as $one_goods)
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
                @include('front.templates.goods-template')
            </div>
        @endforeach
        @endif
    </div>
</div>











@stop

@include('front.footer')
