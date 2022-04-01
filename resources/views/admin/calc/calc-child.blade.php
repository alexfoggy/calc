@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    'Создать калькулятор' => urlForFunctionLanguage($lang, request()->segment(4).'/createItem'),
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
            @if(!empty($calc_list))
                <table class="table" id="tablelistsorter" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        {{--                        <th>{{trans('variables.position_table')}}</th>--}}
                        @if($groupSubRelations->del_to_rec == 1)
                            <th>{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calc_list as $calc)
                        <tr id="{{$calc->id}}">
                            <td class="big">
{{--                                <a href="{{urlForFunctionLanguage($lang, Str::slug($calc->alias).'/editCalc')}}">--}}
                                    <span>{{ !empty(IfHasName($calc->id, $lang_id, 'calc')) ? IfHasName($calc->id, $lang_id, 'calc') : trans('variables.another_name')}}</span>
{{--                                </a>--}}
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($calc->alias).'/editCalc/'.$calc->id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($calc->id, $one_lang->id, 'calc')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active {{$calc->active == 1 ? 'active' : ''}}"
                                       data-active="{{$calc->active}}"
                                       element-id="{{$calc->id}}"></a>
                                </td>
                            @endif
                            {{--                            <td class="dragHandle" nowrap=""></td>--}}
                            @if($groupSubRelations->del_to_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$calc->id}}]"
                                           value="{{$calc->id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($calc->alias).'/destroyCalcId')}}">
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