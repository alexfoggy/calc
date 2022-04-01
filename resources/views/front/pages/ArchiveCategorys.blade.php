@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    {{--    {{ Breadcrumbs::render('order',$lang_id) }}--}}


    <section class="archive-parent-page">

        <div class="container pt-4">
            <h1>{{$goods_subject->itemByLang->name ?? ''}}</h1>
            @foreach($subjects as $one_subject)
            <div class="row-category-archive">
                <h2><a href="{{url($lang,['archive',$one_subject->alias])}}"> {{$one_subject->itemByLang->name ?? ''}} </a></h2>
                <div class="d-flex flew wrap">
                    @foreach($items[$one_subject->id] as $one_item)
                    <a href="{{url($lang,['archive',$one_item->parent->alias,$one_item->alias])}}" class="archive-url">
                        {{$one_item->itemByLang->name ?? ''}}
                    </a>
                        @endforeach

                </div>
            </div>
                @endforeach
        </div>

    </section>




@stop

@include('front.footer')
