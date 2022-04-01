@if(!empty($goods_items_list) && count($goods_items_list))
    @foreach($goods_items_list as $one_goods)
        <div class="col-lg-3 col-6">
            @include('front.templates.goods-template', ['one_goods' => $one_goods])
        </div>
    @endforeach

    @if(!empty($new_url))
        @include('front.templates.pagination', ['paginator' => $goods_items_list, 'new_url' => $new_url])
    @else
        @include('front.templates.pagination', ['paginator' => $goods_items_list, 'new_url' => ''])
    @endif

@else
    <p style="text-align: center; width: 100%; font-size: 2rem;">{{ ShowLabelById(115, $lang_id) }}</p>
@endif
