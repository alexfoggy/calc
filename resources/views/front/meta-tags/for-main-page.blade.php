@if(!Request::segment(2))
    <title>{{ html_entity_decode($meta_main_page->itemByLang->meta_title ?? $meta_default, ENT_QUOTES, "UTF-8") }}</title>
    <meta name="description"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_description ?? $meta_default, ENT_QUOTES, "UTF-8") }}">
    <meta name="keywords"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_keywords ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>

    <meta property="og:locale" content="ru_RU"/>
    <meta property="og:locale:alternate" content="ro_RO"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_title ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>
    <meta property="og:image" content="{{ $meta_page_img }}"/>
    <meta property="og:description"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_description ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{env('APP_DOMAIN')}}"/>

    <meta property="og:fb:admins" content="{{env('APP_DOMAIN')}}"/>
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:description"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_description ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>
    <meta name="twitter:title"
          content="{{ html_entity_decode($meta_main_page->itemByLang->meta_title ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>
    <meta name="twitter:site" content="@url"/>
    <meta name="twitter:image" content="{{ $meta_page_img }}"/>

    <link title="Русский" dir="ltr" type="text/html" rel="alternate" hreflang="x-default"
          href="{{env('APP_URL')}}">
    <link title="Русский" dir="ltr" type="text/html" rel="alternate" hreflang="ru-md"
          href="{{env('APP_URL')}}">
    {{--   <link title="Romana" dir="ltr" type="text/html" rel="alternate" hreflang="ro-md"
             href="{{env('APP_URL')}}/ro">--}}

    {{--  @if((count(request()->segments()) == 1 && $lang == 'ru') || count(request()->segments()) == 0)
          <link rel="canonical" href="{{env('APP_URL')}}"/>
      @elseif(count(request()->segments()) == 1 && $lang == 'ro')
          <link rel="canonical" href="{{env('APP_URL')}}/ro"/>
      @else--}}
    <link rel="canonical" href="{{ mb_strtolower(url()->current()) }}">
    {{-- @endif--}}

@endif
    <meta property="business:contact_data:country_name" content="Moldova"/>
    <meta property="business:contact_data:email" content="maxejj@gmail.com"/>
    <meta property="business:contact_data:website" content="{{ env('APP_URL') }}"/>
    <meta name="author" content="EJOV">
{{--
    <meta property="place:location:latitude" content="47.00500938503058"/>
    <meta property="place:location:longitude" content="28.840672384666217"/>
    <meta property="business:contact_data:street_address" content="{{ ShowLabelById(149, $lang_id) }}"/>
    <meta property="business:contact_data:locality" content="or. Chișinău"/>
    <meta property="business:contact_data:postal_code" content="2025"/>
    <meta property="business:contact_data:country_name" content="Moldova"/>
    <meta property="business:contact_data:email" content="{{ ShowLabelById(64, $lang_id) }}"/>
    <meta property="business:contact_data:phone_number" content="{{ ShowLabelById(65, $lang_id) }}"/>
    <meta property="business:contact_data:phone_number" content="{{ ShowLabelById(4, $lang_id) }}"/>
    <meta property="business:contact_data:website" content="{{ env('APP_URL') }}"/> --}}
