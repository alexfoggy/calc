@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

   <div class="container">
       <form action="{{url($lang,['calcit',$calc_id->alias])}}" id="form-conn" enctype="multipart/form-data" method="POST">
           <h1>{{$calc_id->itemByLang->name ?? ''}}</h1>
           <h2>{{$calc_id->formula ?? ''}}</h2>
           @if($rows)
               @foreach($rows as $one_row)
           <div class="">
               <p>
                   {{$one_row->itemByLang->name ?? ''}}
               </p>
               <input type="text" name="{{$one_row->variable}}" placeholder="">
           </div>
               @endforeach
           @endif
           <div class="result">

           </div>
           <button type="submit" data-form-id="form-conn" onclick="calcThis(this)">Подсчитать</button>
       </form>
   </div>

@stop

@include('front.footer')
