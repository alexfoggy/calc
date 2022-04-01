@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')


    {{ Breadcrumbs::render('order',$lang_id) }}

    <div class='container'>
        <div class="row">
            <div class="col-lg-3">
                @include('front.templates.nav-acc')
            </div>
            <div class="col-lg-9 cabinet">
                <h1 class="main-ttl-on-page">{{ShowLabelById(11,$lang_id)}}</h1>
                <form class="row" action="{{url($lang,'saveData')}}" id="saveData" method="POST" enctype="multipart/form-data">
                    <div class="col-lg-6 mt-3">
                        <p>Имя</p>
                        <input type="text" name="name" value="{{$user_info->name ?? ''}}">
                    </div>
                    @if($user_info->facebook_id == null && $user_info->google_id == null)
                        <div class="col-lg-6 mt-3">
                            <p>Email</p>
                            <input type="text" name="email" value="{{$user_info->email ?? ''}}">
                        </div>
                    @endif

                    <div class="col-lg-6 mt-3">
                        <p>Фамилия</p>
                        <input type="text" name="last_name" value="{{$user_info->last_name ?? ''}}">
                    </div>
                    <div class="col-lg-6 mt-3">
                        <p>Телефон</p>
                        <input type="text" name="phone" value="{{$user_info->phone ?? ''}}">
                    </div>
                    <div class="col-lg-6 mt-3 what-can-improve ">
                        @if($user_info->facebook_id == null && $user_info->google_id == null)
                        <span><a href="{{url($lang,'change-pass')}}">{{ShowLabelById(118,$lang_id)}}</a></span>
                            @endif
                    </div> <div class="col-lg-6 mt-3">

                        <div class="email-form mt-0">
                            <button type="submit" class="save_data" data-form-id="saveData" onclick="saveForm(this)">
                                {{ShowLabelById(119,$lang_id)}}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


@stop

@include('front.footer')
