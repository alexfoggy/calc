@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')



    <section class="archive-page">

        <div class="container pt-4">
            <div class="row">
                <div class="col-lg-6">
                    <h1>{{$goods_item->itemByLang->name ?? ''}}</h1>
                    {!! $goods_item->itemByLang->body ?? '' !!}

                </div>
                <div class="col-lg-6">
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1360.2658302334164!2d28.826057545754995!3d47.01016820847129!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4969a8a9cbbff7d3!2sOficiul%20Po%C5%9Ftal%20MD-2006!5e0!3m2!1sru!2s!4v1639777889472!5m2!1sru!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>

    </section>




@stop

@include('front.footer')
