@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.parameters_list') => urlForLanguage($lang, 'goodsparameters/'.$goods_subject_id->id),
                    trans('variables.add_parameter') => urlForLanguage($lang, 'creategoodsparameter/'.$goods_subject_id->id),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsparametercart/'.$goods_subject_id->id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.parameters_list') => urlForLanguage($lang, 'goodsparameters/'.$goods_subject_id->id),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsparametercart/'.$goods_subject_id->id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savegoodsparameter') }}"
                      id="add-form" enctype="multipart/form-data" page="create-parameter">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="goods_subject_id" value="{{$goods_subject_id->id ?? ''}}">
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
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="parametr_type">{{trans('variables.parameter_type')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="parametr_type" id="parametr_type" class="select2">
                                <option value="input">Input</option>
                                <option value="textarea">Textarea</option>
                                <option value="select">Select</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>
                    </div>
                    <div class="fields-row hidden" id="parametr_value">
                        <div class="fields-title">
                            <span>{{trans('variables.parametr_value')}}</span>
                        </div>
                        <div class="label-wrap">
                            <label for="parametr_type_value">{{trans('variables.name_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <div id="selectvalues">
                                <div id="tablelistsorter_parametrvalue" class="noborder">
                                    <div class="inputs">
                                        <input id="parametr_type_value" name="parametr_type_value[]" class="inp2"
                                               style="background: rgb(255, 255, 255);">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn" id="morevalues">{{trans('variables.add_value')}}</button>
                        </div>
                    </div>
                    <div class="fields-row" id="tr_measure_type">
                        <div class="label-wrap">
                            <label for="measure_type">{{trans('variables.measure_type')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="measure_type" id="measure_type" class="select2">
                                <option value="no_measure">{{trans('variables.without_measurement')}}</option>
                                <option value="with_measure" selected>{{trans('variables.with_measurement')}}</option>
                                <option value="measure_list">{{trans('variables.with_list_of_measurement')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="fields-row hidden goods_measure_list">
                        <div class="fields-title">
                            <span>{{trans('variables.list_of_measure')}}</span>
                        </div>
                        <div class="label-wrap">
                            <label for="goods_measure_list">{{trans('variables.one_measure_type')}}</label>
                        </div>
                        <div class="input-wrap">
                            <div class="goods_measure_list_cont">
                                @if(!empty($measure))
                                    <div class="select-measure">
                                        <select id="goods_measure_list" name="goods_measure_list[]"
                                                class="select2">
                                            @foreach($measure as $one_measure)
                                                <option value="{{$one_measure->goods_measure_id}}">{{$one_measure->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="moreterm" class="btn">{{trans('variables.add_new_measure')}}</button>
                        </div>
                    </div>
                    @if(!empty($measure))
                        <div class="fields-row goods_measure_id">
                            <div class="label-wrap">
                                <label for="goods_measure_id">{{trans('variables.one_measure_type')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="goods_measure_id" id="goods_measure_id" class="select2">
                                    @foreach($measure as $one_measure)
                                        <option value="{{$one_measure->goods_measure_id}}">{{$one_measure->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="active">{{trans('variables.active_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="active" id="active">
                        </div>
                    </div>
                    {{--<div class="fields-row">--}}
                        {{--<div class="label-wrap">--}}
                            {{--<label for="show_in_list">{{trans('variables.show_in_list')}}</label>--}}
                        {{--</div>--}}
                        {{--<div class="input-wrap">--}}
                            {{--<input type="checkbox" name="show_in_list" id="show_in_list">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="fields-row">--}}
                        {{--<div class="label-wrap">--}}
                            {{--<label for="font_for_list">{{trans('variables.font_for_list')}}</label>--}}
                        {{--</div>--}}
                        {{--<div class="input-wrap">--}}
                            {{--<input name="font_for_list" id="font_for_list">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="fields-row">--}}
                        {{--<div class="label-wrap">--}}
                            {{--<label for="display_on_list_page">{{trans('variables.display_on_list_page')}}</label>--}}
                        {{--</div>--}}
                        {{--<div class="input-wrap">--}}
                            {{--<input type="checkbox" name="display_on_list_page" id="display_on_list_page" checked>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="start_open">{{trans('variables.start_open')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="start_open" id="start_open">
                        </div>
                    </div>
                    {{--<div class="fields-row">--}}
                        {{--<div class="label-wrap">--}}
                            {{--<label for="display_in_line">{{trans('variables.display_in_line')}}</label>--}}
                        {{--</div>--}}
                        {{--<div class="input-wrap">--}}
                            {{--<input type="checkbox" name="display_in_line" id="display_in_line">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="add-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop
