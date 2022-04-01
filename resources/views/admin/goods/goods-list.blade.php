@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGoodsSubject/creategoodssubject'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart')
                ],
                'search' => 'true'
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'goodsSubjectCart/goodssubjectcart')
                ],
                'search' => 'true'
            ])
        @endif

        <div class="list-table">
            @if(!empty($goods_subject_list))
                <div class="search-block">
                    <form class="form" method="POST"
                          action="{{ urlForFunctionLanguage($lang, 'goodsSubject/searchObjects') }}" id="search-form"
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
                        {{--<th>{{trans('variables.parameters')}}</th>--}}
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
                    @foreach($goods_subject_list as $key => $one_goods_subject_list)
                        <tr id="{{$one_goods_subject_list->goods_subject_id}}">
                            <td>
                                <a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/memberslist')}}">{{!empty(IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject_list->goods_subject_id, $lang_id, 'goods_subject') : trans('variables.another_name')}}</a>
                            </td>
                           {{-- <td class="link-block">--}}
                                {{--@if(IfHasChildActive($one_goods_subject_list->goods_subject_id, 'goods_subject')->isEmpty() && !CheckIfSubjectHasItems('goods', $one_goods_subject_list->goods_subject_id)->isEmpty())--}}
                                    {{--<a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/goodsparameters/'.$one_goods_subject_list->goods_subject_id)}}">{{trans('variables.parameters')}}</a>--}}
                                {{--@endif--}}
                                {{--<a href="{{urlForFunctionLanguage($lang, $one_goods_subject_list->goodsSubjectId->alias.'/goodsparameters/'.$one_goods_subject_list->goods_subject_id)}}">{{trans('variables.parameters')}}</a>--}}
                            {{--</td>--}}
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
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
                            <td class="dragHandle"></td>
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
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $goods_subject_id_list])
                        </td>
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