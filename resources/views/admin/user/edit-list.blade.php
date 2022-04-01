@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createGroup/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($group->name).'/editlist/'.$group->id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'groupCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($group->name).'/editlist/'.$group->id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$group->name ?? '' }}"</span>
            </div>
            <div class="form-body users-rights">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savelist/'.$group->id) }}" id="edit-form"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="title" name="name" value="{{$group->name}}">
                        </div>
                    </div>
                    @foreach($result as $key => $userRoles)
                        @if(auth()->user()->root == 1)
                            <div class="checkboxes-list">
                                <div class="fields-row">
                                    <div class="label-wrap">
                                        <label for="modules_id-{{$userRoles['modules']->modules_id}}-">{{$userRoles['name']}}</label>
                                    </div>
                                    <div class="input-wrap">
                                        <input type="checkbox" class="modules-id" id="modules_id-{{$userRoles['modules']->modules_id}}-" name="modules_id[{{$userRoles['modules']->modules_id}}]" value="{{$userRoles['modules']->modules_id}}" data-module-id="{{$userRoles['modules']->modules_id}}" {{in_array($userRoles['modules']->modules_id, $arr) ? 'checked' : ''}}>
                                    </div>
                                </div>
                                @foreach($userRoles['roles'] as $manyRoles)
                                    <div class="{{in_array($userRoles['modules']->modules_id, $arr) ? '' : 'hidden' }} children-rights" id="taction-{{$userRoles['modules']->modules_id}}-">
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="new-{{$userRoles['modules']->modules_id}}-">{{trans('variables.create_new_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="new-{{$userRoles['modules']->modules_id}}-" name="new[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $new) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="save-{{$userRoles['modules']->modules_id}}-">{{trans('variables.save_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="save-{{$userRoles['modules']->modules_id}}-" name="save[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $save) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="active-{{$userRoles['modules']->modules_id}}-">{{trans('variables.active_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="active-{{$userRoles['modules']->modules_id}}-" name="active[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $active) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="del_to_rec-{{$userRoles['modules']->modules_id}}-">{{trans('variables.del_to_rec_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="del_to_rec-{{$userRoles['modules']->modules_id}}-" name="del_to_rec[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $del_to_rec) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="del_from_rec-{{$userRoles['modules']->modules_id}}-">{{trans('variables.del_from_rec_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="del_from_rec-{{$userRoles['modules']->modules_id}}-" name="del_from_rec[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $del_from_rec) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @endforeach
                            </div>
                        @else
                            @if($userRoles['modules']->modulesId->alias != 'modules-constructor')
                                <div class="checkboxes-list">
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="modules_id-{{$userRoles['modules']->modules_id}}-">{{$userRoles['name']}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" class="modules-id" id="modules_id-{{$userRoles['modules']->modules_id}}-" name="modules_id[{{$userRoles['modules']->modules_id}}]" value="{{$userRoles['modules']->modules_id}}" data-module-id="{{$userRoles['modules']->modules_id}}" {{in_array($userRoles['modules']->modules_id, $arr) ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                    @foreach($userRoles['roles'] as $manyRoles)
                                        <div class="{{in_array($userRoles['modules']->modules_id, $arr) ? '' : 'hidden' }} children-rights" id="taction-{{$userRoles['modules']->modules_id}}-">
                                            <div class="fields-row">
                                                <div class="label-wrap">
                                                    <label for="new-{{$userRoles['modules']->modules_id}}-">{{trans('variables.create_new_rights')}}</label>
                                                </div>
                                                <div class="input-wrap">
                                                    <input type="checkbox" id="new-{{$userRoles['modules']->modules_id}}-" name="new[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $new) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="fields-row">
                                                <div class="label-wrap">
                                                    <label for="save-{{$userRoles['modules']->modules_id}}-">{{trans('variables.save_rights')}}</label>
                                                </div>
                                                <div class="input-wrap">
                                                    <input type="checkbox" id="save-{{$userRoles['modules']->modules_id}}-" name="save[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $save) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="fields-row">
                                                <div class="label-wrap">
                                                    <label for="active-{{$userRoles['modules']->modules_id}}-">{{trans('variables.active_rights')}}</label>
                                                </div>
                                                <div class="input-wrap">
                                                    <input type="checkbox" id="active-{{$userRoles['modules']->modules_id}}-" name="active[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $active) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="fields-row">
                                                <div class="label-wrap">
                                                    <label for="del_to_rec-{{$userRoles['modules']->modules_id}}-">{{trans('variables.del_to_rec_rights')}}</label>
                                                </div>
                                                <div class="input-wrap">
                                                    <input type="checkbox" id="del_to_rec-{{$userRoles['modules']->modules_id}}-" name="del_to_rec[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $del_to_rec) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="fields-row">
                                                <div class="label-wrap">
                                                    <label for="del_from_rec-{{$userRoles['modules']->modules_id}}-">{{trans('variables.del_from_rec_rights')}}</label>
                                                </div>
                                                <div class="input-wrap">
                                                    <input type="checkbox" id="del_from_rec-{{$userRoles['modules']->modules_id}}-" name="del_from_rec[{{$userRoles['modules']->modules_id}}]" {{in_array('1'.$userRoles['modules']->modules_id, $del_from_rec) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        @break
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    @endforeach
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop