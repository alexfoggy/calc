@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.parameters_list') => urlForLanguage($lang, 'goodsparameters/'.$goods_subject_id->id),
                    trans('variables.add_parameter') => urlForLanguage($lang, 'creategoodsparameter/'.$goods_subject_id->id),
                    trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsparameter/'.$goods_parameter_without_lang->goods_parametr_id.'/'.$lang_id),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsparametercart/'.$goods_subject_id->id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.parameters_list') => urlForLanguage($lang, 'goodsparameters/'.$goods_subject_id->id),
                    trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsparameter/'.$goods_parameter_without_lang->goods_parametr_id.'/'.$lang_id),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsparametercart/'.$goods_subject_id->id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$goods_parametr->name ?? ''}}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST"
                      action="{{ urlForLanguage($lang, 'savegoodsparameter/'.$goods_parameter_without_lang->goods_parametr_id.'/'.$lang_id) }}"
                      id="edit-form" enctype="multipart/form-data" page="edit-parameter">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="goods_subject_id" value="{{$goods_subject_id->id ?? ''}}">
                    <input type="hidden" name="measure_type" value="{{$goods_parametr_id->measure_type ?? ''}}">
                    <input type="hidden" name="parametr_type" value="{{$goods_parametr_id->parametr_type ?? ''}}">

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang" class="select2">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_to_edit ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$goods_parametr->name ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$goods_parametr_id->alias ?? ''}}">
                        </div>
                    </div>

                    @if(!empty($goods_parameter_value))
                        <div class="fields-row">
                            <div class="fields-title">
                                <span>{{trans('variables.parametr_value')}}</span>
                            </div>
                            <div class="label-wrap">
                                <label>{{trans('variables.name_text')}}</label>
                            </div>
                            <div class="input-wrap">
                                <table id="tablelistsorter_parametrvalue" class="table" url="{{$url_for_active_elem}}">
                                    <tbody>
                                    @foreach($goods_parameter_value as $key => $one_parameter_value)
                                        <tr id="{{$one_parameter_value->parametrValueId->id}}"
                                            param-id="{{$goods_parametr_id->id}}">
                                            <td class="dragHandle"></td>
                                            <td>
                                                <input name="parametr_type_value[{{$one_parameter_value->parametrValueId->id}}]"
                                                       class="parameter-input" style="background: rgb(255, 255, 255);"
                                                       value="{{!empty(IfHasName($one_parameter_value->parametrValueId->id, $lang_to_edit, 'goods_parametr_value')) ? IfHasName($one_parameter_value->parametrValueId->id, $lang_to_edit, 'goods_parametr_value') : ''}}">
                                                <span class="delparametrtypevalues"></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn"
                                        id="morevalues">{{trans('variables.add_value')}}</button>
                            </div>
                        </div>
                    @endif
                    @if($goods_parametr_id->parametr_type == 'input')
                        <div class="fields-row" id="tr_measure_type">
                            <div class="label-wrap">
                                <label for="measure_type">{{trans('variables.measure_type')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="measure_type" id="measure_type" class="select2">
                                    <option value="no_measure" {{$goods_parametr_id->measure_type == 'no_measure' ? 'selected' : ''}}>{{trans('variables.without_measurement')}}</option>
                                    <option value="with_measure" {{$goods_parametr_id->measure_type == 'with_measure' ? 'selected' : ''}}>{{trans('variables.with_measurement')}}</option>
                                    <option value="measure_list" {{$goods_parametr_id->measure_type == 'measure_list' ? 'selected' : ''}}>{{trans('variables.with_list_of_measurement')}}</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="fields-row hidden goods_measure_list">
                        <div class="fields-title">
                            <span>{{trans('variables.list_of_measure')}}</span>
                        </div>
                        <div class="label-wrap">
                            <label for="goods_measure_list">{{trans('variables.one_measure_type')}}</label>
                        </div>
                        <div class="input-wrap">
                            @if(!$measure_list->isEmpty())
                                <table id="tablelistsorter_measure" class="table" url="{{$url_for_active_elem}}">
                                    <tbody>
                                    @foreach($measure_list as $key => $one_measure_list)
                                        <tr id="{{$one_measure_list->id}}" param-id="{{$goods_parametr_id->id}}">
                                            <td class="dragHandle" style="cursor: move;"></td>
                                            <td class="select-measure nowrap">
                                                <select name="goods_measure_list[{{$one_measure_list->id}}]"
                                                        class="goods_measure_list select2">
                                                    @foreach($measure as $one_measure)
                                                        <option value="{{$one_measure->goods_measure_id}}" {{$one_measure_list->goods_measure_id == $one_measure->goods_measure_id ? 'selected' : ''}}>{{$one_measure->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{--<span class="delmeasure"></span>--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <button type="button" id="moreterm"
                                    class="btn">{{trans('variables.add_new_measure')}}</button>
                        </div>
                    </div>
                    @if(!empty($measure) && $goods_parametr_id->parametr_type == 'input')
                        <div class="fields-row goods_measure_id">
                            <div class="label-wrap">
                                <label for="goods_measure_id">{{trans('variables.one_measure_type')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="goods_measure_id" id="goods_measure_id" class="select2">
                                    @foreach($measure as $one_measure)
                                        <option value="{{$one_measure->goods_measure_id}}" {{$goods_parametr_id->goods_measure_id == $one_measure->goods_measure_id ? 'selected' : ''}}>{{$one_measure->name}}</option>
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
                            <input type="checkbox" name="active"
                                   id="active" {{$goods_parametr_id->active == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    {{--<div class="fields-row">--}}
                    {{--<div class="label-wrap">--}}
                    {{--<label for="show_in_list">{{trans('variables.show_in_list')}}</label>--}}
                    {{--</div>--}}
                    {{--<div class="input-wrap">--}}
                    {{--<input type="checkbox" name="show_in_list" id="show_in_list" {{$goods_parametr_id->show_in_list == 1 ? 'checked' : ''}}>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="fields-row">--}}
                    {{--<div class="label-wrap">--}}
                    {{--<label for="font_for_list">{{trans('variables.font_for_list')}}</label>--}}
                    {{--</div>--}}
                    {{--<div class="input-wrap">--}}
                    {{--<input name="font_for_list" id="font_for_list" value="{{$goods_parametr_id->font_for_list ?? ''}}">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="fields-row">--}}
                    {{--<div class="label-wrap">--}}
                    {{--<label for="display_on_list_page">{{trans('variables.display_on_list_page')}}</label>--}}
                    {{--</div>--}}
                    {{--<div class="input-wrap">--}}
                    {{--<input type="checkbox" name="display_on_list_page" id="display_on_list_page" {{$goods_parametr_id->display_on_list_page == 1 ? 'checked' : ''}}>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="start_open">{{trans('variables.start_open')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="start_open"
                                   id="start_open" {{$goods_parametr_id->start_open == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    {{--<div class="fields-row">--}}
                    {{--<div class="label-wrap">--}}
                    {{--<label for="display_in_line">{{trans('variables.display_in_line')}}</label>--}}
                    {{--</div>--}}
                    {{--<div class="input-wrap">--}}
                    {{--<input type="checkbox" name="display_in_line" id="display_in_line" {{$goods_parametr_id->display_in_line == 1 ? 'checked' : ''}}>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop