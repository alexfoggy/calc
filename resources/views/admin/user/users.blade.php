@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_element') => urlForLanguage($lang, 'createuser'),
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                ]
            ])
        @endif


        <div class="list-table">
            @if(!$user->isEmpty())
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user as $usr)
                        <tr id="{{$usr->id}}">
                            <td>
                                <span>{{$usr->name}}</span>
                            </td>
                            <td class="link-block">
                                <a href="{{urlForLanguage($lang, 'edituser/'.$usr->id)}}">{{trans('variables.edit_table')}}</a>
                            </td>
                            @if($groupSubRelations->del_to_rec == 1 || $groupSubRelations->del_from_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$usr->id}}]" value="{{$usr->id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($usr->name).'/destroyUserFromCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $user])
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