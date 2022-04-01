@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBrand/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'brandsCart/cartitems')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'brandsCart/cartitems')
                ]
            ])
        @endif


        <div class="list-table">
            @if(!empty($brand_elements))
                <table class="table" id="tablelistsorter" empty-response="{{trans('variables.list_is_empty')}}">
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
                    @foreach($brand_elements as $one_brand)
                        <tr id="{{$one_brand->goods_brand_id}}">
                            <td>
                                <span>{{!empty(IfHasName($one_brand->goods_brand_id, $lang_id, 'goods_brand')) ? IfHasName($one_brand->goods_brand_id, $lang_id, 'goods_brand') : trans('variables.another_name')}}</span>
                            </td>
                            {{--<td class="link-block">
                                <a href="{{urlForFunctionLanguage($lang, $brand->id.'/edititem/'.$brand->id)}}">{{trans('variables.edit_table')}}</a>
                            </td>--}}

                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, $one_brand->brandId->alias.'/editItem/'.$one_brand->goods_brand_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_brand->goods_brand_id, $one_lang->id, 'goods_brand')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>

                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href="" class="change-active {{$one_brand->brandId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_brand->brandId->active}}"
                                       element-id="{{$one_brand->goods_brand_id}}"></a>
                                </td>
                            @endif
                            <td class="dragHandle" nowrap=""></td>
                            @if($groupSubRelations->del_to_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$one_brand->goods_brand_id}}]"
                                           value="{{$one_brand->goods_brand_id}}"
                                           url="{{urlForFunctionLanguage($lang, $one_brand->goods_brand_id.'/destroyBrandToCart')}}">
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
