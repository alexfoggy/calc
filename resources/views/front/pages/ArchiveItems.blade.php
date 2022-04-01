@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


{{--    {{ Breadcrumbs::render('order',$lang_id) }}--}}


<section class="archive-parent-page lish-archive">

    <div class="container pt-4">
        <h1>{{$goods_subject->itemByLang->name ?? ''}}</h1>
        <div class="d-flex flew wrap my-5">
            @foreach($goods_subject->goodsItemId as $one_item)
            <a href="{{url($lang,['archive',$goods_subject->alias,$one_item->alias])}}" class="archive-url">
                {{$one_item->itemByLang->name ?? ''}}
            </a>
            @endforeach
        </div>
    </div>

</section>




@stop

@include('front.footer')
