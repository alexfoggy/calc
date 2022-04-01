@section('footer')


    <!-- Main Footer start -->
    <div class="container">
        <div class="if-error">
            <div class="our-team">{{ShowLabelById(199,$lang_id)}}</div>
            <span class="hash">{{ShowLabelById(192,$lang_id)}}</span>
            <h4>{{ShowLabelById(200,$lang_id)}}
                <br>
                {{ShowLabelById(201,$lang_id)}}</h4>
            <div class="contact-button-open button-open" data-open='error-popup'>
                 {{ShowLabelById(202,$lang_id)}}
            </div>
        </div>
    </div>
    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <a href="{{url($lang)}}" class="logo">
                        <img src="{{asset('front-assets/img/logo/logo.svg')}}" alt="">
                    </a>
                </div>
                <div class="col-lg-2">
{{--                    <h4>Самые популярные</h4>--}}
{{--                    <h5>--}}
{{--                        <a href="">Подсчет НДС</a>--}}
{{--                    </h5>--}}
{{--                    <h5>--}}
{{--                        <a href="">Подсчет НДС</a>--}}
{{--                    </h5>--}}
{{--                    <h5>--}}
{{--                        <a href="">Подсчет НДС</a>--}}
{{--                    </h5>--}}
                </div>
                <div class="col-lg-4">
                    <h4>{{ShowLabelById(203,$lang_id)}}</h4>
                    @foreach($new_calc_list as $one_calc)
                    <h5>
                        <a href="{{url($lang,['calculator',$one_calc->parent->alias,$one_calc->alias])}}">{{$one_calc->itemByLang->name ?? ''}}</a>
                    </h5>
                    @endforeach
                </div>
                <div class="col-lg-3">
                    <h4>{{ShowLabelById(204,$lang_id)}}</h4>
                    @foreach($footer_menu as $one_menu)
                    <h5>
                        <a href="{{url($lang,$one_menu->alias)}}">{{$one_menu->itemByLang->name ?? ''}}</a>
                    </h5>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <p>Copyright 2021</p>
                <a href="" class="created-by">
                    EJOV PROJECT
                </a>
            </div>
        </div>
    </div>
    <!-- Main Footer end -->


    <div class="popup error-popup">
        <div class="plenka"></div>
        <div class="error-block block-popup">
            <div class="back-image">
                <svg>
                    <use xlink:href="{{asset("front-assets/svg/sprite.svg#eye")}}"></use>
                </svg>
            </div>
            <form action="{{url($lang,['simpleFeedback','feedback'])}}" id="feedback">
                <div class="">
                    <p>{{ShowLabelById(205,$lang_id)}}</p>
                    <input type="text" name="email">
                </div>
                <div>
                    <p>{{ShowLabelById(206,$lang_id)}}</p>
                    <textarea name="message" id=""></textarea>
                </div>
                <div class="">
                    <button class="button-classic" type="submit" onclick="saveForm(this)" data-form-id="feedback">
                        {{ShowLabelById(207,$lang_id)}}
                    </button>
                </div>
            </form>
        </div>
    </div>

@stop
