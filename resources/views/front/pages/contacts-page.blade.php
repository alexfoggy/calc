@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    @if($parent_menu->itemByLang)

                {{ Breadcrumbs::render($parent_menu->alias, $parent_menu) }}

    @endif

    <section class="contacts">
        <div class="container">
            <h1 class="h2"><span>{{ @$parent_menu->itemByLang->name ?? '' }}</span></h1>
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <form action="{{url($lang,['simpleFeedback','feedback'])}}" enctype="multipart/form-data" id="contactsForm" method="POST">
                        <input type="hidden" name="type_form" value="fromcontacts">
                        <p>{{ShowLabelById(53,$lang_id)}}</p>
                        <input type="text" name='email'>
                        <p>{{ShowLabelById(144,$lang_id)}}</p>
                        <textarea name="comment"></textarea>
                        <button class='def-yellow-btn w-100 mt-4' id="contactsForm" data-form-id="contactsForm" type="submit" onclick="saveForm(this)">
                            {{ShowLabelById(71,$lang_id)}}</button>
                    </form>
                </div>
                <div class="col-lg-7 col-md-7 map">
                    {!!showSettingBodyByAlias('map',$lang_id)!!}
                </div>
            </div>
            <div class="row info-contacts">
                <div class="col-lg-3">
                    <div class="d-flex">
                        <div class="">
                            <svg>
                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#call')}}"></use>
                            </svg>
                        </div>
                        <div class="">
                            <div class="cont-ttl">{{ShowLabelById(39,$lang_id)}}</div>
                            <div class="cont-href"><a href="tel:{{ShowLabelById(40,$lang_id)}}">{{ShowLabelById(40,$lang_id)}}</a></div>
                            <div class="cont-ttl">{{ShowLabelById(41,$lang_id)}}</div>
                            <div class="cont-href"><a href="tel:{{ShowLabelById(189,$lang_id)}}"> {{ShowLabelById(189,$lang_id)}}</a></div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex">
                        <div class="">
                            <div class="cont-ttl">{{ShowLabelById(43,$lang_id)}}</div>
                            <div class="cont-href"><a href="tel:{{ShowLabelById(42,$lang_id)}}">{{ShowLabelById(42,$lang_id)}}</a></div>
                        </div>
                    </div>
                    <div class="info-footer socials">
                        <p>{{ShowLabelById(37,$lang_id)}} <a href="{{showSettingBodyByAlias('facebook',$lang_id)}}" target="_blank">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#facebook')}}"></use>
                                </svg>
                            </a></p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex">
                        <div class="">
                            <svg>
                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#map')}}"></use>
                            </svg>
                        </div>
                        <div>
                            <div class="cont-ttl mb-0 ">{{ShowLabelById(145,$lang_id)}}</div>
                        </div>
                    </div>
                    <div class="d-flex mt-4">
                        <div class="">
                            <svg>
                                <use xlink:href="{{asset('front-assets/svg/sprite.svg#email')}}"></use>
                            </svg>
                        </div>
                        <div class="">
                            <div class="cont-ttl">{{ShowLabelById(46,$lang_id)}}</div>
                            <div class="cont-href"> <a href="mailto:{{ShowLabelById(47,$lang_id)}}"> {{ShowLabelById(47,$lang_id)}} </a></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="info-footer">
                        <div class="ttl-cont">{{ShowLabelById(31,$lang_id)}}</div>
                        <p>{{ShowLabelById(32,$lang_id)}}</p>
                        <p>{{ShowLabelById(33,$lang_id)}}</p>
                        <p>{{ShowLabelById(34,$lang_id)}}</p>
                    </div>
                </div>
        </div>
    </section>

@stop

@include('front.footer')
