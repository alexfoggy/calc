@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')
    <section class="calculator-page">
        <div class="container">
            {{ Breadcrumbs::render('calc_item',$calc_id->parent, $calc_id) }}
            <div class="d-flex align-items-center mobile-column justify-content-between">
                <h1>{{$calc_id->itemByLang->name ?? ''}}</h1>
                <div class="add-to-fav has-help add-to-fav-it <?php if(checkIfWishExist($calc_id->id) == true): ?>active <?php endif; ?>"
                     data-id="{{$calc_id->id}}">
                    <svg>
                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#saved')}}"></use>
                    </svg>
                    <span>
                        {{ShowLabelById(208,$lang_id)}}
                    </span>
                    <div class="help-message first-message">
                        {{ShowLabelById(209,$lang_id)}}
                    </div>
                    <div class="help-message second-message">
                        {{ShowLabelById(210,$lang_id)}}
                    </div>
                </div>
            </div>
            <h2>{{ShowLabelById(211,$lang_id)}}</h2>
            <div class="text">
                {!! $calc_id->itemByLang->body ?? '' !!}
            </div>
            @if($rows->isNotEmpty())
                <div class="row">
                    <div class="col-lg-8">
                        <div class="calc-block">
                            <div class="mt-5">
                                <form action="{{url($lang,['calcit',$calc_id->alias])}}" id="form-conn"
                                      enctype="multipart/form-data" method="POST">
                                    <div class="reqie">
                                        {{ShowLabelById(218,$lang_id)}}
                                    </div>
                                    <span class="calc-back">
                                        {{ShowLabelById(192,$lang_id)}}
                                    </span>
                                    <div class="row flex-column">
                                        @foreach($rows as $one_row)
                                            <div class="d-flex  col-lg-12 align-items-center mt-4">
                                                <div class="d-flex align-items-center w-100">
                                                    <div class="title-input">
                                                        {{$one_row->itemByLang->name ?? ''}}
                                                    </div>
                                                    <div class="d-flex align-items-center ">
                                                        <div class="input-value mt-0">
                                                            <input type="text" name="{{$one_row->variable}}"
                                                                   oninput="this.value = this.value.replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '$1');">
                                                        </div>
                                                        <span class="ml-2">{{$one_row->itemByLang->after_text ?? ''}}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>
                                    @if($calc_id->type_calc == 'select')
                                        <div class="d-flex justify-content-center flex-column">
                                            @if($checkbox_spec == true)
                                                @foreach($checkbox as $one_box)
                                                    <input type="hidden" name='formula_type' value="{{$one_box->id}}"
                                                           checked>
                                                @endforeach
                                            @else
                                                @foreach($checkbox as $one_box)
                                                    <div class="checkbox-row">
                                                        <label>
                                                            <input type="radio" name='formula_type'
                                                                   value="{{$one_box->id}}"
                                                                   @if($loop->first) checked @endif>
                                                            <div class="checkbox"></div>
                                                            <span>{{$one_box->itemByLang->name ?? ''}}</span>
                                                        </label>
                                                    </div>
                                                @endforeach

                                            @endif
                                            {{--   <div class="checkbox-row">
                                                   <label>
                                                       <input type="checkbox" name='history-allow'>
                                                       <div class="checkbox"></div>
                                                       <span>сохранять историю подсчетов</span>
                                                   </label>
                                               </div>
                                               <div class="checkbox-row">
                                                   <label>
                                                       <input type="checkbox" name='history-allow'>
                                                       <div class="checkbox"></div>
                                                       <span>сохранять историю подсчетов</span>
                                                   </label>
                                               </div>--}}

                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-center mt-3">
                                        <button type="submit" data-form-id="form-conn" class="active"
                                                onclick="calcThis(this)">{{ShowLabelById(217,$lang_id)}}
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>
                        <div class="w-100 position-relative">
                            <div class="anchor"></div>
                            <div class="result">
                                <div class="center f-40">
                                    <div class="">
                                        <div class="center">--:--
                                        </div>
                                        <div class="f-24">{{ShowLabelById(212,$lang_id)}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-5">
                        <div class="share">
                            {{--<a href="">
                                <svg>
                                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#share')}}"></use>
                                </svg>
                            </a>--}}
                            <div class="social-links">
                                <a href="https://vk.com/share.php?url={{url()->full()}}" target="_blank">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#vk')}}"></use>
                                    </svg>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{url()->full()}}"
                                   target="_blank">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#facebook')}}"></use>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/share?url={{url()->full()}}" target="_blank">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#twitter')}}"></use>
                                    </svg>
                                </a>
                                </a>
                                {{-- <a href="">
                                     <svg>
                                         <use xlink:href="{{asset('front-assets/svg/sprite.svg#instagram')}}"></use>
                                     </svg>
                                 </a>--}}
                                <a href="https://telegram.me/share/url?url={{url()->full()}}" target="_blank">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#telegram')}}"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                {{--
                            <div class="calc-block">
                                <div class="anchor"></div>
                                <div class="mt-5">
                                    <div class="result">
                                        <h3>Результат: <span>--:--</span></h3>
                                    </div>

                                    <form action="{{url($lang,['calcit',$calc_id->alias])}}" id="form-conn" enctype="multipart/form-data" method="POST">
                                       @if($checkbox_spec == true)
                                            @foreach($checkbox as $one_checkbox)
                                            <input type="radio" style="display: none;" name="formula_type" value="{{$one_checkbox->id}}" checked="checked">
                                            @endforeach
                                           @else
                                        @if($checkbox)
                                        <div class="d-flex justify-content-around mb-3 flex-wrap">
                                        @foreach($checkbox as $one_checkbox)
                                        <div class="checkbox-row">
                                            <label>
                                                <input type="radio" name="formula_type" value="{{$one_checkbox->id}}">
                                                <div class="checkbox"></div>
                                                <span>{{$one_checkbox->itemByLang->name ?? ''}}</span>
                                            </label>
                                        </div>
                                            @endforeach
                                       </div>
                                        @endif
                                        @endif
                                        @foreach($rows as $one_row)
                                            <div class="d-flex justify-content-between">
                                            <div class="title-input">
                                                {{$one_row->itemByLang->name ?? ''}}
                                            </div>
                                            <div class="input-value">
                                                <input type="text" name="{{$one_row->variable}}">
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" data-form-id="form-conn" onclick="calcThis(this)" disabled>Подсчитать</button>
                                        </div>
                                    </form>

                                </div>

                            </div>--}}
            @else

                <div class="info-alert">
                    <svg>
                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#hmm')}}"></use>
                    </svg>
                    <div class="info-error">
                        {{ShowLabelById(213,$lang_id)}}
                    </div>
                </div>

            @endif

        </div>
        @if($recomended->isNotEmpty())
            <div class="container mt-5 pt-3 mb-5 pb-5">
                <div class="ttl h2">
                    {{ShowLabelById(214,$lang_id)}}
                </div>
                <div class="row mx-0">
                    @foreach($recomended as $one_calc)
                        <a href='{{url($lang,['calculator',$one_calc->parent->alias,$one_calc->alias])}}'
                           class="calc-interested">
                            <h3>{{$one_calc->itemByLang->name ?? ''}}</h3>
                        </a>
                    @endforeach

                </div>
            </div>
        @endif

    </section>

    <div class="info-alert fixed-center">
        <div class="d-flex">
            <svg>
                <use xlink:href="{{asset('front-assets/svg/sprite.svg#cloud')}}"></use>
            </svg>
            <div class="info-error align-items-center d-flex">
                <div>
                    {{ShowLabelById(215,$lang_id)}}
                    <br>
                    {{ShowLabelById(216,$lang_id)}}
                </div>
            </div>
        </div>
    </div>
@stop

@include('front.footer')
