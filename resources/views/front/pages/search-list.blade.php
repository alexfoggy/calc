@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')



    <section class="calculator-page">
        <div class="container">
            {{ Breadcrumbs::render('search', $lang_id) }}
        </div>
        <div class="container">
            <div class="ttl mb-3 mt-2"><span
                        class="font-weight-normal">{{ShowLabelById(198,$lang_id)}}</span> {{$search}}</div>
            <div class="list-fav">
                @foreach($search_calc as $one_calc)
                    <div class="fav-item">
                        <a href="{{url($lang,['calculator',$one_calc->parent->alias,$one_calc->alias])}}">
                            {{$one_calc->itemByLang->name ?? ''}}
                        </a>
                        <span class="fav-status add-to-fav-it <?php if(checkIfWishExist($one_calc->id) == true): ?> active <?php endif; ?>"
                              title="Добавить в избранное" data-id="{{$one_calc->id}}">
                   <svg>
                    <use xlink:href="{{asset('front-assets/svg/sprite.svg#saved')}}"></use>
                </svg>
            </span>
                    </div>
                @endforeach
            </div>
        </div>

    </section>

@stop

@include('front.footer')
