@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'create_user/createitem'),
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
            @if(!empty($users))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.id_table')}}</th>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.email_text')}}</th>
                        <th>{{trans('variables.phone')}}</th>
                        <th>Процент скидки</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                        <tr id="{{$user->id}}">
                            <td class="small">
                                <span>{{$user->id}}</span>
                            </td>
                            <td>
                                <span>{{$user->name ?? trans('variables.another_name')}}</span>
                            </td>
                            <td>
                                <span>{{$user->email}}</span>
                            </td>
                            <td>
                                <span>{{$user->phone ?? '---------'}}</span>
                            </td>
                            <td>
                                <span>{{$user->discount}}</span>
                            </td>
                            <td class="link-block">
                                <a href="{{urlForFunctionLanguage($lang, $user->id.'/editUser/'.$user->id.'/'.$lang_id)}}">{{trans('variables.edit_table')}}</a>
                            </td>
                            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$user->id}}]" value="{{$user->id}}"
                                           url="{{urlForFunctionLanguage($lang, $user->id.'/destroyFrontUser')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    {{--<tr>--}}
                    {{--<td colspan="10">--}}

                    {{--@include('admin.pagination', ['paginator' => $labels_list_id])--}}
                    {{--</td>--}}
                    {{--</tr>--}}
                    </tfoot>
                </table>
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>
@stop