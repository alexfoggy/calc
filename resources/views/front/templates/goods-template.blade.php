@php
    $parent_alias = '';
    if($one_goods->getSubjectId && $one_goods->getSubjectId->alias)
        $parent_alias = $one_goods->getSubjectId->alias
@endphp
<div class="item">
    <div class="top-item-row">
        <div class="cod">Код: <span> {{$one_goods->one_c_code}}</span></div>
        <div class="">
            <a href="javascript:void(0);" class="add-to-compare" data-goods-id="{{$one_goods->id}}"><svg class="{{ checkIfCompareExist($one_goods->id) ? 'active' : '' }}">
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#or')}}"></use>
                </svg></a>
            <a href="javascript:void(0);" class="add-to-wish" data-id="{{$one_goods->id}}" data-wish="@if(checkIfWishExist($one_goods->id) == true) 1 @else 0 @endif"><svg @if(checkIfWishExist($one_goods->id) == true) class="active" @endif>

                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#fav')}}"></use>
                </svg></a>
        </div>
    </div>
    <div class="image-item">
        <div class="item-actions">
            @if($one_goods->new_element == 1)
                <div class="new">{{ShowLabelById(140,$lang_id)}}</div>
            @endif
            @if($one_goods->can_buy_by_bonus == 1)
                <div class="card-bon"><img src="{{asset('front-assets/img/icons/logo-for-item.png')}}" alt=""></div>
            @endif
            @if($one_goods->price_bonus)
                @if($one_goods->price_bonus > 0)
                    <div class="bonus">+{{$one_goods->bonus_plus_item}}</div>
                @endif
            @endif
            @if($one_goods->price < $one_goods->price_old)
                <div class="sale">-{{round(100-($one_goods->price*100/$one_goods->price_old))}}%</div>
            @endif
        </div>
        <a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}">
            @if($one_goods->oImage != null)
                <img src="{{asset('upfiles/gallery/'.$one_goods->oImage->img)}}" alt="{{$one_goods->itemByLang->name}}">
            @else
                <img src="{{asset('front-assets/img/no-image.png')}}" alt="{{ShowLabelById(190,$lang_id)}}">
            @endif
        </a>
    </div>
    @if($one_goods->in_stoc == 1)
        <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}"><img src="{{asset('front-assets/img/icons/instoc.png')}}" alt=""> <span>{{ShowLabelById(141,$lang_id)}}</span>  </a></div>
    @else
        <div class="check-if-aval"><a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}"><img src="{{asset('front-assets/img/icons/clock.png')}}" alt=""> <span>{{ShowLabelById(142,$lang_id)}}</span>  </a></div>
    @endif
    <div class="name-item"><a href="{{url($lang,['catalog',$one_goods->parent->alias,$one_goods->alias])}}">{{$one_goods->itemByLang->name}}</a></div>
    <div class="text-with-cart">{{ShowLabelById(99,$lang_id)}}</div>
    <div class="price-with-cart">{{$one_goods->price}}{{ShowLabelById(76,$lang_id)}}</div>
    <div class="norm-price">{{$one_goods->price}}{{ShowLabelById(76,$lang_id)}}</div>
    <div class="for-bal">{{ShowLabelById(100,$lang_id)}}{{$one_goods->price_bonus}} <img src="{{asset('front-assets/img/icons/quet.png')}}" alt=""></div>
    <div class="row-item-card">
        <div class="count">
            <input type="text" value='1' disabled>
            <div class="buttons-count">
                <div class="plus"><img src="{{asset('front-assets/img/icons/plus.png')}}" alt="+"></div>
                <div class="minus"><img src="{{asset('front-assets/img/icons/minus.png')}}" alt="-">  </div>
            </div>
        </div>
        <div class="add-to-card add-to-basket" data-id="{{$one_goods->id}}">{{ShowLabelById(101,$lang_id)}}</div>
    </div>
</div>