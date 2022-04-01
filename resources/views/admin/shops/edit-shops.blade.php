@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.add_element') => urlForFunctionLanguage($lang, 'createShops/createitem'),
					trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($shops_without_lang->name).'/edititem/'.$shops_without_lang->shops_id.'/'.$edited_lang_id)
				]
			])
        @else
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($shops_without_lang->name).'/edititem/'.$shops_without_lang->shops_id.'/'.$edited_lang_id)
				]
			])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$shops->name ?? ''}}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$shops_without_lang->shops_id.'/'.$edited_lang_id) }}" id="edit-form" data-parent-url="{{$url_for_active_elem}}" enctype="multipart/form-data">
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
                            <input name="name" id="name" value="{{$shops->name ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{ $shops_without_lang->shopsId->alias ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="phone">{{trans('variables.phone')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="phone" id="phone" value="{{ $shops_without_lang->shopsId->phone ?? ''}}">
                        </div>
                    </div>
                    @if(!empty($city))
                        <div class="fields-row">
                            <div class="label-wrap">
                                <label for="city_id">{{trans('variables.city')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="city_id" id="city_id" class="select2">
                                    @foreach($city as $one_city)
                                        <option value="{{$one_city->city_id}}" {{$shops_without_lang->shopsId->city_id == $one_city->city_id ? 'selected' : ''}}>{{$one_city->name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $shops_id, 'element_by_lang' => ''])
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="title">{{trans('variables.schedule')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="schedule" id="schedule" value="{{$shops->schedule ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="address">{{trans('variables.address')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="address" id="address" value="{{$shops->address ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="type">{{trans('variables.type_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="type" id="type" value="{{$shops->type ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="distr">{{trans('variables.distractie')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="distr" id="distr" value="{{$shops->distr ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="cafe">{{trans('variables.cafe_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="cafe" id="cafe" value="{{$shops->cafe ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="latitude">{{trans('variables.latitude')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="latitude" id="latitude" value="{{$shops_without_lang->shopsId->latitude ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="longitude">{{trans('variables.longitude')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="longitude" id="longitude" value="{{$shops_without_lang->shopsId->longitude ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="longitude">{{trans('variables.map')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="button" value="{{trans('variables.map')}}" id="show_gmap">
                        </div>
                        <div id="google_map"></div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)" data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop