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
                <h1 class="main-ttl-on-page">{{ShowLabelById(118,$lang_id)}}</h1>
                <form class="row" action="{{url($lang,'change-pass-ajax')}}" id="changePass" method="POST" enctype="multipart/form-data">
                    <div class="col-lg-6 mt-3">
                        <p>{{ShowLabelById(136,$lang_id)}}</p>
                        <input type="password" name="old_pass">
                    </div>
                    <div class="col-lg-6 mt-3">
                        <p>{{ShowLabelById(137,$lang_id)}}</p>
                        <input type="password" name="new_pass">
                    </div>
                    <div class="col-lg-6 mt-3">
                        <p>{{ShowLabelById(138,$lang_id)}}</p>
                        <input type="password" name="new_pass_confirmation" >
                    </div>
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6 mt-3">
                        <div class="email-form mt-0">
                            <button type="submit" class="changePass" data-form-id="changePass" onclick="saveForm(this)"> {{ShowLabelById(119,$lang_id)}}</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>


@stop

@include('front.footer')
