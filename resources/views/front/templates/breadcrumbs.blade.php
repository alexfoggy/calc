@if($breadcrumbs && count($breadcrumbs))
    <div class="breadcrumbs">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                @php
                    $url_array = explode('/',$breadcrumb->url);
                @endphp

                <a href="{{ url($lang, $url_array) }}">
                    @if($loop->first)

                    @endif
                    {{ $breadcrumb->title ?? '' }}
                </a>
                <span>
                <svg>
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#arr-right')}}"></use>
                </svg>
</span>
            @else
                <a href="javascript:;" class="active">
                    {{ $breadcrumb->title ?? '' }}
                </a>
            @endif
        @endforeach
    </div>
@endif
