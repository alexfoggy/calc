@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.add_element') => urlForFunctionLanguage($lang, 'createCity/createitem'),
					trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($city_without_lang->name).'/edititem/'.$city_without_lang->city_id.'/'.$edited_lang_id)
				]
			])
        @else
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($city_without_lang->name).'/edititem/'.$city_without_lang->city_id.'/'.$edited_lang_id)
				]
			])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$city->name ?? ''}}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$city_without_lang->city_id.'/'.$edited_lang_id) }}" id="edit-form" data-parent-url="{{$url_for_active_elem}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang" class="select2">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $edited_lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$city->name ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$city_without_lang->cityId->alias ?? ''}}">
                        </div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)" data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@stop

