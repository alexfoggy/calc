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

    <section class="about about-page pb-5">
        <div class="container">
            <h1 class="h2"><span>{{ @$parent_menu->itemBylang->name ?? '' }}</span></h1>
            <div class="layout-row flex-wrap">
                <div class="about-text">
                    <div class="about-img">
                        <img src="{{ $parent_menu->oImage->img ? asset('upfiles/menu/'. $parent_menu->oImage->img) : asset('front-assets/img/no-image.png') }}"
                             alt="Agro-tehnica">
                    </div>
                    {!! @$parent_menu->itemByLang->body ?? '' !!}
                </div>
            </div>
        </div>
    </section>


    @if(!empty($partners) && count($partners))
        <section class="partners pb-8">
            @include('front.templates.partners-template')
        </section>
    @endif


@stop

@include('front.footer')
