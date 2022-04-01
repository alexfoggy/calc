@if ($paginator->lastPage() > 1)
    @php
        $start = $paginator->currentPage() - 2;
        $end = $paginator->currentPage() + 2;
        $last_page = $paginator->lastPage();
        if ($start < 1) $start = 1;
        if ($end >= $paginator->lastPage()) $end = $paginator->lastPage();
    @endphp


    <div class="pagination">
        @if(!empty($new_url))

                    {!! ($paginator->currentPage() == 1) ? '<a><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>' : '<a href="' . $paginator->url($paginator->currentPage()-1) . $new_url . '"><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>' !!}

{{--                 {{ $paginator->currentPage() == 1 ? 'class=active' : '' }}>--}}
                    {!! ($paginator->currentPage() == 1) ? '<a class=active>' . 1 . '</a>' : '<a href="' . $new_url . '&page=1' . '">' . 1 . '</a>' !!}

                @if($start > 1)

                <a href="javascript:void(0);">...</a>

                @endif
                @for ($i = $start + 1; $i < $end; $i++)
{{--                     {{ $paginator->currentPage() == $i ? 'class=active' : '' }}--}}
                        {!! ($paginator->currentPage() == $i) ? '<a class=active>' . $i . '</a>' : '<a href="' . $new_url . '&page=' . $i . '">' . $i . '</a>' !!}

                @endfor
                @if($end < $paginator->lastPage())

                <a href="javascript:void(0);">...</a>

                @endif
{{--               {{ $paginator->currentPage() == $last_page ? 'class=active' : '' }}--}}
                    {!! ($paginator->currentPage() == $last_page) ? '<a class=active>' . $last_page . '</a>' : '<a href="' .  $new_url . '&page=' . $last_page . '">' . $last_page . '</a>' !!}

                    {!! ($paginator->currentPage() == $paginator->lastPage()) ? '<a><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>' : '<a href="' . $paginator->url($paginator->currentPage()+1) . $new_url . '"><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>'  !!}


        @else

                    {!! ($paginator->currentPage() == 1) ? '<a><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>' : '<a href="' . $paginator->url($paginator->currentPage()-1) . $new_url . '"><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#left-arrow").'"></use></svg></a>' !!}

{{--                {{ $paginator->currentPage() == 1 ? 'class=active' : '' }}--}}
                    {!! ($paginator->currentPage() == 1) ? '<a class=active>' . 1 . '</a>' : '<a href="' . $paginator->url(1) . $new_url . '">' . 1 . '</a>' !!}

                @if($start > 1)

                <a href="javascript:void(0);">...</a>

                @endif
                @for ($i = $start + 1; $i < $end; $i++)
{{--                   {{ $paginator->currentPage() == $i ? 'class=active' : '' }}--}}
                        {!! ($paginator->currentPage() == $i) ? '<a class=active>' . $i . '</a>' : '<a href="' . $paginator->url($i) . $new_url . '">' . $i . '</a>' !!}

                @endfor
                @if($end < $paginator->lastPage())

                <a href="javascript:void(0);">...</a>

                @endif
{{--               {{ $paginator->currentPage() == $last_page ? 'class=active' : '' }}--}}
                    {!! ($paginator->currentPage() == $last_page) ? '<a class=active>' . $last_page . '</a>' : '<a href="' . $paginator->url($last_page) . $new_url . '">' . $last_page . '</a>' !!}

                    {!! ($paginator->currentPage() == $paginator->lastPage()) ? '<a><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#right-arrow").'"></use></svg></a>' : '<a href="' . $paginator->url($paginator->currentPage()+1) . $new_url . '"><svg><use xlink:href="'.asset("front-assets/svg/sprite.svg#right-arrow").'"></use></svg></a>'  !!}

        @endif
    </div>
@endif
