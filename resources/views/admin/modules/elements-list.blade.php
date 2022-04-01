@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createModules/createmodules'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'modulesCart/modulescart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'modulesCart/modulescart')
                ]
            ])
        @endif

        <div class="list-table">
            @if(!empty($module_elements))
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
                    @foreach($module_elements as $key => $one_module_element)
                        <tr id="{{$one_module_element->modules_id}}">
                            @if(!IfHasChildModulesList($one_module_element->modulesId->id, 'modules')->isEmpty())
                                <td>
                                    <a href="{{urlForFunctionLanguage($lang, $one_module_element->modulesId->alias.'/memberslist')}}">{{!empty(IfHasName($one_module_element->modules_id, $lang_id, 'modules')) ? IfHasName($one_module_element->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}</a>
                                </td>
                            @else
                                <td>
                                    <span>{{ !empty(IfHasName($one_module_element->modules_id, $lang_id, 'modules')) ? IfHasName($one_module_element->modules_id, $lang_id, 'modules') : trans('variables.another_name')}}</span>
                                </td>
                            @endif
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, $one_module_element->modulesId->alias.'/editmodules/'.$one_module_element->modules_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_module_element->modules_id, $one_lang->id, 'modules')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$one_module_element->modulesId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_module_element->modulesId->active}}"
                                       element-id="{{$one_module_element->modulesId->id}}"></a>
                                </td>
                            @endif
                            <td class="dragHandle" nowrap=""></td>
                            @if($groupSubRelations->del_to_rec == 1)
                                @if(IfHasChildModulesList($one_module_element->modulesId->id, 'modules')->isEmpty() && $one_module_element->modulesId->root == 0)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_module_element->modules_id}}]"
                                               value="{{$one_module_element->modules_id}}"
                                               url="{{urlForFunctionLanguage($lang, Str::slug($one_module_element->name).$one_module_element->modules_id.'/destroyModulesToCart')}}">
                                    </td>
                                @elseif($one_module_element->modulesId->root == 1)
                                    <td>
                                        <span>{{trans('variables.cant_delete_module')}}</span>
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