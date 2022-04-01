@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')
    <section class="calculator-page">
        <div class="container">
            {{ Breadcrumbs::render('calcs') }}


        </div>
        <div class="container calcs">
            <div class="ttl mb-3 mt-2">{{$page->itemByLang->name ?? ''}}</div>
            <div class="row isotopegrid">
                @if($calc_categorys)
                    @foreach($calc_categorys as $one_subject)
                        <div class="grid-item">
                            <div class="calc-category">
                                <h3>
                                    <a href="{{url($lang,['calculator',$one_subject->alias])}}">
                                        {{$one_subject->itemByLang->name ?? ''}}
                                    </a>
                                </h3>
                                <div class="d-flex flex-wrap">
                                    @foreach($one_subject->children as $one_calc)
                                        <h4>
                                            <a href="{{url($lang,['calculator',$one_subject->alias,$one_calc->alias])}}">{{$one_calc->itemByLang->name ?? ''}}</a>
                                        </h4>
                                    @endforeach
                                </div>
                                <div class="icon-background">
                                    <svg>
                                        <use xlink:href="{{asset('front-assets/svg/sprite.svg#math'.$loop->iteration)}}"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </section>

@stop

@include('front.footer')
