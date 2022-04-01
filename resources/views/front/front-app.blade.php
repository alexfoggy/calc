<!DOCTYPE html>
<html lang="{{$lang}}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script data-ad-client="ca-pub-6912825580400313" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-WZ5XCKTQEB"></script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Calc.md",
        "alternateName": "Полезные калькуляторы",
        "url": {{env('APP_URL')}}",
        "potentialAction": [{
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "{{env('APP_URL')}}/search?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }]
    }
</script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-WZ5XCKTQEB');
    </script>
    {!! showSettingBodyByAlias('after-head', $lang_id) !!}
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6107TVHW0P"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-6107TVHW0P');
    </script>

@yield('meta-tags')
@include('front.meta-tags.for-main-page')

    <link rel="icon" type="image/gif" href="{{asset('favicon.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('front-assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('front-assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('front-assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('front-assets/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('front-assets/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="{{ asset('front-assets/favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{asset('front-assets/css/libs.min.css?v=').env('CSS_LIB_VER') }}">
    <link rel="stylesheet" href="{{asset('front-assets/css/libs.min.css?v=') }}">
    <link rel="stylesheet" href="{{asset('front-assets/css/main.css?v=').env('CSS_VER') }}">
    <link rel="stylesheet" href="{{asset('front-assets/css/validate.css')}}">
    <link rel="stylesheet" href="{{asset('front-assets/css/notiflix-2.5.0.min.css')}}">
</head>
<body>



@yield('header')

@yield('container')

@yield('footer')

<script src="{{asset('front-assets/js/libs.min.js?v=').env('JS_LIB_VER') }}"></script>
<script src="{{asset('front-assets/js/main.js?v=').env('JS_VER') }}"></script>
<script src="{{asset('front-assets/js/notiflix-2.5.0.min.js')}}"></script>
<script src="{{asset('front-assets/js/recaptcha.js')}}"></script>
<script src="{{asset('front-assets/js/ajax-scripts.js')}}"></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RE_CAP_SITE') }}"></script>

@stack('slider-range')
<script>
    getRecaptcha('/allpages', 'recaptcha-main');
/*    getRecaptcha('/contacts', 'recaptcha-contacts-form')*/
</script>

<div id="fixed-overlay"></div>

<script>
    let lang = $('html').attr('lang');
    if ( $(window).width() > 768 ) {
        $('a[href^="tel:"], a[href^="mailto:"]').click(function() {
            event.preventDefault();
            window.location.href = '/' + lang + '/contacts';
        });
    };
</script>


{!! showSettingBodyByAlias('before-body', $lang_id) !!}
</body>
</html>
