@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
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

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body users-rights">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savelist') }}" id="add-form"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="title" name="name">
                        </div>
                    </div>
                    @foreach($menu as $val)
                        @if(auth()->user()->root == 1)
                            <div class="checkboxes-list">
                                <div class="fields-row">
                                    <div class="label-wrap">
                                        <label for="modules_id-{{$val->modules_id}}-">{{$val->name ?? '' }}</label>
                                    </div>
                                    <div class="input-wrap">
                                        <input type="checkbox" class="modules-id" id="modules_id-{{$val->modules_id}}-" name="modules_id[{{$val->modules_id}}]" value="{{$val->modules_id}}" data-module-id="{{$val->modules_id}}">
                                    </div>
                                </div>
                                <div class="hidden children-rights" id="taction-{{$val->modules_id}}-">
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="new-{{$val->modules_id}}-">{{trans('variables.create_new_rights')}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" id="new-{{$val->modules_id}}-" name="new[{{$val->modules_id}}]">
                                        </div>
                                    </div>
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="save-{{$val->modules_id}}-">{{trans('variables.save_rights')}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" id="save-{{$val->modules_id}}-" name="save[{{$val->modules_id}}]">
                                        </div>
                                    </div>
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="active-{{$val->modules_id}}-">{{trans('variables.active_rights')}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" id="active-{{$val->modules_id}}-" name="active[{{$val->modules_id}}]">
                                        </div>
                                    </div>
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="del_to_rec-{{$val->modules_id}}-">{{trans('variables.del_to_rec_rights')}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" id="del_to_rec-{{$val->modules_id}}-" name="del_to_rec[{{$val->modules_id}}]">
                                        </div>
                                    </div>
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="del_from_rec-{{$val->modules_id}}-">{{trans('variables.del_from_rec_rights')}}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" id="del_from_rec-{{$val->modules_id}}-" name="del_from_rec[{{$val->modules_id}}]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if($val->modulesId->alias != 'modules-constructor')
                                <div class="checkboxes-list">
                                    <div class="fields-row">
                                        <div class="label-wrap">
                                            <label for="modules_id-{{$val->modules_id}}-">{{$val->name ?? '' }}</label>
                                        </div>
                                        <div class="input-wrap">
                                            <input type="checkbox" class="modules-id" id="modules_id-{{$val->modules_id}}-" name="modules_id[{{$val->modules_id}}]" value="{{$val->modules_id}}" data-module-id="{{$val->modules_id}}">
                                        </div>
                                    </div>
                                    <div class="hidden children-rights" id="taction-{{$val->modules_id}}-">
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="new-{{$val->modules_id}}-">{{trans('variables.create_new_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="new-{{$val->modules_id}}-" name="new[{{$val->modules_id}}]">
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="save-{{$val->modules_id}}-">{{trans('variables.save_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="save-{{$val->modules_id}}-" name="save[{{$val->modules_id}}]">
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="active-{{$val->modules_id}}-">{{trans('variables.active_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="active-{{$val->modules_id}}-" name="active[{{$val->modules_id}}]">
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="del_to_rec-{{$val->modules_id}}-">{{trans('variables.del_to_rec_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="del_to_rec-{{$val->modules_id}}-" name="del_to_rec[{{$val->modules_id}}]">
                                            </div>
                                        </div>
                                        <div class="fields-row">
                                            <div class="label-wrap">
                                                <label for="del_from_rec-{{$val->modules_id}}-">{{trans('variables.del_from_rec_rights')}}</label>
                                            </div>
                                            <div class="input-wrap">
                                                <input type="checkbox" id="del_from_rec-{{$val->modules_id}}-" name="del_from_rec[{{$val->modules_id}}]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="add-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop