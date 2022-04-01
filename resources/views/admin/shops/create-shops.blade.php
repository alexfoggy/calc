@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
					trans('variables.add_element') => urlForFunctionLanguage($lang, 'createShops/createitem'),
				]
			])
        @else
            @include('admin.list-elements', [
				'actions' => [
					trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
				]
			])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save') }}" id="add-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang" class="select2">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>

                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias">
                        </div>
                    </div>
                    <div class="fields-row link hide">
                        <div class="label-wrap">
                            <label for="phone">{{trans('variables.phone')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="text" name="phone" id="phone">
                        </div>
                    </div>
                    @if(!empty($city))
                        <div class="fields-row">
                            <div class="label-wrap">
                                <label for="city_id">{{trans('variables.p_id_name')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="city_id" id="city_id" class="select2">
                                    @foreach($city as $one_city)
                                        <option value="{{$one_city->city_id}}">{{$one_city->name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => '', 'element_by_lang' => ''])
                    </div>
                    <div class="fields-row link hide">
                        <div class="label-wrap">
                            <label for="schedule">{{trans('variables.schedule')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="text" name="schedule" id="schedule">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="address">{{trans('variables.address')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="address" id="address">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="type">{{trans('variables.type_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="type" id="type">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="distr">{{trans('variables.distractie')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="distr" id="distr">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="cafe">{{trans('variables.cafe_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="cafe" id="cafe">
                        </div>
                    </div>
                    <div class="fields-row link hide">
                        <div class="label-wrap">
                            <label for="latitude">{{trans('variables.latitude')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="latitude" name="latitude" value="47.02465276374675">
                        </div>
                    </div>
                    <div class="fields-row link hide">
                        <div class="label-wrap">
                            <label for="longitude">{{trans('variables.longitude')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="text" id="longitude" name="longitude" value="28.83242893218994">
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
                        <button class="btn" onclick="saveForm(this)" data-form-id="add-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@stop









