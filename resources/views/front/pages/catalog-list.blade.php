@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    <div class="breadcrumbs-wrapper">
        <div class="container">
            {{ Breadcrumbs::render('catalog') }}
        </div>
    </div>
    <div class="container catalog-list-page">
        <h1>{{ShowLabelById(133,$lang_id)}}</h1>
        <div class="row ">
            @if($goods_subject_l1)
                @foreach($goods_subject_l1 as $one_subject)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <h3><a href="{{url($lang,['catalog',$one_subject->alias])}}">{{$one_subject->itemByLang->name}}</a></h3>
                <ul>
                    @foreach($one_subject->children as $one_sub_subject)
                    <li><a href="{{url($lang,['catalog',$one_sub_subject->alias])}}">{{$one_sub_subject->itemByLang->name}}</a></li>

                    @endforeach

                </ul>
                @foreach($one_subject->children as $one_sub_subject)
                    @if($loop->index == 6)
                <span><img src="{{asset('front-assets/img/arr-down.png')}}" alt="">{{ShowLabelById(134,$lang_id)}}</span>
                    @endif
                @endforeach
            </div>
                @endforeach
                @endif

        </div>

    </div>

@stop

@include('front.footer')
