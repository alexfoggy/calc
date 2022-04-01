<div class="search-block">
    <form class="form" method="POST"
          action="{{ urlForFunctionLanguage($lang, 'searchObjects/searchObjects') }}" id="search-form"
          enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="search-label">
            <label for="search-key">{{trans('variables.search_object')}}</label>
            <input name="search-key"
                   id="search-key" placeholder="{{!empty(request()->get('search-key')) && !is_null(request()->get('search-key')) ? request()->get('search-key') : ''}}">
        </div>
        <button class="btn" onclick="saveForm(this)" data-form-id="search-form"
                id="submit-search">{{trans('variables.search_object_it')}}</button>
        <div class="loader-list"></div>
    </form>
</div>
@if(!empty($child_goods_item_list))
    <table class="table" id="tablelistsorter" action="item" url="{{$url_for_active_elem}}"
           empty-response="{{trans('variables.list_is_empty')}}">
        <thead>
        <tr>
            <th>{{trans('variables.barcode')}}</th>
            <th>{{trans('variables.title_table')}}</th>
            <th>{{trans('variables.elements')}}</th>
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
        @foreach($child_goods_item_list as $key => $one_goods_item_list)
            <tr id="{{$one_goods_item_list->goods_item_id}}">
                <td class="small">
                    <span>{{$one_goods_item_list->goodsItemId->barcode}}</span>
                </td>
                <td>
                    <span>{{!empty(IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item')) ? IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item') : trans('variables.another_name')}}</span>
                </td>
                <td class="link-block">
                    <a href="{{urlForFunctionLanguage($lang, $one_goods_item_list->goodsItemId->getSubjectId->alias . '/itemsphoto/'.$one_goods_item_list->goods_item_id)}}">{{trans('variables.photo')}}</a>
                </td>
                <td class="edit-links">
                    @foreach($lang_list as $lang_key => $one_lang)
                        <a href="{{urlForFunctionLanguage($lang, getSubjectByItem($one_goods_item_list->goodsItemId->goods_subject_id)->alias.'/editgoodsitem/'.$one_goods_item_list->goods_item_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_item_list->goods_item_id, $one_lang->id, 'goods_item')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                    @endforeach
                </td>
                @if($groupSubRelations->active == 1)
                    <td class="small active-link">
                        <a href=""
                           class="change-active{{$one_goods_item_list->goodsItemId->active == 1 ? ' active' : ''}}"
                           data-active="{{$one_goods_item_list->goodsItemId->active}}"
                           element-id="{{$one_goods_item_list->goodsItemId->id}}" action="item"
                           url="{{$url_for_active_elem}}"></a>
                    </td>
                @endif
                <td class="dragHandle" nowrap=""></td>
                @if($groupSubRelations->del_to_rec == 1)
                    <td class="check-destroy-element">
                        <input type="checkbox" class="remove-all-elements"
                               name="destroy_elements[{{$one_goods_item_list->goods_item_id}}]"
                               value="{{$one_goods_item_list->goods_item_id}}"
                               url="{{urlForFunctionLanguage($lang, Str::slug($one_goods_item_list->name).'/destroyGoodsItemToCart')}}">
                    </td>
                @endif
            </tr>
        @endforeach
        <tfoot>
        <tr>
            <td colspan="10">
                @if($child_goods_item_list instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    @include('admin.pagination', ['paginator' => $child_goods_item_list])
                @endif
            </td>
        </tr>
        </tfoot>
    </table>
@else
    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
