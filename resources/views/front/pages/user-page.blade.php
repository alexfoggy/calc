@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

    <section class="user-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-3"> <div class="user">
                        <div class="user-name">
                            {{$user->name ?? 'AGENT 007'}}
                            <div class="status">Студент</div>
                        </div>
                    </div></div>
                <div class="col-lg-3"></div>
                <div class="col-lg-3"></div>
                <div class="col-lg-3"></div>

            </div>
            <h3>Расписание составленые пользователем</h3>


            @if($tablesRelated)

            <div class="tabels-that-created row">
                @foreach($tablesRelated as $one_table)

                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <a href="{{url($lang,['table',$one_table->id])}}">
                        <span class="big-text">Расписание для {{$one_table->getBody->group_name ?? ''}}</span>

                        <span class="middle-text"> {{$one_table->getBody->group_name ?? ''}}</span>
                        <span>Город {{$one_table->getBody->city ?? ''}}</span>
                    </a>
                </div>
                @endforeach








{{--                <a >--}}
{{--                    <span>{{$one_table->getBody->group_name ?? ''}}</span>--}}
{{--                    <span>{{$one_table->getBody->class ?? ''}}</span>--}}
{{--                    <span>{{$one_table->getBody->city ?? ''}}</span>--}}
{{--                    <span>{{Carbon\Carbon::parse($one_table->created_at)->locale($lang)->isoFormat('DD MMM YYYY')}}</span>--}}
{{--                </a>--}}

            </div>
                @if(!empty($new_url))
                    @include('front.templates.pagination', ['paginator' => $tablesRelated, 'new_url' => $new_url])
                @else
                    @include('front.templates.pagination', ['paginator' => $tablesRelated, 'new_url' => ''])
                @endif
                @endif
        </div>
    </section>


@stop

@include('front.footer')
