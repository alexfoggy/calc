@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')




    {{ Breadcrumbs::render('order',$lang_id) }}

    <div class='container'>
        <div class="row">
            <div class="col-lg-3">
                @include('front.templates.nav-acc')
            </div>
            <div class="col-lg-9 orders one-order">
                <h1>{{ShowLabelById(150,$lang_id)}} ({{$one_order->ordersData->id}})</h1>

                <div class="row">
                    <div class="col-lg-12">
                        <h4 class='cont-order-items'>{{ShowLabelById(148,$lang_id)}}{{$one_order->basket->count()}}</h4>
                        <div class="items">


                            <div class="arrow-left"><img src="{{asset('front-assets/img/icons/arr-left.png')}}" alt=""></div>
                            <div class="arrow-right"><img src="{{asset('front-assets/img/icons/arr-right.png')}}" alt=""></div>

                            <div class="swiper-container item-sliders-2">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <!-- Slides -->
                                    @foreach($one_order->basket as $one_goods)
                                    <div class="swiper-slide">
                                        <div class="item">
                                            <div class="image-item">
                                                <a href="{{url($lang,['category',$one_goods->alias_item])}}">
                                                    <img src="{{asset('upfiles/gallery/'.optional($one_goods->oImage)->img)}}" alt="">
                                                </a>
                                            </div>

                                            <div class="name-item"><a href="{{url($lang,['category',$one_goods->alias_item])}}">{{$one_goods->goods_name ?? ''}}</a></div>
                                            <div class="text-with-cart">{{ShowLabelById(99,$lang_id)}}</div>
                                            <div class="price-with-cart">{{$one_goods->goods_price ?? ''}}{{ShowLabelById(76,$lang_id)}}</div>
                                            <div class="norm-price">{{$one_goods->goods_price ?? ''}}{{ShowLabelById(76,$lang_id)}}</div>
                                        </div>
                                    </div>
                                    @endforeach


                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 pr-5 pr-mob-15">
                        <div class="data-order-info sp-bet">
                        <span class='big-info'><svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#date')}}"></use>
                        </svg>{{ShowLabelById(149,$lang_id)}}</span>
                            <span>{{Carbon\Carbon::parse($one_order->ordersData->created_at)->locale($lang)->isoFormat('DD MMMM YYYY') }}</span>
                        </div>
                        <div class="code-order-info sp-bet">
                        <span class='big-info'><svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#box')}}"></use>
                        </svg>{{ShowLabelById(151,$lang_id)}}</span>
                            <span>#{{$one_order->ordersData->id}}</span>
                        </div>
                        <div class="title-order-info mt-5">{{ShowLabelById(152,$lang_id)}}</div>
                        <div class="info-order">
                            <p>{{$one_order->ordersUsers->name}} {{$one_order->ordersUsers->last_name}}</p>
                            <p>{{$one_order->ordersUsers->phone}}</p>
                           <p> {{$one_order->ordersUsers->email}}</p>
                            <p>{{$one_order->ordersUsers->address}}</p>
                        </div>

                    </div>
                    <div class="col-lg-6 pl-5 mob-pl-15 mob-mt-30">
                        <div class="title-order-info mt-1">{{ShowLabelById(153,$lang_id)}}</div>
                        <div class="block-with-borders-tb">
                            <div class="sp-bet">
                                <span>{{ShowLabelById(154,$lang_id)}}</span>
                                <span>
                                    @if($one_order->ordersData->delivery_cost != null)
                                    {{$one_order->ordersData->total_price - $one_order->ordersData->delivery_cost}}{{ShowLabelById(76,$lang_id)}}
                                    @else
                                        {{$one_order->ordersData->total_price}} {{ShowLabelById(76,$lang_id)}}
                                    @endif
                                </span>
                            </div>
                            <div class="sp-bet mt-3">
                                <span>{{ShowLabelById(125,$lang_id)}}</span>
                                <span>@if($one_order->ordersData->delivery_cost != null) {{$one_order->ordersData->delivery_cost}} {{ShowLabelById(76,$lang_id)}} @else  {{ShowLabelById(155,$lang_id)}} @endif</span>
                            </div>
                        </div>
                        <div class="title-order-info mt-3">
                            <div class="sp-bet ">
                                <span>{{ShowLabelById(129,$lang_id)}}</span>
                                <span>{{$one_order->ordersData->total_price}} {{ShowLabelById(76,$lang_id)}} </span>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>








@stop

@include('front.footer')
