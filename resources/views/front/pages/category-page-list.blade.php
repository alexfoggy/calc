@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')




    <div class="container mt-3 catalog-list-page">
        <div class="row">
            <div class="pl-0 col-lg-2">
                <div class="category-list">
                    @if($goods_subject)
                        @foreach($goods_subject->children as $one_subject)
                    <a href="{{url($lang,['catalog',$one_subject->alias])}}">
                        <span>{{$one_subject->itemByLang->name}}</span>
                    </a>
                        @endforeach
                        @endif
                </div>
            </div>
            <div class="col-lg-10">
                {{ Breadcrumbs::render('goods-subject', $goods_subject) }}
                <h1 class='mt-0'>{{$goods_subject->itemByLang->name}}</h1>
                @if($goods_subject)
                    @foreach($subjects as $one_subject)
                        @if($items[$one_subject->id]->isNotEmpty())
                <div class="d-flex justify-content-between mt-4">
                <a href="{{url($lang,['catalog',$one_subject->alias])}}" class='name-sub-category'>{{$one_subject->itemByLang->name}}</a>
                    <span><a href="{{url($lang,['catalog',$one_subject->alias])}}" class='btn-show-all'>{{ShowLabelById(135,$lang_id)}}</a></span>
                </div>

                <div class="items mt-0">


                    <div class="arrow-left"><img src="{{asset('front-assets/img/icons/arr-left.png')}}" alt=""></div>
                    <div class="arrow-right"><img src="{{asset('front-assets/img/icons/arr-right.png')}}" alt=""></div>

                    <div class="swiper-container item-sliders-four">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->

                            @foreach($items[$one_subject->id] as $one_goods)
                                <div class="swiper-slide">
                                    @include('front.templates.goods-template')
                                </div>
                            @endforeach
                        </div>


                    </div>
                </div>
                    @endif
                    @endforeach
                    @endif
            </div>
        </div>
    </div>




@stop

@include('front.footer')
