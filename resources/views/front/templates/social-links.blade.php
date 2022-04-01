<ul>
    @if($skype)
        <li>
            <a href="skype:cabacom.cad?call">
                <svg>
                    <use xlink:href="{{ asset('front-assets/svg/sprite.svg#skype') }}"></use>
                </svg>
            </a>
        </li>
    @endif
    @if($facebook)
        <li>
            <a href="{{ $facebook }}" target="_blank">
                <svg>
                    <use xlink:href="{{ asset('front-assets/svg/sprite.svg#facebook') }}"></use>
                </svg>
            </a>
        </li>
    @endif
    @if($viber)
        <li>
            <a href="viber://contact?number=%2B{{ str_replace([' ', '(', ')', '-'], '', $viber) }}">
                <svg>
                    <use xlink:href="{{ asset('front-assets/svg/sprite.svg#viber') }}"></use>
                </svg>
            </a>
        </li>
    @endif
</ul>