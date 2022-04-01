@extends('admin.app')

@section('content')

    <div class="error-page-msg">
        <span>{{trans('variables.be_right_back')}}</span>
    </div>

@stop

@section('footer')
    <footer>
        @include('admin.footer')
    </footer>
@stop