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
            @if(!$user_group->isEmpty())
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    
                    @foreach($user_group as $group)
                        @if($group->alias != Str::slug(config()->get('params.__key') . config()->get('params.__token')))
                            <tr id="{{$group->id}}">
                                <td>
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($group->name).'/memberslist')}}">{{$group->name}}</a>
                                </td>
                                <td class="link-block">
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($group->name).'/editlist/'.$group->id)}}">{{trans('variables.edit_table')}}</a>
                                </td>
                                @if($groupSubRelations->del_to_rec == 1)
                                    @if(!CheckIfGroupHasUsers($group->id))
                                        <td class="check-destroy-element">
                                            <input type="checkbox" class="remove-all-elements"
                                                   name="destroy_elements[{{$group->id}}]" value="{{$group->id}}"
                                                   url="{{urlForFunctionLanguage($lang, Str::slug($group->name).'/destroyGroupToCart')}}">
                                        </td>
                                    @else
                                        <td>{{trans('variables.delete_inner_modules')}}</td>
                                    @endif
                                @endif
                            </tr>
                        @endif
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $user_group])
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