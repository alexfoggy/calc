@if ($paginator->lastPage() > 1)
    <?php
    $start = $paginator->currentPage() - 5;
    $end = $paginator->currentPage() + 5;
    $last_page = $paginator->lastPage();
    if ($start < 1) $start = 1;
    if ($end >= $paginator->lastPage()) $end = $paginator->lastPage();
    ?>

    <div class="pagination-block">
        <ul class="pagination">
            <li {{ ($paginator->currentPage() == 1) ? 'class=disabled' : '' }}>
                {!! ($paginator->currentPage() == 1) ? '<span class="prev"></span>' : '<a href="' . $paginator->url($paginator->currentPage()-1) . '" class="prev"></a>' !!}
            </li>
            <li {{ ($paginator->currentPage() == 1) ? 'class=active' : '' }}>
                {!! ($paginator->currentPage() == 1) ? '<span>' . 1 . '</span>' : '<a href="' . $paginator->url(1) . '">' . 1 . '</a>' !!}
            </li>
            @if($start > 1)
                <li>
                    <span>...</span>
                </li>
            @endif
            @for ($i = $start + 1; $i < $end; $i++)
                <li {{ ($paginator->currentPage() == $i) ? 'class=active' : '' }}>
                    {!! ($paginator->currentPage() == $i) ? '<span>' . $i . '</span>' : '<a href="' . $paginator->url($i) . '">' . $i . '</a>' !!}
                </li>
            @endfor
            @if($end < $paginator->lastPage())
                <li>
                    <span>...</span>
                </li>
            @endif
            <li {{ ($paginator->currentPage() == $last_page) ? 'class=active' : '' }}>
                {!! ($paginator->currentPage() == $last_page) ? '<span>' . $last_page . '</span>' : '<a href="' . $paginator->url($last_page) . '">' . $last_page . '</a>' !!}
            </li>
            <li {{ ($paginator->currentPage() == $paginator->lastPage()) ? 'class=disabled' : '' }}>
                {!! ($paginator->currentPage() == $paginator->lastPage()) ? '<span class="next"></span>' : '<a href="' . $paginator->url($paginator->currentPage()+1) . '" class="next"></a>'  !!}
            </li>
        </ul>
    </div>
@endif
