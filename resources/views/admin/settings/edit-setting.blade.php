@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createSetting/createitem'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($settings_without_lang->name).'/edititem/'.$settings_without_lang->settings_id.'/'.$lang_id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($settings_without_lang->name).'/edititem/'.$settings_without_lang->settings_id.'/'.$lang_id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$settings->name ?? '' }}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$settings_without_lang->settings_id.'/'.$lang_id) }}" id="edit-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_to_edit ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="set_type">{{trans('variables.parameter_type')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="set_type" id="set_type" class="select2">
                                <option value="input" {{(!is_null($settings_id)) ? (($settings_id->set_type == 'input' ? 'selected' : '')) : ''}}>{{trans('variables.input')}}</option>
                                <option value="textarea" {{(!is_null($settings_id)) ? (($settings_id->set_type == 'textarea' ? 'selected' : '')) : ''}}>{{trans('variables.textarea')}}</option>
                                <option value="ckeditor" {{(!is_null($settings_id)) ? (($settings_id->set_type == 'ckeditor' ? 'selected' : '')) : ''}}>{{trans('variables.ckeditor')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$settings->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$settings_without_lang->settingsId->alias ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row ckeditor hidden">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.body')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$settings->body ?? $settings_without_lang->body}}</textarea>
                        </div>
                    </div>
                    <div class="fields-row input">
                        <div class="label-wrap">
                            <label for="input">{{trans('variables.input')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="input" id="input" value="{{$settings->body ?? $settings_without_lang->body}}">
                        </div>
                    </div>
                    <div class="fields-row textarea hidden">
                        <div class="label-wrap">
                            <label for="textarea">{{trans('variables.body')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="textarea" id="textarea" rows="10">{{$settings->body ?? $settings_without_lang->body}}</textarea>
                        </div>
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
