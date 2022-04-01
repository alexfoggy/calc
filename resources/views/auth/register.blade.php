@extends('admin.app')

@section('content')

	<div class="login-register-page">
		<div class="login-block-title">Register</div>
		<div class="login-block-content">
			<form method="POST" action="{{ url($lang.'/back/auth/register') }}" id="register-form">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="field-row">
					<div class="field-wrap">
						<input id="name" name="name" placeholder="Name">
					</div>
				</div>
				<div class="field-row">
					<div class="field-wrap">
						<input id="login" name="login" placeholder="Login">
					</div>
				</div>
				<div class="field-row">
					<div class="field-wrap">
						<input id="email" name="email" placeholder="Email">
					</div>
				</div>
				<div class="field-row">
					<div class="field-wrap">
						<input type="password" id="password" name="password" placeholder="Password">
					</div>
				</div>
				<div class="field-row">
					<div class="field-wrap">
						<input type="password" id="repeat_password" name="repeat_password" placeholder="Repeat password">
					</div>
				</div>
				<button class="btn" onclick="saveForm(this)" data-form-id="register-form">{{trans('variables.register')}}</button>
			</form>
		</div>
	</div>

@stop






