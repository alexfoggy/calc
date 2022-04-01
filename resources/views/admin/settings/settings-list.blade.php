@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createSetting/createitem'),
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                ]
            ])
        @endif


        <div class="list-table">
            @if(!empty($settings_list))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.alias_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($settings_list as $key => $setting)
                        <tr id="{{$setting->settings_id}}">
                            <td class="big">
                                <span>{{ !empty(IfHasName($setting->settings_id, $lang_id, 'settings')) ? IfHasName($setting->settings_id, $lang_id, 'settings') : trans('variables.another_name')}}</span>
                            </td>
                            <td class="alias-block">
                                <span>{{$setting->settingsId->alias}}</span>
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($setting->name).'/edititem/'.$setting->settings_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($setting->settings_id, $one_lang->id, 'settings')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->del_to_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$setting->settings_id}}]"
                                           value="{{$setting->settings_id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($setting->name).'/destroySettingFromCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $settings_list_id])
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

