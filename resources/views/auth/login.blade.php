@extends('admin.app')

@section('content')

    <div class="login-register-page">
        <div class="login-block-title">Login</div>
        <div class="login-block-content">
            <form method="POST" action="{{ url($lang.'/back/auth/login') }}" id="login-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="field-row">
                    <div class="field-wrap">
                        <input id="login" name="login" placeholder="Login">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-wrap">
                        <input type="password" id="password" name="password" placeholder="Password">
                    </div>
                </div>
                <button class="btn" onclick="saveForm(this)"
                        data-form-id="login-form">{{trans('variables.sing_in')}}</button>
            </form>
        </div>
    </div>

@stop

