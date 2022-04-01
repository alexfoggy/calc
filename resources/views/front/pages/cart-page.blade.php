@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')
    <div class="container cart-page">
    <div class="breadcrumbs-wrapper">
        <div class="container">
            {{ Breadcrumbs::render('cart', $lang_id) }}
        </div>
    </div>
        @if(!empty($basket) && count($basket))
    <section class="basket">
        <div class="container">



                <div class="cart-elems">
                    <div class="row row-top-cart">
                        <div class="col-lg-2 center">{{ShowLabelById(120,$lang_id)}}</div>
                        <div class="col-lg-2 mr-5"></div>
                        <div class="col-lg-2 center">{{ShowLabelById(75,$lang_id)}}</div>
                        <div class="col-lg-2 center">{{ShowLabelById(121,$lang_id)}}</div>
                        <div class="col-lg-2 center">{{ShowLabelById(122,$lang_id)}}</div>
                    </div>

                @foreach($basket as $one_elem)

                    <div class="row row-cart">
                        <div class="col-lg-2 image-cart">
                            <a href="{{url($lang,['catalog',$one_elem->GoodsItemId->parent->alias,$one_elem->alias_item])}}">
                                <img src="{{asset('upfiles/gallery/'.$one_elem->oImage->img)}}" alt="{{$one_elem->goods_name}}"></a></div>
                        <div class="col-lg-10 cart-row-part-two mob-w-60">
                            <div class="row w-100">

                            <div class="cent-row-name col-lg-3">
                                <div class="cod-item">{{ShowLabelById(114,$lang_id)}} {{$one_elem->goods_one_c_code}}</div>
                                <div class="name-item-cart"> <a href="{{url($lang,['catalog',$one_elem->GoodsItemId->parent->alias,$one_elem->alias_item])}}"> {{$one_elem->goods_name}} </a></div>
                            </div>
                            <div class="center no-cent-mob col-lg-3">
                                <div class="price-cart-item">
                                    <span> {{$one_elem->goods_price}} {{ShowLabelById(76,$lang_id)}}</span>
                                </div>
                            </div>
                            <div class="a-center col-lg-2"><div class="count">
                                    <input type="text" value='{{$one_elem->items_count}}' data-id="{{$one_elem->goods_item_id}}" data-page="cart" disabled>
                                    <div class="buttons-count">
                                        <div class="plus count-plus"><img src="{{asset('front-assets/img/icons/plus.svg')}}" alt=""></div>
                                        <div class="minus count-minus"><img src="{{asset('front-assets/img/icons/minus.svg)}}" alt="">  </div>
                                    </div>
                                </div></div>
                            <div class="center no-cent-mob col-lg-2">
                                <div class="price-f-row"><span>{{$one_elem->goods_price * $one_elem->items_count}}</span>  mdl</div></div>
                            <div class="center-right col-lg-1 del-from-cert-block">
                                <a href="javascript:void(0);" class='del-from-cart basket-remove' data-id="{{$one_elem->goods_item_id}}">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#trash')}}"></use>
                                    </svg>
                                </a></div>
                        </div>
                        </div>
                    </div>

                    @endforeach
</div>


        </div>
    </section>

    <div class="cart-final">
        <div class="row @if (Session::has('session-front-user')) center @endif">
            @if (!Session::has('session-front-user'))
            <div class="col-lg-6">

                <div class="block-from-cart-sh">
                    <h2>{{ShowLabelById(123,$lang_id)}}</h2>
                    <h3>{{ShowLabelById(124,$lang_id)}}</h3>
                    <div class="get-card">
                        <form action="">
                            <input type="text" placeholder="Email">
                            <input type="text" placeholder="Пароль">
                            <div class="remebmer">
                                <label>
                                    <input id="checkbox-agree" name="agree" type="checkbox" >
                                    <span class="checkbox"></span>
                                    {{ShowLabelById(56,$lang_id)}}
                                </label>
                            </div>
                            <button>{{ShowLabelById(57,$lang_id)}}</button>
                        </form>
                       {{-- <a href="" class='how-get'>Как получить карту?</a>--}}
                    </div>
                </div>

               {{-- <div class="block-from-cart-sh">
                    <h3>Введите номер карты и получите скидку
                        и баллы клуба друзей.</h3>
                    <div class="get-card">
                        <form action="">
                            <input type="text">
                            <button>ПЕРЕСЧИТАТЬ</button>
                        </form>
                        <a href="" class='how-get'>Как получить карту?</a>
                    </div>
                </div>--}}
            </div>
            @endif

            <div class="col-lg-6 right-cart-part">
                <form  action="{{url($lang,'newOrder')}}" enctype="multipart/form-data" id="new_order">
                <div class="block-from-cart-sh p-50">
                    <div class="type-del">
                        <div class="type-chouse active delivery-go">{{ShowLabelById(125,$lang_id)}}</div>
                        <div class="type-chouse takeaway">{{ShowLabelById(126,$lang_id)}}</div>
                    </div>
                    <input type="hidden" class='takeordeliv' value='delivery'>
                    <div class="row-input">
                        <div class="position-relative">
                        <input type="text" name="name" @if($user_info)value="{{$user_info->name ?? ''}}"@endif placeholder="{{ShowLabelById(51,$lang_id)}}">
                        </div>
                        <div class="position-relative">
                        <input type="text" name="last_name" @if($user_info)value="{{$user_info->last_name ?? ''}}"@endif placeholder="{{ShowLabelById(52,$lang_id)}}">
                        </div>
                        <div class="position-relative">
                        <input type="text" name='email' @if($user_info)value="{{$user_info->email ?? ''}}"@endif placeholder="{{ShowLabelById(53,$lang_id)}}">
                        </div><div class="position-relative">
                        <input type="text" name='phone' @if($user_info)value="{{$user_info->phone ?? ''}}"@endif placeholder="{{ShowLabelById(54,$lang_id)}}">
                        </div>
                        </div>
                    <input type="text" name="address" placeholder="{{ShowLabelById(127,$lang_id)}}" class="adress">
                    <textarea name="comment" id="" cols="10" rows="3" placeholder="{{ShowLabelById(128,$lang_id)}}"></textarea>
                    <div class="type-pay">
                        <label>
                            <input id="checkbox-agree" name="pay_method" value="cash" type="radio" checked>
                            <span class="checkbox">
                        <svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#cash')}}"></use>
                        </svg>
                        cash
                    </span>

                        </label>
                        <label>
                            <input id="checkbox-agree" name="pay_method" value="card" type="radio" >
                            <span class="checkbox">
                        <svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#card')}}"></use>
                        </svg>
                        card
                    </span>

                        </label>
                    </div>

                    <div class="final-items-col">
                        {{ShowLabelById(129,$lang_id)}} <span>{{$basket_items_count}}</span> {{ShowLabelById(130,$lang_id)}}
                    </div>
                    <div class="final-price">
                       <span> {{$total_price}} </span> {{ShowLabelById(76,$lang_id)}}
                    </div>
                    <div class="price-delivery">
                        {{ShowLabelById(131,$lang_id)}}    <span> --- </span>
                    </div>
                    <div class="email-form">
                        <button type="submit" class="send-bth" data-form-id="new_order">{{ShowLabelById(132,$lang_id)}}</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
        @else

            <div class="center">
                <p style="font-size:50px">{{ShowLabelById(115,$lang_id)}}</p>
            </div>

        @endif
    </div>











@stop

@include('front.footer')
