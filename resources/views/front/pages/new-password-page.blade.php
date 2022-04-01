@extends('front.front-app')

@section('meta-tags')
    @include('front.meta-tags.for-other-pages')
@stop

@include('front.header')

@section('container')

<div class="container">
    <h1 class="center mt-4 bold">{{ShowLabelById(146,$lang_id)}}</h1>
    <div class="center">
        <form action="{{url('saveNewPass')}}" class="form-spec" id="restorePass">
            <input type="password" name="password" placeholder="{{ShowLabelById(137,$lang_id)}}">
            <input type="password" name="password_confirmation" placeholder="{{ShowLabelById(137,$lang_id)}}">
            <input type="hidden" name="hash" value="{{request()->input('h')}}">
            <button data-form-id="restorePass" id="restorePass" type="submit" onclick="saveForm(this)">{{ShowLabelById(147,$lang_id)}}</button>
        </form>
    </div>
</div>

@stop

@include('front.footer')
