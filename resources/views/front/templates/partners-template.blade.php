<div class="container">
    <h2><span>{{ ShowLabelById(82, $lang_id) }}</span></h2>
    <div class="layout-row flex-wrap">
        @foreach($partners as $one_partner)
            @php $brand_name = getBrandName($one_partner->id, $lang) @endphp
            <a href="{{ $one_partner->oImage && $one_partner->oImage->img ? asset('upfiles/brand/'. $one_partner->oImage->img) : asset('front-assets/img/no-image.png') }}" class="partners-item" data-fancybox="partners">
                <img src="{{ $one_partner->oImage && $one_partner->oImage->img ? asset('upfiles/brand/'. $one_partner->oImage->img) : asset('front-assets/img/no-image.png') }}" alt="{{ $brand_name }}">
            </a>
        @endforeach
    </div>
</div>