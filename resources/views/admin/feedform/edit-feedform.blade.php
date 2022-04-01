@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @include('admin.list-elements', [
            'actions' => [
                trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($feedform->name).'/edititem/'.$feedform->id)
            ]
        ])

        <div class="form-page">
            <div class="list-table">
                <div class="table-title">
                    <span>{{trans('variables.edit_element')}} "{{$feedform->name ?? '' }}"</span>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Тип</th>
                        @switch($feedform->type_form)

                            @case('ask')

                            <th>{{trans('variables.subject_text')}}</th>
                            <th>{{trans('variables.comment_table')}}</th>
                            <th>{{trans('variables.email_text')}}</th>
                            @break
                            @case('ordercall')
                            <th>{{trans('variables.name_text')}}</th>
                            <th>{{trans('variables.phone')}}</th>
                        @break

                            @case('review')
                            <td><span>{{trans('variables.name_text')}}</span></td>
                            <th>{{trans('variables.comment_table')}}</th>
                            <th>{{trans('variables.email_text')}}</th>
                            @break
                            @case('error')
                            <th>{{trans('variables.comment_table')}}</th>
                            @break

                            @case('fromcontacts')
                            <th>{{trans('variables.comment_table')}}</th>
                            <th>{{trans('variables.email_text')}}</th>
                            @break

                        @endswitch


                      {{--  <th>{{trans('variables.name_text')}}</th>
                        <th>{{trans('variables.phone')}}</th>
                        <th>{{trans('variables.comment_table')}}</th>
                        <th>{{trans('variables.subject_text')}}</th>
                        --}}{{--<th>{{trans('variables.agree_text')}}</th>--}}{{--
                        <th>{{trans('variables.email_text')}}</th>--}}
                        <th>{{trans('variables.date_table')}}</th>
                        <th>{{trans('variables.user_ip')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <span>{{$feedform->type_form ?? '' }}</span>
                        </td>
                        @switch($feedform->type_form)

                            @case('ask')

                            <td>
                                <span>{{$feedform->subject ?? '' }}</span>
                            </td>
                            <td>
                                <span>{{$feedform->comment ?? '' }}</span>
                            </td>

                            <td>
                                <span>{{$feedform->email ?? '' }}</span>
                            </td>
                            @break
                            @case('fromcontacts')
                            <td>
                                <span>{{$feedform->comment ?? '' }}</span>
                            </td>
                            <td>
                                <span>{{$feedform->email ?? '' }}</span>
                            </td>
                            @break
                            @case('ordercall')
                            <td>
                                <span>{{$feedform->name ?? '' }}</span>
                            </td>

                            <td>
                                <span>{{$feedform->phone ?? '' }}</span>
                            </td>
                            @break
                            @case('review')
                            <td>
                                <span>{{$feedform->name ?? '' }}</span>
                            </td>

                            <td>
                                <span>{{$feedform->comment ?? '' }}</span>
                            </td>
                            <td>
                                <span>{{$feedform->email ?? '' }}</span>
                            </td>
                            @break
                            @case('error')
                            <td>
                                <span>{{$feedform->comment ?? '' }}</span>
                            </td>
                            @break

                        @endswitch


                      {{--  <td>
                            <span>{{$feedform->name ?? '' }}</span>
                        </td>
                        <td>
                            <span>{{$feedform->phone ?? '' }}</span>
                        </td>
                        <td>
                            <span>{{$feedform->comment ?? '' }}</span>
                        </td>
                        <td>
                            <span>{{$feedform->subject ?? '' }}</span>
                        </td>
                        --}}{{--<td>
                            <span>{{$feedform->agree==1? ShowLabelById(23, $lang_id):'-' }}</span>
                        </td>--}}{{--
                        <td>
                            <span>{{$feedform->email ?? '' }}</span>
                        </td>--}}
                        <td>
                            <span>{{$feedform->created_at ?? '' }}</span>
                        </td>
                        <td>
                            <span>{{$feedform->ip ?? '' }}</span>
                        </td>
                        @if($groupSubRelations->active == 1)
                            <td class="small active-link">
                                <a href="" class="change-active{{$feedform->active == 1 ? ' active' : ''}}"
                                   data-active="{{$feedform->active}}" element-id="{{$feedform->id}}"
                                   action="subject"
                                   url="{{$url_for_active_elem}}"></a>
                            </td>
                        @endif
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="10"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            {{--<div class="form-head">--}}
            {{--<span>{{trans('variables.edit_element')}} "{{$feedform->name ?? '' }}"</span>--}}
            {{--</div>--}}
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'save/'.$feedform->id) }}"
                      id="edit-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.comment_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="comment" id="body" rows="10">{!! $feedform->comment ?? ''  !!}</textarea>
                        </div>
                    </div>
                    {{--<div class="fields-row">--}}
                    {{--<div class="label-wrap">--}}
                    {{--<label for="active">{{trans('variables.active_table')}}</label>--}}
                    {{--</div>--}}
                    {{--<div class="input-wrap">--}}
                    {{--<input type="checkbox" name="active"--}}
                    {{--id="active" {{$feedform->active == 1 ? 'checked' : ''}}>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>

        </div>
    </div>

@stop
