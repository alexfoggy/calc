<meta name="description" content="{{ trim(html_entity_decode(@$meta_tag->itemByLang->meta_description ? @$meta_tag->itemByLang->meta_description : (@$meta_tag->itemByLang->body ? substrBySpace(@$meta_tag->itemByLang->body, 150) : $meta_default), ENT_QUOTES, "UTF-8")) }}">
<meta name="keywords" content="{{ html_entity_decode(@$meta_tag->itemByLang->meta_keywords ?? $meta_default, ENT_QUOTES, "UTF-8") }}"/>

<meta property="og:locale" content="ru_RU"/>
<meta property="og:locale:alternate" content="ro_RO"/>
<meta property="og:type" content="website"/>
@if(@$meta_static)
    <title>{{ @$meta_static ?? $meta_default }}  • calc.md</title>
@else
    <title>{{ html_entity_decode(@$meta_tag->itemByLang->meta_title ? @$meta_tag->itemByLang->meta_title : (@$meta_tag->itemByLang->name ? @$meta_tag->itemByLang->name : $meta_default), ENT_QUOTES, "UTF-8") }}</title>
@endif
<meta property="og:image" content="{{ @$current_meta_img ? @$current_meta_img : $meta_page_img }}"/>
<meta property="og:description" content="{{ trim(html_entity_decode(@$meta_tag->itemByLang->meta_description ? @$meta_tag->itemByLang->meta_description : (@$meta_tag->itemByLang->body ? substrBySpace(@$meta_tag->itemByLang->body, 150) : $meta_default), ENT_QUOTES, "UTF-8")) }}"/>
<meta property="og:url" content="{{ url()->current() }}"/>
<meta property="og:site_name" content="{{ env('APP_DOMAIN')}} "/>

<meta property="og:fb:admins" content="{{ env('APP_DOMAIN') }}"/>
<meta name="twitter:card" content="summary"/>
<meta name="twitter:description" content="{{ trim(html_entity_decode(@$meta_tag->itemByLang->meta_description ? @$meta_tag->itemByLang->meta_description : (@$meta_tag->itemByLang->body ? substrBySpace(@$meta_tag->itemByLang->body, 150) : $meta_default), ENT_QUOTES, "UTF-8")) }}"/>
@if(@$meta_static)
    <meta name="twitter:title" content="{{ @$meta_static ?? $meta_default }}"/>
@else
    <meta name="twitter:title" content="{{ html_entity_decode(@$meta_tag->itemByLang->meta_title ? @$meta_tag->itemByLang->meta_title : (@$meta_tag->itemByLang->name ? @$meta_tag->itemByLang->name : $meta_default), ENT_QUOTES, "UTF-8") }}"/>
@endif

<meta name="twitter:site" content="@url"/>
<meta name="twitter:image" content="{{ @$current_meta_img ? @$current_meta_img : $meta_page_img }}"/>


@if(count(request()->segments()) > 1)
    <link title="Русский" dir="ltr" type="text/html" rel="alternate" hreflang="ru-md"
          href="{{ count(request()->segments()) > 0 ? str_replace('/'.$lang, '/ru', request()->fullUrl()) : url('ru') }}">
 {{--   <link title="Romana" dir="ltr" type="text/html" rel="alternate" hreflang="ro-md"
          href="{{ count(request()->segments()) > 0 ? str_replace('/'.$lang, '/ro', request()->fullUrl()) : url('ro') }}">--}}
@endif

{{--<link title="Romana" dir="ltr" type="text/html" rel="alternate" hreflang="ro"
      href="{{ count(request()->segments()) > 0 ? str_replace('/'.$lang, '/ro', request()->fullUrl()) : url('ro') }}">--}}
<link title="Русский" dir="ltr" type="text/html" rel="alternate" hreflang="ru"
      href="{{ count(request()->segments()) > 0 ? str_replace('/'.$lang, '/ru', request()->fullUrl()) : url('ru') }}">

{{--@if((count(request()->segments()) == 1 && $lang == 'ro') || count(request()->segments()) == 0)
    <link rel="canonical" href="{{env('APP_URL')}}"/>
@elseif(count(request()->segments()) == 1 && $lang == 'ru')
    <link rel="canonical" href="{{env('APP_URL')}}/ru"/>
@else--}}
    <link rel="canonical" href="{{ mb_strtolower(url()->current()) }}">
{{--@endif--}}
