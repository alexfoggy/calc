@extends('front.front-app')

@include('front.header')

@section('container')


    <!-- Hero start -->
    <section class="hero">
        <div class="container main">
            <span class="hash">{{ShowLabelById(192,$lang_id)}}</span>
            <h1>{{ShowLabelById(193,$lang_id)}}</h1>
            <h2>{{ShowLabelById(194,$lang_id)}}</h2>
        </div>
        <div class="container calcs">
            <div class="row justify-content-between" id="gridUp">
               {{-- @if($calc_categorys)
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
                @endif--}}
            </div>
        </div>
       {{-- <div class="container main-archiv">
            <span class="hash green">#calc.md</span>
            <h2>Архив который нужен каждому</h2>
            <h3>важное и нужное</h3>
        </div>
        @if($arch)
        <div class="container archiv-intrudaction">
            @foreach($arch as $one_arch)
            <div class="row">
                <div class="col-lg-10">
                    <div class="archiv-block">
                        <h3>{{$one_arch->itemByLang->name ?? ''}}</h3>
                        <h4>По городам</h4>
                        <div class="acv-block">
                            @foreach($one_arch->children as $one_city)
                            <a href="{{url($lang,['archive',$one_city->alias])}}">
                                {{$one_city->itemByLang->name ?? ''}}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <a href='{{url($lang,['archive',$one_arch->alias])}}' class="more-and-more">
                        <svg>
                            <use xlink:href="{{asset('front-assets/svg/sprite.svg#more-arrow')}}"></use>
                        </svg>
                        <span>Смотреть все</span>
                    </a>
                </div>
            </div>
                @endforeach
            @endif

        </div>--}}
       {{-- <div class="container about">
            <div class="ttl">{{ShowLabelById(195,$lang_id)}}</div>
            <div class="text">
                Calc.md - бесплатный сервис онлайн-калькуляторов. С помощью нашего сервиса Вы сможете без регистрации и
                прочих проверок быстро и точно произвести необходимые вычисления. Мы постоянно работаем над улучшением
                предоставляемого Вам сервиса и созданием новых калькуляторов, чтобы каждый пользователь смог оперативно
                и максимально точно произвести нужные расчеты. Мы прилагаем много усилий для улучшения нашего сервиса и
                искренне надеемся, что с помощью представленных онлайн-калькуляторов Вы смогли решить поставленные перед
                Вами задачи. Если Вам понравился наш сервис онлайн-калькуляторов, то добавляйте его в закладки и
                расскажите про него друзьям через Вашу социальную сеть.

                Все онлайн-калькуляторы были протестированы на наличие погрешностей и ошибок и только после этого
                представлены Вам для пользования. Однако, если Вы нашли какую-либо ошибку или не точность, то мы
                приносим свои извинения и просим сообщить нам о недоработке через форму обратной связи.

                Наш сайт постоянно находится в процессе разработки и будет пополняться новыми онлайн-калькуляторами и
                прочими сервисами. Мы будем рады всем Вашим комментариям и предложениям по улучшению ресурса.
            </div>
        </div>--}}
        <!-- <div class="container">
          <div class="ttl">
            Налоговые калькуляторы
          </div>
          <div class="row">
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НД 1231231С</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НДС</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>
            <div class="item">
              <a href="">
                <h2>Подсчет НД 33333 3С</h2>
                <span>подходит для подсчета...</span>
              </a>
            </div>

          </div>
        </div> -->
    </section>
    <!-- Hero end -->


@stop

@push('scripts')
    <script>

        async function fetchText() {
            let response = await fetch('https://www.calc.md/api/getmain',
                {
                    headers: {
                        'Content-Type': 'application/json'
                        // 'Content-Type': 'application/x-www-form-urlencoded',
                    },
                });
            let data = await response.json();
            console.log(data);
            let block = '';
            let inUrl = '';
            let checknum = -1;
            for(let i = 0;i < data.length;i++){
                for(let j = 0;j < data[i].children.length; j++) {
                    if(checknum == data[i].id){

                    }
                    else {
                        inUrl = '';
                    }
                    checknum = data[i].id;
                    inUrl += '<h4><a href="ru/calculator/'+data[i].alias+'/'+data[i].children[j].alias+'">'+data[i].children[j].item_by_lang.name+'</a></h4>'

                }

                block += '<div class="grid-item mx-2">'+
                    '<div class="calc-category">'+
                    '<h3>'+
                    '<a href="/ru/'+data[i].alias+'">'+
                    data[i].item_by_lang.name+
                    '</a>'+
                    '</h3>'+
                    '<div class="d-flex flex-wrap">'+inUrl+
                    ' </div>'+
                ' <div class="icon-background">'+
                '<svg>'+
                ' <use xlink:href="/front-assets/svg/sprite.svg#math'+i+'"></use>'+
                '</svg>'+
                ' </div>'+
                '</div>'+
                '</div>';
            }


            $('#gridUp').append(block);

        }
        fetchText();
    </script>
@endpush

@include('front.footer')