@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')



    <section class="table-page">
        <div class="container">
            <h1>Расписание Группы TI-221</h1>
            <h2>UTM | Город:Кишинев</h2>
            <h3>
                <span class='timenow'>^_^</span>
                <span class='weekday'>Хороший денек</span>
                <span class='nowgo'>Сейчас ты должен учиться</span>
                <span class='nowalk'>Перемена</span>
            </h3>
            <div class="table">
                <div class="obilt">
                    <a href="javascript:void(0)">
                        <svg viewBox="0 0 100 100">
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#more')}}"></use>
                        </svg>
                    </a>
                    @if(Session::get('session-front-user') == $curientTable->front_user_id)
                    <a href="">
                        <svg viewBox="0 0 100 100">
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#edit')}}"></use>
                        </svg>
                    </a>
                    @endif
                    <a href="javascript:;" class="createPdf" data-id="{{$curientTable->id}}">
                        <svg viewBox="0 0 100 100">
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#printer')}}"></use>
                        </svg>
                    </a>
                    @if(Session::get('session-front-user'))
                    <a href="javascript:void(0)" class='savetofav'>
                        <svg viewBox="0 0 100 100">
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#star')}}"></use>
                        </svg>
                    </a>
                        @endif
                </div>
                <div class="overflow">
                    <table>
                        <tr class="active">
                            <th>День/Время</th>
                            <th class='day1'>Понедельник</th>
                            <th class='day2'>Вторник</th>
                            <th class='day3'>Среда</th>
                            <th class='day4'>Четверг</th>
                            <th class='day5'>Пятница</th>
                            <th class='day6'>Суббота</th>
                        </tr>
                        @php
                            $i = 0;
                        @endphp
                        @for($y = 0; $y < $curientTable->row_count; $y++)
                        <tr class="active">
                            <th>С <span class='timefrom' data-timefrom='{{$defacBody[$i]}}/{{$defacBody[$i+1]}}'>{{$defacBody[$i]}}</span> до <span class='timeto'
                                                                                                        data-timeto='9:30'>{{$defacBody[$i+1]}}</span></th>
                            <td>{{$defacBody[$i+2]}}</td>
                            <td>{{$defacBody[$i+3]}}</td>
                            <td>{{$defacBody[$i+4]}}</td>
                            <td>{{$defacBody[$i+5]}}</td>
                            <td>{{$defacBody[$i+6]}}</td>
                            <td>{{$defacBody[$i+7]}}</td>
                        </tr>
                            @php
                            $i = $i + 8;
                            @endphp
                        @endfor
{{--                        <tr>--}}
{{--                            <th>С <span class='timefrom' data-timefrom='10:00/11:30'>10:00</span> до <span class='timeto'--}}
{{--                                                                                                           data-timeto='11:30'>11:30</span></th>--}}
{{--                            <td>1</td>--}}
{{--                            <td>2</td>--}}
{{--                            <td>3</td>--}}
{{--                            <td>4</td>--}}
{{--                            <td>5</td>--}}
{{--                            <td>6</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>С <span class='timefrom' data-timefrom='12:00/13:30'>12:00</span> до <span class='timeto'--}}
{{--                                                                                                           data-timeto='13:30'>13:30</span></th>--}}
{{--                            <td>1</td>--}}
{{--                            <td>2</td>--}}
{{--                            <td>3</td>--}}
{{--                            <td>4</td>--}}
{{--                            <td>5</td>--}}
{{--                            <td>6</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>С <span class='timefrom' data-timefrom='14:00/15:30'>14:00</span> до <span class='timeto'--}}
{{--                                                                                                           data-timeto='15:30'>15:30</span></th>--}}
{{--                            <td>1</td>--}}
{{--                            <td>2</td>--}}
{{--                            <td>3</td>--}}
{{--                            <td>4</td>--}}
{{--                            <td>5</td>--}}
{{--                            <td>6</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>С <span class='timefrom' data-timefrom='16:00/17:30'>16:00</span> до <span class='timeto'--}}
{{--                                                                                                           data-timeto='17:30'>17:30</span></th>--}}
{{--                            <td>1</td>--}}
{{--                            <td>2</td>--}}
{{--                            <td>3</td>--}}
{{--                            <td>4</td>--}}
{{--                            <td>5</td>--}}
{{--                            <td>6</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>С <span class='timefrom' data-timefrom='18:00/19:30'>18:00</span> до <span class='timeto'--}}
{{--                                                                                                           data-timeto='19:30'>19:30</span></th>--}}
{{--                            <td>1</td>--}}
{{--                            <td>2</td>--}}
{{--                            <td>3</td>--}}
{{--                            <td>4</td>--}}
{{--                            <td>5</td>--}}
{{--                            <td>6</td>--}}
{{--                        </tr>--}}

                    </table>
                </div>
            </div>
            @if($curientTable->shorts->isNotEmpty())
            <div class="shorts">
                <h3>События</h3>
                @foreach($curientTable->shorts as $one_short)
                <div class="one-short">
                    <div class="date-stamp">
                        {{Carbon\Carbon::parse($one_short->created_at)->locale($lang)->isoFormat('DD MMM YYYY')}}
                    </div>
                    <h3>{{$one_short->head_shorts}}</h3>
                    <p>{{$one_short->head_shorts}}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>






@stop

@include('front.footer')
