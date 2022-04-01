<!DOCTYPE html>
<html lang="{{$lang}}">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=1140">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    @if(!empty($modules_submenu_name))
        <title>{{$modules_submenu_name->name ?? trans('variables.title_page')}}</title>
    @elseif(!empty($modules_name))
        <title>{{$modules_name->name ?? trans('variables.title_page')}}</title>
    @else
        <title>{{trans('variables.title_page')}}</title>
    @endif
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" href="{{asset('favicon.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('favicon.png')}}">

    <link rel="stylesheet" href="{{asset('admin-assets/css/jquery.arcticmodal-0.3.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/jquery.mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/jquery.formstyler.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/jquery.datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/toastr.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/reset.css')}}">
    <link rel="stylesheet" href="{{asset('admin-assets/css/styles.css')}}">

    <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->

</head>
<body>

@include('admin.header')

<div class="wrap">

@if(auth()->check() == true)
    @include('admin.sidebar')
@endif
    <!--START content-->
    <div class="content {{auth()->check() == true && (is_null(request()->cookie('sidebar')) || request()->cookie('sidebar')) ? ' with-sidebar' : ''}}">
        @yield('content')
    </div>
    <!--END content-->

</div>

@include('admin.footer')

<script src="{{asset('admin-assets/js/jquery-1.11.1.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.arcticmodal-0.3.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.fancybox.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.formstyler.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.matchHeight-min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.tablednd_0_5.js')}}"></script>
<script src="{{asset('admin-assets/js/select2.min.js')}}"></script>
<script src="{{asset('admin-assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('admin-assets/js/toastr.js')}}"></script>
<script src="{{asset('admin-assets/js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('admin-assets/js/drag-arrange.js')}}"></script>
<script src="{{asset('admin-assets/js/dropzone.js')}}"></script>
<script src="{{asset('admin-assets/js/dropzone-config.js')}}"></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?region={{$lang}}&language={{$lang}}&key={{env('GOOGLE_MAP_API')}}"></script>
<script src="{{asset('admin-assets/js/google_map.js')}}"></script>
<script src="{{asset('admin-assets/js/scripts.js')}}"></script>

</body>
</html>