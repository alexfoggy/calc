@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createCity/createitem'),
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
            @if(!empty($city_list))
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
                                <th>{{trans('variables.delete_table')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($city_list as $city)
                            <tr id="{{$city->city_id}}">
                                <td class="big">
                                    <span>{{ !empty(IfHasName($city->city_id, $lang_id, 'city')) ? IfHasName($city->city_id, $lang_id, 'city') : trans('variables.another_name')}}</span>
                                </td>
                                <td class="edit-links">
                                    @foreach($lang_list as $lang_key => $one_lang)
                                        <a href="{{urlForFunctionLanguage($lang, Str::slug($city->name).'/edititem/'.$city->city_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($city->city_id, $one_lang->id, 'city')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                    @endforeach
                                </td>
                                @if($groupSubRelations->active == 1)
                                    <td class="small active-link">
                                        <a href=""
                                                class="change-active {{$city->cityId->active == 1 ? 'active' : ''}}"
                                                data-active="{{$city->cityId->active}}"
                                                element-id="{{$city->city_id}}"></a>
                                    </td>
                                @endif
                                <td class="dragHandle" nowrap=""></td>
                                @if($groupSubRelations->del_to_rec == 1)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                                name="destroy_elements[{{$city->city_id}}]"
                                                value="{{$city->city_id}}"
                                                url="{{urlForFunctionLanguage($lang, Str::slug($city->name).'/destroyCityFromCart')}}">
                                    </td>
                                @endif
                            </tr>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                {{--@include('admin.pagination', ['paginator' => $city_list])--}}
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