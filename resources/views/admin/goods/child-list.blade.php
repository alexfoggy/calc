@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if(empty($child_goods_list) && empty($child_goods_item_list))
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsSubject' || request()->segment(4) == 'createGoodsItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                            trans('variables.add_item') => urlForFunctionLanguage($lang, 'createGoodsItem/creategoodsitem'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                            trans('variables.item_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_subject') => urlForLanguage($lang, 'creategoodssubject'),
                            trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                            trans('variables.item_basket') => urlForLanguage($lang, 'goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsSubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif

            @endif
        @elseif(CheckIfSubjectHasItems('goods', $goods_list_id->id)->isEmpty())
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsSubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_subject') => urlForLanguage($lang, 'creategoodssubject'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsSubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodssubjectcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif
            @endif
        @else
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_item') => urlForFunctionLanguage($lang, 'createGoodsItem/creategoodsitem'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGoodsItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsItemCart/goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                        ],
                        'search' => 'true'
                    ])
                @endif
            @endif
        @endif


        <div class="list-table">
            @if(!CheckIfSubjectHasOtherItems('goods', $goods_list_id->id)->isEmpty())
                @if(!empty($child_goods_item_list))
                    <div class="search-block">
                        <form class="form" method="POST"
                                action="{{ urlForLanguage($lang, 'searchObjects') }}" id="search-form"
                                enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="search-label">
                                <label for="search-key">{{trans('variables.search_object')}}</label>
                                <input name="search-key" id="search-key">
                            </div>
                            <button class="btn" onclick="saveForm(this)" data-form-id="search-form"
                                    id="submit-search">{{trans('variables.search_object_it')}}</button>
                            <div class="loader-list"></div>
                        </form>
                    </div>
                    <table class="table" id="tablelistsorter" action="item" url="{{$url_for_active_elem}}"
                            empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                            <tr>
                                {{--<th>{{trans('variables.barcode')}}</th>--}}
                                <th>{{trans('variables.title_table')}}</th>
{{--
                                <th>{{trans('variables.elements')}}</th>
--}}
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

                                    <td>
                                        <span>{{!empty(IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item')) ? IfHasName($one_goods_item_list->goods_item_id, $lang_id, 'goods_item') : trans('variables.another_name')}}</span>
                                    </td>
                                    {{--<td class="link-block">
                                        <a href="{{urlForLanguage($lang, 'itemsphoto/'.$one_goods_item_list->goods_item_id)}}">{{trans('variables.photo')}}</a>
                                    </td>--}}
                                    <td class="edit-links">
                                        @foreach($lang_list as $lang_key => $one_lang)
                                            {{--getSubjectByItem($one_goods_item_list->goodsItemId->goods_subject_id)->alias--}}
                                            <a href="{{urlForFunctionLanguage($lang, request()->segment(4).'/editgoodsitem/'.$one_goods_item_list->goods_item_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_item_list->goods_item_id, $one_lang->id, 'goods_item')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
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

                        {{--$color_from_subject--}}


                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    @if($child_goods_item_list_id instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                        @include('admin.pagination', ['paginator' => $child_goods_item_list_id])
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                @else
                    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif
            @elseif(CheckIfSubjectHasItems('goods', $goods_list_id->id)->isEmpty())

                @if(!empty($child_goods_list))
                    <div class="search-block">
                        <form class="form" method="POST"
                                action="{{ urlForLanguage($lang, 'searchObjects') }}" id="search-form"
                                enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="search-label">
                                <label for="search-key">{{trans('variables.search_object')}}</label>
                                <input name="search-key" id="search-key">
                            </div>
                            <button class="btn" onclick="saveForm(this)" data-form-id="search-form"
                                    id="submit-search">{{trans('variables.search_object_it')}}</button>
                            <div class="loader-list"></div>
                        </form>
                    </div>
                    <table class="table" id="tablelistsorter" action="subject" url="{{$url_for_active_elem}}"
                            empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                            <tr>
                                <th>{{trans('variables.title_table')}}</th>
                               {{-- <th>{{trans('variables.parameters')}}</th>--}}
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
                            @foreach($child_goods_list as $key => $one_goods_subject_list)
                                <tr id="{{$one_goods_subject_list->goods_subject_id}}">
                                    <td>
                                        <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/memberslist')}}">{{!empty(IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject') : trans('variables.another_name')}}</a>
                                    </td>
                                   {{-- <td class="link-block">
                                        --}}{{--@if(IfHasChildActive($one_goods_subject_list->goods_subject_id, 'goods_subject')->isEmpty() && !CheckIfSubjectHasItems('goods', $one_goods_subject_list->goods_subject_id)->isEmpty())--}}{{--
                                        <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/goodsparameters/'.$one_goods_subject_list->goods_subject_id)}}">{{trans('variables.parameters')}}</a>
                                        --}}{{--@endif--}}{{--
                                    </td>--}}
                                    <td class="edit-links">
                                        @foreach($lang_list as $lang_key => $one_lang)
                                            {{--                                        <a href="{{urlForFunctionLanguage($lang, GetParentAlias($one_goods_subject_list->goods_subject_id, 'goods_subject_id').'/editgoodssubject/'.$one_goods_subject_list->goods_subject_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_subject_list->goods_subject_id, $one_lang->id, 'goods_subject')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>--}}
                                            <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/editgoodssubject/'.$one_goods_subject_list->goods_subject_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_goods_subject_list->goods_subject_id, $one_lang->id, 'goods_subject')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                        @endforeach
                                    </td>
                                    @if($groupSubRelations->active == 1)
                                        <td class="small active-link">
                                            <a href=""
                                                    class="change-active{{$one_goods_subject_list->goodsSubjectId->active == 1 ? ' active' : ''}}"
                                                    data-active="{{$one_goods_subject_list->goodsSubjectId->active}}"
                                                    element-id="{{$one_goods_subject_list->goodsSubjectId->id}}" action="subject"
                                                    url="{{$url_for_active_elem}}"></a>
                                        </td>
                                    @endif
                                    <td class="dragHandle" nowrap=""></td>
                                    @if($groupSubRelations->del_to_rec == 1)
                                        @if(IfHasChildActive($one_goods_subject_list->goods_subject_id, 'goods_subject')->isEmpty() && CheckIfSubjectHasItems('goods', $one_goods_subject_list->goods_subject_id)->isEmpty())
                                            <td class="check-destroy-element">
                                                <input type="checkbox" class="remove-all-elements"
                                                        name="destroy_elements[{{$one_goods_subject_list->goods_subject_id}}]"
                                                        value="{{$one_goods_subject_list->goods_subject_id}}"
                                                        url="{{urlForFunctionLanguage($lang, Str::slug($one_goods_subject_list->name).'/destroyGoodsSubjectToCart')}}">
                                            </td>
                                        @else
                                            <td>{{trans('variables.delete_inner_modules')}}</td>
                                        @endif
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
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>
    {{--@foreach($color_from_subject as $color )--}}
        {{--<label style="display: inline">--}}
            {{--<input type="checkbox" value="{{$color->id}}" name="goods_colors_id[{{$color->id}}]"  />--}}
            {{--<img src="http://oblanc-shop.webit.md/upfiles/goods/{{$color->img}}" height="30px;" width="30px" alt="" style="margin-right: 30px;"/>--}}
            {{--<span style="position: absolute; top: 25px; margin-left: -60px;">{{$color->name}}</span>--}}
        {{--</label>--}}
    {{--@endforeach--}}
    {{--@foreach($parameter_value_id as $value_id )--}}
        {{--<p>{{$value_id->id}}}</p>--}}
    {{--@endforeach--}}
@stop
