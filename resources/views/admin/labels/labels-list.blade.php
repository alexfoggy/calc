@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createLabel/createitem'),
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
            @if(!empty($labels_list))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.id_table')}}</th>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($labels_list as $key => $label)
                        <tr id="{{$label->labels_id}}">
                            <td class="small">
                                <span>{{$label->labels_id}}</span>
                            </td>
                            <td>
                                <span>{{ !empty(IfHasName($label->labels_id, $lang_id, 'labels')) ? IfHasName($label->labels_id, $lang_id, 'labels') : trans('variables.another_name')}}</span>
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($label->name).'/edititem/'.$label->labels_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($label->labels_id, $one_lang->id, 'labels')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements" name="destroy_elements[{{$label->labels_id}}]" value="{{$label->labels_id}}" url="{{urlForFunctionLanguage($lang, Str::slug($label->name).'/destroyLabelFromCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $labels_list_id])
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