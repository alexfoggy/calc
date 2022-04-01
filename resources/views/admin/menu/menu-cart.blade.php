@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @if(request()->segment(5) == '' || request()->segment(4) == 'menuCart')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.add_element') => urlForFunctionLanguage($lang, 'createMenu/createmenu'),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
                    ]
                ])
            @else
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                        trans('variables.add_element') => urlForLanguage($lang, 'createmenu'),
                        trans('variables.elements_basket') => urlForLanguage($lang, 'menucart')
                    ]
                ])
            @endif
        @else
            @if(request()->segment(5) == '' || request()->segment(4) == 'menuCart')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'menuCart/menucart')
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
        @endif


        <div class="list-table">
            @if(!empty($deleted_menu_elems))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th class="restore-all">{{trans('variables.reestablish_table')}}</th>
                        @if($groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deleted_menu_elems as $one_menu_element)
                        <tr id="{{$one_menu_element->menu_id}}">
                            <td>
                                <span>{{!empty(IfHasName($one_menu_element->menu_id, $lang_id, 'menu')) ? IfHasName($one_menu_element->menu_id, $lang_id, 'menu') : trans('variables.another_name')}}</span>
                            </td>
                            <td class="medium check-restore-element">
                                <input type="checkbox" class="restore-all-elements"
                                       name="restore_elements[{{$one_menu_element->menu_id}}]"
                                       value="{{$one_menu_element->menu_id}}"
                                       url="{{urlForFunctionLanguage($lang, Str::slug($one_menu_element->name).'/restoreMenu')}}">
                            </td>
                            @if($groupSubRelations->del_from_rec == 1)
                                <td class="medium check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$one_menu_element->menu_id}}]"
                                           value="{{$one_menu_element->menu_id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($one_menu_element->name).'/destroyMenuFromCart')}}">
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
