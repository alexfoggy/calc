@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

{{--@include('front.header')--}}

@section('container')


    <section class="login">
        <div class="container">

            <div class="row">
                <div class="col-lg-6 center mob-no">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1200 1200"><defs><style>.cls-1,.cls-8,.cls-9{fill:none;}.cls-2{fill:#949494;}.cls-3{fill:url(#New_Pattern);}.cls-4{fill:#f3f3f3;}.cls-5{fill:#dfdfdf;}.cls-6{fill:#f9f9f9;}.cls-7{fill:#fff;}.cls-8,.cls-9{stroke:#000;stroke-linecap:round;stroke-width:2.5px;}.cls-8{stroke-miterlimit:10;}.cls-9{stroke-linejoin:round;}</style><pattern id="New_Pattern" data-name="New Pattern" width="12" height="12" patternUnits="userSpaceOnUse" viewBox="0 0 12 12"><rect class="cls-1" width="12" height="12"/><circle class="cls-2" cx="12" cy="9" r="2"/><circle class="cls-2" cx="6" cy="12" r="2"/><circle class="cls-2" cy="9" r="2"/><circle class="cls-2" cx="12" cy="3" r="2"/><circle class="cls-2" cx="6" cy="6" r="2"/><circle class="cls-2" cy="3" r="2"/><circle class="cls-2" cx="6" r="2"/></pattern></defs><title>Artboard 1</title><g id="Shadows"><path class="cls-3" d="M464,316.88c3.46,5.79,24.44,42.63,23,72.12-6.62-2.51-33.39-14.75-33.39-14.75l-1.73-10-7.3-28.2-4.13-10.79-6.94-14.2-4.45-6.22,12,2.76Z"/><path class="cls-3" d="M360.74,358.14c0-8.44,3,111.89,40.25,127-20.13,5.85-52.81,1.3-52.81,1.3l-10.5-9.94-3.09-7.45L332.7,442.3v-9.5l.14-15.82,1.51-22.85,1.32-8.1,4.58-10.49,1.92-3.69s5.32,1.64,7.32,1.64S360.74,361.79,360.74,358.14Z"/></g><g id="Vector"><polygon class="cls-4" points="594.8 89.22 594.8 662.58 614.35 662.58 614.35 786.81 1075.02 786.81 1075.02 89.22 594.8 89.22"/><rect class="cls-5" x="594.8" y="89.22" width="480.22" height="44.93"/><rect class="cls-5" x="705.7" y="190.74" width="403.3" height="114.91"/><rect class="cls-6" x="729.25" y="212.98" width="360.56" height="10.41"/><rect class="cls-6" x="729.25" y="237.78" width="360.56" height="10.41"/><rect class="cls-6" x="729.25" y="262.59" width="360.56" height="10.41"/><rect class="cls-5" x="705.7" y="351.83" width="403.3" height="114.91"/><circle class="cls-6" cx="759.42" cy="409.28" r="34.22"/><rect class="cls-6" x="818.89" y="375.06" width="262.55" height="33.1"/><rect class="cls-6" x="818.89" y="419.38" width="262.55" height="12.16"/><rect class="cls-7" x="641.74" y="516.58" width="378.79" height="186.36"/><path class="cls-8" d="M614.35,1111.54v-449H470.57v449m51.6-425.29v-20.6l12-.49v9.48l24.92,11.61Z"/><path class="cls-8" d="M428.57,1111.54V811.29H284.79v300.25m55.33-265.79v-20.6l12-.49v9.48L377,845.75Z"/><polyline class="cls-8" points="99 1111.55 99 940.61 242.78 940.61 242.78 1111.55"/><path d="M451.19,475.9c10,0,90.23,11.33,125.33,29.36-4.83,23.51-36.39,162-36.39,162H520.48s-7.4-116.59-9-115.62c-10.63,5.47-52.82,19.27-97.91,19.94"/><path class="cls-9" d="M473.53,331.72c6.85,4.89,17.55,14.25,19.87,18.18C520.58,316.1,565.34,282,569,278.06s4.8-25.89,4.8-30.69,6.62-17.59,13-17.59v57.55s-74.9,105.05-87.23,105.05c-6.36,0-32.68-11-45.92-18.13"/><path class="cls-8" d="M428.77,304.86c20,2.88,46.25,15.93,46.3,21.12.06,6-12.94,28.87-23.15,38.29"/><path class="cls-8" d="M447.77,204.06a13.48,13.48,0,0,0,1.92-7c0-9-7.56-10.18-11-13-3.66-3-2.09-12.07-12-12.07-8.88,0-13.25,9.33-18.93,12.92-6.87,4.35-20.35.85-29.23,10-4.31,4.44-5.32,15.37-8,19.37-3.66,5.42-10,7-10,15.27,0,5,3.51,12.85,8.21,16.29"/><path class="cls-8" d="M412.14,290.59c53.77,27.3,49.46,177.28,39.05,185.31-11.46,8.83-105.52,13.13-114.12,0-4-6.12-6.86-47.62-1.82-90.24"/><path class="cls-8" d="M360.66,305.09c6.24-8.42,13.75-14.51,22.73-17.21"/><path class="cls-8" d="M307.48,350.07c-15.14,17.26-37.9,61.52-37.9,69.43,0,20,67.52,108.09,76.11,108.09,3.58-6.21,9.31-19.09,9.31-19.09s-40.56-75.64-48-85.42c6.44-6,29.82-37.46,33.64-47.24a26.22,26.22,0,0,0,1.19-4.1"/><path class="cls-8" d="M349.05,373.48c-7.64.48-43.93-18.91-43.93-28.41,0-7.64,39.7-48.78,56.82-39.39,2.44,1.34,4,3.76,5,6.92"/><path class="cls-8" d="M412.14,290.59V278"/><path class="cls-8" d="M383.39,255.68v35.89c0,5.06,7.19,7.64,14.38,7.66"/><path class="cls-8" d="M412.14,278a19.49,19.49,0,0,0,3.39-.48c14.79-3.45,22.68-23.82,17.62-45.48a57.87,57.87,0,0,0-3.93-11.39"/><path class="cls-8" d="M378.2,235.39a9.42,9.42,0,0,0,2.6,18.47,9.74,9.74,0,0,0,1.72-.17c.28.67.57,1.34.87,2"/><path d="M337.68,475.9v.64c9.44,17.27,17.32,32,17.32,32s-5.73,12.88-9.31,19.09c-1.36,0-4.2-2.22-8-6V825.69H355L451.19,475.9C445,481,360,493,337.68,475.9Z"/><polyline class="cls-9" points="340.12 825.15 340.12 845.75 377 845.75 352.09 834.14 352.09 824.66"/><polyline class="cls-9" points="522.17 665.65 522.17 686.25 559.06 686.25 534.14 674.64 534.14 665.16"/></g></svg>

                </div>
                <div class="col-lg-6 center ">
                    <div class="w-80">
                        <div class="form-login">
                            <h2>??????????????????????</h2>
                            <form action="{{url($lang,'regin')}}" id="register" method="post">
                                <input type="text" placeholder="??????" name="name">
                                <input type="text" placeholder="Email" name="email">

                                <div class="position-relative mt-1">
                                    <input type="password" placeholder="Password" class="mt-1" name="password">
                                    <div class="show-pass">
                                        <img src="{{asset('front-assets/img/eye.svg')}}" alt="">
                                    </div>
                                </div>

                                <div class="position-relative mt-1">
                                    <input type="password" placeholder="Password" class="mt-1" name="password_confirmation">
                                    <div class="show-pass">
                                        <img src="{{asset('front-assets/img/eye.svg')}}" alt="">
                                    </div>
                                </div>

                                <div class="center"><button class='btn-go' type="submit" onclick="saveForm(this)" data-form-id="register">????????????????????????????????????</button>
                                </div>
                                <h6 class='mt-3'>???????????????????????????????????? ?????????? ????????</h6>
                                <div class="social-auth">
                                    <a href="" class='facebook'>Facebook</a>
                                    <a href="{{url('login','google')}}" class='google'>Google</a>
                                </div>
                            </form>
                        </div>
                        <div class="back-or-go">
                            <a href="{{url($lang)}}">?????????????????? ???? ????????</a>
                            <a href="{{url($lang,'login')}}">??????????</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


@stop

@include('front.footer')
