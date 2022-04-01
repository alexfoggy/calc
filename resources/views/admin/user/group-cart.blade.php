@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createGroup/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems')
                ]
            ])
        @endif

        <div class="list-table">
            @if(!$deleted_group->isEmpty())
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
                    @foreach($deleted_group as $group)
                        <tr id="{{$group->id}}">
                            <td>
                                <span>{{$group->name ?? '' }}</span>
                            </td>
                            <td class="medium check-restore-element">
                                <input type="checkbox" class="restore-all-elements" name="restore_elements[{{$group->id}}]" value="{{$group->id}}" url="{{urlForFunctionLanguage($lang, Str::slug($group->name).'/restore')}}">
                            </td>
                            @if($groupSubRelations->del_from_rec == 1)
                                <td class="medium check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements" name="destroy_elements[{{$group->id}}]" value="{{$group->id}}" url="{{urlForFunctionLanguage($lang, Str::slug($group->name).'/destroyGroupFromCart')}}">
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