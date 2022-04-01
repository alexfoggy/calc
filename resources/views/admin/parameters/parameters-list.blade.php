@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

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


        <div class="list-table">
            @if(!empty($goods_parameter))
                <table class="table" id="tablelistsorter" action="parameter" url="{{$url_for_active_elem}}"
                       empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        <th>{{trans('variables.position_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($goods_parameter as $key => $one_goods_parameter)
                        <tr id="{{$one_goods_parameter->goods_parametr_id}}">
                            <td>
                                <span>{{!empty(IfHasName($one_goods_parameter->goods_parametr_id, $lang_id, 'goods_parametr')) ? IfHasName($one_goods_parameter->goods_parametr_id, $lang_id, 'goods_parametr') : trans('variables.another_name')}}</span>
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForLanguage($lang, 'editgoodsparameter/'.$one_goods_parameter->goods_parametr_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_parameter->goods_parametr_id, $one_lang->id, 'goods_parametr')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$one_goods_parameter->parametrId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_goods_parameter->parametrId->active}}"
                                       element-id="{{$one_goods_parameter->parametrId->id}}" action="parameter"
                                       url="{{$url_for_active_elem}}"></a>
                                </td>
                            @endif
                            <td class="dragHandle" nowrap=""></td>
                            @if($groupSubRelations->del_to_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$one_goods_parameter->goods_parametr_id}}]"
                                           value="{{$one_goods_parameter->goods_parametr_id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($one_goods_parameter->name).'/destroyGoodsParameterToCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10"></td>
                    </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop
