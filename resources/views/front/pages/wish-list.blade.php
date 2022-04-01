@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


            {{ Breadcrumbs::render('wish-list', $lang_id) }}



            <div class='container'>
                <div class="row">
                    <div class="col-lg-3">
                        @include('front.templates.nav-acc')
                    </div>
                    <div class="col-lg-9">
                        <h1 class='main-ttl-on-page'>{{ShowLabelById(112,$lang_id)}} (<span>{{count($goods_items_list)}}</span>)</h1>
                        @if(!empty($goods_items_list) && count($goods_items_list))
                        <div class="d-flex justify-content-between mb-4">
                            <div class="view-mode-page">
                                <div class="one-in-row"><img src="{{asset('front-assets/img/row-one-mode.png')}}" alt=""></div>
                                <div class="two-in-row"><img src="{{asset('front-assets/img/row-two-mode.png')}}" alt=""></div>
                            </div>
                        </div>
                        <div class="row-item-special">

                                @foreach($goods_items_list as $one_goods)
                            <div class="special-item">

                                <div class="image-special-block">
                                    <a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}">
                                        <img src="{{asset('upfiles/gallery/'.$one_goods->oImage->img)}}" alt="">
                                    </a>
                                    <div class="cod-special">
                    <span>{{ShowLabelById(114,$lang_id)}}
                        </span>
                                        <span class='bold'>{{$one_goods->one_c_code}}</span>
                                    </div>

                                </div>
                                <div class="name-item-special-block">
                                    <a href='{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}' class="name-item-special">
                                        {{$one_goods->name}}
                                    </a>
                                    @if($one_goods->in_stoc == 1)
                                        <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}"><img src="{{asset('front-assets/img/icons/instoc.png')}}" alt=""> <span>{{ShowLabelById(141,$lang_id)}}</span>  </a></div>
                                    @else
                                        <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}"><img src="{{asset('front-assets/img/icons/clock.png')}}" alt=""> <span>{{ShowLabelById(142,$lang_id)}}</span>  </a></div>
                                    @endif
                                    <div class="starts">

                                        @if(!empty($one_goods->goodsItemReviews))
                                            @php
                                            $i = 0;
                                            if(!empty($one_goods->goodsItemReviews))
                                            foreach($one_goods->goodsItemReviews as $onerev){
                                                $i = $i + $onerev->rating;
                                            }
                                            if($i != 0){

                                            $i = $i /$one_goods->goodsItemReviews->count();
                                            for($x = 0;$x < $i; $x++){
                                                @endphp
                                                 <svg class='active'>
                                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                        </svg>
                                        @php
                                            } }
                                        else {
                                            echo ShowLabelById(117,$lang_id);
                                        }

                                            @endphp

                                        @endif

                                    </div>
                                    <div class="text-descr-special">
                                        {{ substr($one_goods->short_descr, 0, 200) . '...'}}
                                    </div>

                                </div>

                                <div class="price-special-block">
                                    <div class="mob-left-part-fav">
                                        <div class="cart-club">{{ShowLabelById(99,$lang_id)}}</div>
                                        <div class="price-add-to">{{$one_goods->price}} {{ShowLabelById(76,$lang_id)}}</div>
                                        @if($one_goods->price_old)<div class="price-add-to-2">{{$one_goods->price_old}} {{ShowLabelById(76,$lang_id)}}</div>@endif
                                        @if($one_goods->price_club)<div class="bonus-add-to">{{ShowLabelById(100,$lang_id)}} {{$one_goods->price_club}}<img src="{{asset('front-assets/img/icons/quet.png')}}" alt=""></div>
                                            @endif
                                    </div>
                                    <div class="row-item-card">
                                        <div class="count">
                                            <input type="text" value='1' disabled>
                                            <div class="buttons-count">
                                                <div class="plus"><img src="{{asset('front-assets/img/icons/plus.png')}}" alt=""></div>
                                                <div class="minus"><img src="{{asset('front-assets/img/icons/minus.png')}}" alt="">  </div>
                                            </div>
                                        </div>
                                        <div class="add-to-card add-to-basket" data-id="{{$one_goods->id}}">{{ShowLabelById(101,$lang_id)}}</div>
                                    </div>
                                    <div class="row-attr-item top-item-row">
                                        <div class="attr-item">
                                            <a href="javascript:void(0);" class="add-to-compare" data-goods-id="{{$one_goods->id}}"><svg class="{{ checkIfCompareExist($one_goods->id) ? 'active' : '' }}">
                                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#or')}}"></use>
                                                </svg></a>
                                        </div>
                                        <div class="attr-item">
                                            <a href="javascript:void(0);" class="add-to-wish" data-id="{{$one_goods->id}}" data-wish="@if(checkIfWishExist($one_goods->id) == true) 1 @else 0 @endif"><svg @if(checkIfWishExist($one_goods->id) == true) class="active" @endif>
                                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#fav')}}"></use>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="attr-item">
                                            <a href="javascript:void(0);" class="remove-wish-item" data-id="{{$one_goods->id}}">
                                            <svg>
                                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#trash')}}"></use>
                                            </svg>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                                @endforeach

                            </div>

                        </div>
                    @else

                        <h2 class="h1 center bold mt-5">{{ShowLabelById(105,$lang_id)}}</h2>

                    @endif
                    </div>
                </div>
            </div>

@stop

@include('front.footer')
