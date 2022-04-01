@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    <div class="container compare-page">
        {{ Breadcrumbs::render('compare', $lang_id) }}
        <h1>{{ShowLabelById(139,$lang_id)}}</h1>
        @if($cookie_compare)
        <div class="row-with-tabs">
            @foreach($compare_subjects as $one_compare_subject)
                @php
                    $compare_items_count = CountCompareBySubject($one_compare_subject);
                @endphp

                <a class="tab-category {{ $compare_subject_id == $one_compare_subject ? 'active' : ''  }}" href="{{url($lang,'compare-list')}}?subject={{ $one_compare_subject ?? '' }}">
                {{ IfHasName($one_compare_subject, $lang_id, 'goods_subject') }} {{ $compare_items_count ? '('.$compare_items_count.')' : ''  }}
                </a>
            @endforeach


        </div>
        <div class="swiper-row">
            <div class="row mt-5">
                @foreach($compare_list as $one_compare_item)
                <div class="w-20">
                    <div class="item">
                        <div class="top-item-row">
                            <div class="cod">{{ShowLabelById(114,$lang_id)}}<span>{{$one_compare_item->goodsItemId->one_c_code}}</span></div>
                            <div class="">

                                <a href="javascript:void(0);" class="add-to-wish" data-id="{{$one_compare_item->goods_item_id}}" data-wish="@if(checkIfWishExist($one_compare_item->goods_item_id) == true) 1 @else 0 @endif">
                                    <svg @if(checkIfWishExist($one_compare_item->goods_item_id) == true) class="active" @endif>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#fav')}}"></use>
                                    </svg>
                                </a>
                                <a href="javascript:void(0);" class="remove-compare-item" data-goods-id="{{$one_compare_item->goods_item_id}}">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#close')}}"></use>
                                    </svg></a>
                            </div>
                        </div>
                        <div class="image-item">
                            <div class="item-actions">
                                @if($one_compare_item->goodsItemId->new_element == 1)
                                    <div class="new"> {{ShowLabelById(140,$lang_id)}}</div>
                                @endif
                                @if($one_compare_item->goodsItemId->can_buy_by_bonus == 1)
                                    <div class="card-bon"><img src="{{asset('front-assets/img/icons/logo-for-item.png')}}" alt=""></div>
                                @endif
                                @if($one_compare_item->goodsItemId->price_bonus > 0)
                                    <div class="bonus">+{{$one_compare_item->bonus_plus_item}}</div>
                                @endif
                                @if($one_compare_item->goodsItemId->price < $one_compare_item->goodsItemId->price_old)
                                    <div class="sale">-{{round(100-($one_compare_item->goodsItemId->price*100/$one_compare_item->goodsItemId->price_old))}}%</div>
                                @endif
                            </div>
                            <a href="{{url($lang,['catalog',$one_compare_item->goodsItemId->parent->alias,$one_compare_item->goodsItemId->alias])}}">
                            <img src="{{asset('upfiles/gallery/'.$one_compare_item->goodsItemId->oImage->img)}}" alt="{{$one_compare_item->goodsItemId->itemByLang->name}}">
                            </a>
                         </div>
                        @if($one_compare_item->in_stoc == 1)
                            <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_compare_item->goodsItemId->parent->alias,$one_compare_item->goodsItemId->alias])}}"><img src="{{asset('front-assets/img/icons/instoc.png')}}" alt=""> <span>{{ShowLabelById(141,$lang_id)}}</span>  </a></div>
                        @else
                            <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_compare_item->goodsItemId->parent->alias,$one_compare_item->goodsItemId->alias])}}"><img src="{{asset('front-assets/img/icons/clock.png')}}" alt=""> <span>{{ShowLabelById(142,$lang_id)}}</span>  </a></div>
                        @endif

                        <div class="name-item"><a href="{{url($lang,['catalog',$one_compare_item->goodsItemId->parent->alias,$one_compare_item->goodsItemId->alias])}}">{{$one_compare_item->goodsItemId->itemByLang->name}}</a></div>
                        <div class="text-with-cart">{{ShowLabelById(99,$lang_id)}} {{ShowLabelById(115,$lang_id)}}</div>
                        <div class="price-with-cart">{{$one_compare_item->goodsItemId->price}} {{ShowLabelById(76,$lang_id)}}</div>
                        <div class="norm-price">{{$one_compare_item->goodsItemId->price}} {{ShowLabelById(76,$lang_id)}}</div>
                        <div class="for-bal">{{ShowLabelById(100,$lang_id)}} {{ShowLabelById(115,$lang_id)}} {{$one_compare_item->goodsItemId->price_bonus}} <img src="{{asset('front-assets/img/icons/quet.png')}}" alt=""></div>
                        <div class="row-item-card">
                            <div class="count">
                                <input type="text" value='1' disabled>
                                <div class="buttons-count">
                                    <div class="plus"><img src="{{asset('front-assets/img/icons/plus.png')}}" alt=""></div>
                                    <div class="minus"><img src="{{asset('front-assets/img/icons/minus.png')}}" alt="">  </div>
                                </div>
                            </div>
                            <div class="add-to-card add-to-basket" data-id="{{$one_compare_item->goods_item_id}}">{{ShowLabelById(101,$lang_id)}}</div>
                        </div>
                    </div>
                    <div class="param-compare-one">
                        <div class="attr-bold">{{ShowLabelById(143,$lang_id)}}</div>
                        <div class="attr-light">@if($one_compare_item->goodsItemId->goodsItemReviews->count() > 0)
                                @php
                                    $review_rating = 0;
foreach ($one_compare_item->goodsItemId->goodsItemReviews as $one_review) {
             $review_rating = $review_rating + $one_review->rating;
                            }
     $review_rating = $review_rating / $one_compare_item->goodsItemId->goodsItemReviews->count();

                                @endphp
                                <div class="stars">
                                    @for($i = 0; $i < $review_rating ; $i++)
                                        <svg class='active'>
                                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                        </svg>
                                    @endfor
                                        ({{$one_compare_item->goodsItemId->goodsItemReviews->count()}})

                                </div>
                            @else
                                -----
                            @endif
                        </div>
                        @php
                            $stars = round($one_compare_item->goodsItemId->rating);
                            $reviews_count = $one_compare_item->goodsItemId->goodsItemReviews->count();
                        @endphp

                    </div>
                </div>
            @endforeach
            </div>


                    <div class="params-compare">

                        @if($parameters->isNotEmpty())
                        @foreach($parameters as $one_parameter)
                                <div class="row">
                            @php
                                $draw_prev = '';
                                $td_class = 'compare-cell-similar';
                                if($one_parameter->for_basket == 1)
                                    $add_to_basket = 0;
                            @endphp
                        @if($compare_list)
                            @foreach($compare_list as $one_key => $one_compare_item)
                                @php
                                    $draw_td = ParametrDisplayOneValue($one_parameter, $one_compare_item->goods_item_id, $lang_id);
                                    if($one_key > 0 && $draw_td != $draw_prev)
                                        $draw_prev = $draw_td;
                                @endphp
                                            <div class="w-20">
                                <div class="param-compare-one">
                                    <div class="attr-bold"> {{$one_parameter->itemByLang->name}}</div>
                                    <div class="attr-light">{{ $draw_td ?? '---' }}</div>
                                </div>
                                            </div>
                            @endforeach
                        @endif


                                </div>
                        @endforeach
                        @endif

                    </div>




            </div>
        </div>
        @else
            <h2 class="h1 center bold mt-5">{{ShowLabelById(115,$lang_id)}}</h2>
        @endif
    </div>









@stop

@include('front.footer')
