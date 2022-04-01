
      <div class="row">
        <div class="col-lg-12">
      <h3>
        <a href="{{url($lang,'calculator')}}">
          Калькуляторы
        </a>
      </h3>
      <div class="d-flex flex-wrap">
          @if($search_calc)
              @foreach($search_calc as $one_calc)
      <h4>
        <a href="{{url($lang,['calculator',$one_calc->parent->alias,$one_calc->alias])}}">
            {!! str_replace($search_list, $search_list_text, $one_calc->name) !!}
        </a>
      </h4>
              @endforeach
              @else

          @endif

    </div>
  </div>
{{--  <div class="col-lg-6 search-archive">
    <h3><a href=""> Справочник </a></h3>
    <div class="d-flex flex-wrap">
        @if($search_archive)
            @foreach($search_archive as $one_arch)
                <h4>
                    <a href="">
                        {!! str_replace($search_list, $search_list_text,  $one_arch->name) !!}
                    </a>
                </h4>
            @endforeach
            @else

        @endif

    </div>
  </div>--}}
</div>
