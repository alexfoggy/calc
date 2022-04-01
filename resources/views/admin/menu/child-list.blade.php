@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_element') => urlForLanguage($lang, 'createmenu'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
                ]
            ])
        @endif


        <div class="list-table">
            @if(!empty($child_menu_list))
                <table class="table" id="tablelistsorter" action="membersmenu" url="{{$url_for_active_elem}}"
                       empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                            @if($groupSubRelations->level == 1)
                            <th>{{trans('variables.top_menu')}}</th>
                            <th>{{trans('variables.footer_menu')}}</th>
                            @endif
                        @endif
                        <th>{{trans('variables.position_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($child_menu_list as $one_menu_element)
                        <tr id="{{$one_menu_element->menu_id}}">
                            @if(!IfHasChildActive($one_menu_element->menuId->id, 'menu')->isEmpty())
                                <td>
                                    <a href="{{urlForFunctionLanguage($lang, $one_menu_element->menuId->alias.'/memberslist')}}">{{!empty(IfHasName($one_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</a>
                                </td>
                            @else
                                <td>
                                    <span>{{!empty(IfHasName($one_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</span>
                                </td>
                            @endif
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, $one_menu_element->menuId->alias.'/editmenu/'.$one_menu_element->menu_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_menu_element->menu_id, $one_lang->id, 'menu')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$one_menu_element->menuId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_menu_element->menuId->active}}" action="membersmenu"
                                       url="{{$url_for_active_elem}}"
                                       element-id="{{$one_menu_element->menuId->id}}"></a>
                                </td>
                                @if($groupSubRelations->level == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-top-menu {{$one_menu_element->menuId->top_menu == 1 ? ' active' : ''}}"
                                       data-active="{{$one_menu_element->menuId->top_menu}}" action="membersmenu"
                                       url="{{$url_for_active_elem}}"
                                       element-id="{{$one_menu_element->menuId->id}}"></a>
                                </td>
                                <td class="small active-link">
                                    <a href=""
                                       class="change-footer-menu {{$one_menu_element->menuId->footer_menu == 1 ? ' active' : ''}}"
                                       data-active="{{$one_menu_element->menuId->footer_menu}}" action="membersmenu"
                                       url="{{$url_for_active_elem}}"
                                       element-id="{{$one_menu_element->menuId->id}}"></a>
                                </td>
                                @endif
                            @endif
                            <td class="dragHandle" nowrap=""></td>
                            @if($groupSubRelations->del_to_rec == 1)
                                @if(IfHasChildActive($one_menu_element->menuId->id, 'menu')->isEmpty())
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_menu_element->menu_id}}]"
                                               value="{{$one_menu_element->menu_id}}"
                                               url="{{urlForFunctionLanguage($lang, Str::slug($one_menu_element->name).'/destroyMenuToCart')}}">
                                    </td>
                                @else
                                    <td>
                                        <span>{{trans('variables.delete_inner_modules')}}</span>
                                    </td>
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
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop
