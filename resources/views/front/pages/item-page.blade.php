@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    <div class="container item-page">
        @if($goods_item->itemByLang)
            {{ Breadcrumbs::render('goods-item', $goods_subject, $goods_item) }}
        @endif


        <h1>{{ @$goods_item->itemByLang->name ?? '' }}</h1>
        <div class="row">
            <div class="col-lg-10">
                <div class="row-tabs">
                    <div class="tab-item active" data-show="c1">{{ShowLabelById(95,$lang_id)}}</div>
                    @if(!empty($params))<div class="tab-item" data-show="c2">{{ShowLabelById(96,$lang_id)}}</div> @endif
                    {{--                    <div class="tab-item">ХАРАКТЕРИСТИКИ</div>--}}
                    @if($best_review)<div class="tab-item" data-show="c3">{{ShowLabelById(97,$lang_id)}}</div>@endif
                </div>

                <div class=" c1 show-hide active">
                    <div class="row detail-item">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-3 ord-0">
                                <div class="swiper-container item-slider">
                                    <!-- Additional required wrapper -->
                                    <div class="swiper-wrapper">
                                        @foreach($goods_images as $one_image)
                                            <div class="swiper-slide" data-image='{{$loop->iteration}}'><img
                                                        src="{{ asset('upfiles/gallery/s') }}/{{ $one_image->img ?? '' }}"
                                                        alt=""></div>
                                        @endforeach
                                    </div>

                                    <div class="swiper-scrollbar"></div>
                                </div>
                            </div>
                            <div class="col-lg-9 ord-1 center">
                                <div class="big-image">
                                    @foreach($goods_images as $one_image)

                                        <a href="{{ asset('upfiles/gallery') }}/{{ $one_image->img ?? '' }}"
                                           data-fancybox="gallery" class='image-{{$loop->iteration}}'>
                                            <img src="{{asset($one_image != null ? 'upfiles/gallery/'.$one_image->img : 'front-assets/img/no-image.jpg') }}"
                                                 alt="">
                                        </a>

                                    @endforeach
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="item-descr">
                            {{ @$goods_item->itemByLang->short_descr ?? '' }}
                        </div>
                        <div class="row-attr-item">
                            <div class="add-to-compare attr-item mob-no {{ checkIfCompareExist(@$goods_item->id) ? 'active' : '' }}" data-goods-id="{{$goods_item->id}}">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#or')}}"></use>
                                </svg>
                                <span>{{ShowLabelById(102,$lang_id)}}</span>
                            </div>

                            <div class="attr-item add-to-wish mob-no @if(checkIfWishExist($goods_item->id) == true)active @endif"
                                 data-id="{{$goods_item->id}}" data-wish="0">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#fav ')}}"></use>
                                </svg>
                                <span>{{ShowLabelById(98,$lang_id)}}</span>
                            </div>

                            <div class="starts mr-4 mr-mob-no">
                                @if($reviews_item->count() > 0)
                                    @for($i = 0; $i < $review_rating ; $i++)
                                        <svg class='active'>
                                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                        </svg>
                                    @endfor
                                        ({{$reviews_item->count()}})
                                @else
                                    @for($i = 0; $i < 5 ; $i++)
                                        <svg>
                                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                        </svg>
                                    @endfor
                                    (0)
                                @endif
                            </div>

                        </div>
                        {{-- <div class="parametrs">
                             <div class="param">
                                 <span class='main-param'>ДОСТАВКА:</span>
                                 <span class='param-value'>доступно сегодня
                         18 452,5 пог. м</span>
                             </div>
                             <div class="param">
                                 <span class='main-param'>САМОВЫВОЗ:</span>
                                 <span class='param-value'>из строительных центров</span>
                             </div>
                         </div>--}}
                    </div>

                </div>

                <div class="row big-attr">
                    <div class="col-lg-6">
                        <div class="px-2">
                            @foreach($params as $one_param)
                                @if(!($loop->iteration % 2 == 0))
                                    <div class="row row-attr-param">
                                        <div class="col-lg-6 attr-light">{{$one_param['name']}}</div>
                                        <div class="col-lg-6 attr-bold">{{$one_param['value']}}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="px-2">
                            @foreach($params as $one_param)
                                @if($loop->iteration % 2 == 0)
                                    <div class="row row-attr-param">
                                        <div class="col-lg-6 attr-light">{{$one_param['name']}}</div>
                                        <div class="col-lg-6 attr-bold">{{$one_param['value']}}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                </div>
                @if(!empty($params))
                    <div class="c2 show-hide">
                        <div class="attr-block mt-5">

                            @foreach($params as $one_param)
                                <div class="row row-attr-param">
                                    <div class="col-lg-6 attr-light">{{$one_param['name']}}</div>
                                    <div class="col-lg-6 attr-bold">{{$one_param['value']}}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="c3 show-hide">
                    <div class="mt-3">

                        @foreach($reviews_item as $one_review)

                            <div class="row-review">

                                <div class="name-review-one">
                                    <span class="mr-1"><b>{{ShowLabelById(105,$lang_id)}} </b></span> {{$one_review->userInfo->name}} {{$one_review->userInfo->last_name}}
                                    <div class="stars-review-one">
                                        @for($i = 0;$i < $one_review->rating; $i++)
                                            <svg>
                                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                            </svg>
                                        @endfor

                                    </div>
                                    <div class="date-review">
                                        {{Carbon\Carbon::parse($one_review->created_at)->locale($lang)->isoFormat('DD MMMM YYYY') }}
                                    </div>
                                </div>
                                <div class="text-review">
                                    {{$one_review->body}}
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-lg-2 px-0">
                <div class="add-to-cart-block">
                    <div class="cart-club">{{ShowLabelById(99,$lang_id)}}</div>
                    <div class="price-add-to">{{@$goods_item->price}} {{ShowLabelById(76,$lang_id)}}</div>
                    <div class="price-add-to-2">{{@$goods_item->price_club}}{{ShowLabelById(76,$lang_id)}}</div>
                    <div class="bonus-add-to">{{ShowLabelById(100,$lang_id)}} {{@$goods_item->price_bonus}} <img src="{{asset('front-assets/img/icons/quet.png')}}" alt=""></div>
                    <div class="row-item-card">
                        <div class="count">
                            <input type="text" value='1' disabled>
                            <div class="buttons-count">
                                <div class="plus"><img src="{{asset('front-assets/img/icons/plus.png')}}" alt=""></div>
                                <div class="minus"><img src="{{asset('front-assets/img/icons/minus.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="add-to-card add-to-basket" data-id="{{$goods_item->id}}">{{ShowLabelById(101,$lang_id)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid info-alert">
        <div class="container">
            {{ShowLabelById(116,$lang_id)}}
        </div>
    </div>
    <div class="container c1 show-hide active">
                <div class="special-item mob-go">
                    <div class="price-special-block mt-3">
                        <div class="mob-left-part-fav">
                            <div class="cart-club"> {{ShowLabelById(99,$lang_id)}}</div>
                            <div class="price-add-to">{{$goods_item->price}} {{ShowLabelById(76,$lang_id)}}</div>
                            <div class="price-add-to-2">{{$goods_item->price}} {{ShowLabelById(76,$lang_id)}}</div>
                            <div class="bonus-add-to">{{ShowLabelById(100,$lang)}} {{$goods_item->price_bonus}} <img
                                        src="{{asset('front-assets/img/icons/quet.png')}}" alt=""></div>
                        </div>
                        <div class="row-item-card">
                            <div class="count">
                                <input type="text" value='1' disabled>
                                <div class="buttons-count">
                                    <div class="plus"><img src="{{asset('front-assets/img/icons/plus.png')}}" alt="">
                                    </div>
                                    <div class="minus"><img src="{{asset('front-assets/img/icons/minus.png')}}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="add-to-card add-to-basket" data-id="{{$goods_item->id}}" >{{ShowLabelById(101,$lang_id)}}</div>
                        </div>
                        <div class="row-attr-item">
                            <div class="attr-item add-to-compare  {{ checkIfCompareExist($goods_item->id) ? 'active' : '' }}" data-goods-id="{{$goods_item->id}}" >
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#or')}}"></use>
                                </svg>
                            </div>
{{--                            <div class="attr-item">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#chat')}}"></use>
                                </svg>
                            </div>--}}
                            <div class="attr-item add-to-wish @if(checkIfWishExist($goods_item->id) == true) active @endif" data-wish="@if(checkIfWishExist($goods_item->id) == true) 1 @else 0 @endif" data-id="{{$goods_item->id}}">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#fav')}}"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @if($best_review)
                    <div class="review-block">
                        <div class="review-title">{{ShowLabelById(103,$lang_id)}}</div>
                        <div class="d-flex align-items-center">
                            <div class="review-author">{{$best_review->userInfo->last_name}}</div>
                            <div class="starts">
                                @for($i = 0;$i < $best_review->rating;$i++)
                                    <svg class='active'>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                                    </svg>
                                @endfor

                            </div>
                        </div>
                        <div class="city-date">
                            <div class="date">{{Carbon\Carbon::parse($best_review->created_at)->locale($lang)->isoFormat('DD MMMM YYYY') }}</div>
                        </div>
                        <div class="text-review">
                            {{$best_review->body ?? ''}}
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <a href='javascript:void(0);'
                                   class='def-yellow-btn @if (Session::has('session-front-user'))rev-on @else open_popup' data-pop='review-wrapper @endif'>
                                    {{ShowLabelById(22,$lang_id)}}</a>
                            </div>

                            <div class="col-lg-6"><a href="" class='def-grey-btn'>{{ShowLabelById(89,$lang_id)}}</a></div>

                        </div>


                    </div>
                @else
                    <a href='javascript:void(0);'
                       class='def-yellow-btn mt-5 @if (Session::has('session-front-user'))rev-on @else open_popup' data-pop='reg @endif'>{{ShowLabelById(22,$lang_id)}}</a>
                @endif
                </div>
    </div>

                @if($similar_goods)
                    <div class="container">
                    <div class="also-need">
                        <div class="also-need-title">{{ShowLabelById(104,$lang_id)}}</div>
                    </div>
                    <div class="items">


                        <div class="arrow-left"><img src="{{asset('front-assets/img/icons/arr-left.png')}}" alt="">
                        </div>
                        <div class="arrow-right"><img src="{{asset('front-assets/img/icons/arr-right.png')}}" alt="">
                        </div>
                        <div class="swiper-container item-sliders-2">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                @foreach($similar_goods as $one_goods)
                                    <div class="swiper-slide">
                                        @include('front.templates.goods-template')
                                    </div>
                                @endforeach


                            </div>


                        </div>
                    </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    @if (Session::has('session-front-user'))
        <div class="overflow review-wrapper">
            <div class="plenka"></div>
            <div class="reg-in-popup">
                <div class="close">
                    <svg>
                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#close')}}"></use>
                    </svg>
                </div>
                <form action="{{url($lang,'newReview')}}" id="review-form" method="POST" enctype="multipart/form-data">
                    <div class="center">
                        <h2>{{ShowLabelById(106,$lang_id)}}</h2>
                    </div>
                    <h5><span class='attr-light'>{{ShowLabelById(107,$lang_id)}}</span> <span
                                class='bold'>{{$goods_item->itemByLang->name}}</span></h5>
                    <div class="name-reviews">
                        <span class='attr-light'>{{ShowLabelById(105,$lang_id)}}</span> <span class='bold'> {{$user_info->name ?? ''}}</span>
                    </div>
                    <textarea name="body" id="" cols="30" rows="10"></textarea>
                    <input type="hidden" name="item_id" value="{{$goods_item->id}}">
                    <input type="hidden" value="" name='rating' class='rating-input'>
                    <div class="starts">
                        <svg data-star='1' class='rating-star-1'>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                        <svg data-star='2' class='rating-star-2'>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                        <svg data-star='3' class='rating-star-3'>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                        <svg data-star='4' class='rating-star-4'>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                        <svg data-star='5' class='rating-star-5'>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                    </div>
                    <div class="email-form">
                        <button type="submit" id="review-form" data-form-id="review-form" onclick="saveForm(this)">
                            {{ShowLabelById(108,$lang_id)}}
                        </button>
                    </div>

                </form>

            </div>

        </div>
    @endif



@stop

@include('front.footer')
