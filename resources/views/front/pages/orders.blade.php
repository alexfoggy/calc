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
            <div class="col-lg-9 orders">
                <h1>{{ShowLabelById(7,$lang_id)}}</h1>
                @if($front_user_orders->isNotEmpty())
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">

                                @foreach($front_user_orders as $one_order)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="order-block">
                                    <a href="{{url($lang,['orders',$one_order->id])}}" class='text-decor-none'>
                                        <div class="d-flex justify-content-between order-row">
                                            <div class="date-order">{{Carbon\Carbon::parse($one_order->ordersData->created_at)->locale($lang)->isoFormat('DD MMMM YYYY') }}</div>
                                            <div class="cod-order">#<b>{{$one_order->ordersData->id}}</b></div>

                                        </div>
                                        <div class="items-order">{{ShowLabelById(148,$lang_id)}}<b>{{$one_order->basket->count()}}</b></div>

                                        <div class="fin-ord-price-text">{{ShowLabelById(156,$lang_id)}}</div>
                                        <div class="fin-ord-price">{{$one_order->ordersData->total_price}} {{ShowLabelById(76,$lang_id)}}</div>

                                        <a href="{{url($lang,['orders',$one_order->id])}}" class='btn-sem-yellow'>{{ShowLabelById(157,$lang_id)}}</a>
                                    </a>
                                </div>
                            </div>
                                @endforeach



                        </div>
                    </div>

                </div>
                @else
                        <h2 class="h1 center bold mt-5">{{ShowLabelById(115,$lang_id)}}</h2>

                @endif
            </div>
        </div>
    </div>








@stop

@include('front.footer')
