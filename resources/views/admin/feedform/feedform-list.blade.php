@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @include('admin.list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
            ]
        ])

        <div class="list-table">
            @if(!$feedform->isEmpty())
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.name_text')}}</th>
                        <th>{{trans('variables.phone')}}</th>
                        {{--<th>{{trans('variables.subject_text')}}</th>--}}
                        {{--<th>{{trans('variables.agree_text')}}</th>--}}
                        <th>{{trans('variables.comment_table')}}</th>
                        <th>{{trans('variables.email_text')}}</th>
                        <th>{{trans('variables.date_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($feedform as $one_feedform)
                        <tr id="{{$one_feedform->id}}">
                            <td>
                                <span>{{$one_feedform->name ?? ''}}</span>
                            </td>
                            <td>
                                <span>{{$one_feedform->phone ?? ''}}</span>
                            </td>
                            {{--<td>
                                <span>{{$one_feedform->subject ?? '' }}</span>
                            </td>--}}
                            <td class="medium">
                                <span>{{!empty($one_feedform->comment) ? strPosText($one_feedform->comment, 200) : ''}}</span>
                            </td>
                            <td>
                                <span>{{$one_feedform->email ?? '' }}</span>
                            </td>
                            <td>
                                <span>{{$one_feedform->created_at ?? '' }}</span>
                            </td>
                            <td class="link-block">
                                <a href="{{urlForFunctionLanguage($lang, Str::slug($one_feedform->name).'/edititem/'.$one_feedform->id)}}">{{trans('variables.edit_table')}}</a>
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href="" class="change-active{{$one_feedform->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_feedform->active}}" element-id="{{$one_feedform->id}}"
                                       action="subject"
                                       url="{{$url_for_active_elem}}"></a>
                                </td>
                            @endif
                            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$one_feedform->id}}]" value="{{$one_feedform->id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($one_feedform->name).'/destroyFeedformFromCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $feedform])
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
