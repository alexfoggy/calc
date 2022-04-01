@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'create_user/createitem')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, '/'),
                ]
            ])
        @endif

        <div class="form-page">
            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$user->name ?? ''}}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$user->id) }}" id="edit-form"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.name_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$user->name ?? ''}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                           <label for="email">{{trans('variables.email_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="email" name="email" id="email" value="{{$user->email ?? ''}}"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="phone">{{trans('variables.phone')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="text" name="phone" id="phone" autocomplete="off" value="{{$user->phone ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="discount">Процент скидки</label>
                        </div>
                        <div class="input-wrap">
                            <input type="number" name="discount" id="discount" autocomplete="off" value="{{$user->discount ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="password">{{trans('variables.password_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="password" name="password" id="password"
                                   placeholder="{{trans('variables.empty_pass')}}" autocomplete="off">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="repeat_password">{{trans('variables.repeat_password')}}</label>
                       </div>
                        <div class="input-wrap">
                            <input type="password" name="repeat_password" id="repeat_password" autocomplete="off">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="update-date">{{trans('variables.updated_text')}}</label>
                        </div>
                        <div class="input-wrap">
                           <input id="update-date" value="{{$user->updated_at ?? ''}}" readonly>
                        </div>
                    </div>
                    <div class="fields-row">
                        {{--@include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $user, 'element_by_lang' => 'multiLanguageUploader'])--}}
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@stop