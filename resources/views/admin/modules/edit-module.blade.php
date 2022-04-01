@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => ($modules_id->level == 1 ? urlForFunctionLanguage($lang, '') : urlForFunctionLanguage($lang, GetParentAlias($modules_without_lang->modules_id, 'modules_id').'/memberslist')),
                    trans('variables.add_element') => ($modules_id->level == 1 ? urlForFunctionLanguage($lang, 'createModules/createmodules') : urlForFunctionLanguage($lang, GetParentAlias($modules_without_lang->modules_id, 'modules_id').'/createmodules')),
                    trans('variables.elements_basket') => ($modules_id->level == 1 ? urlForFunctionLanguage($lang, 'modulesCart/modulescart') : urlForFunctionLanguage($lang, GetParentAlias($modules_without_lang->modules_id, 'modules_id').'/modulescart')),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $modules_id->alias . '/editmodules/'.$modules_without_lang->modules_id.'/'.$lang_id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => ($modules_id->level == 1 ? urlForFunctionLanguage($lang, '') : urlForFunctionLanguage($lang, GetParentAlias($modules_without_lang->modules_id, 'modules_id').'/memberslist')),
                    trans('variables.elements_basket') => ($modules_id->level == 1 ? urlForFunctionLanguage($lang, 'modulesCart/modulescart') : urlForFunctionLanguage($lang, GetParentAlias($modules_without_lang->modules_id, 'modules_id').'/modulescart')),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $modules_id->alias . '/editmodules/'.$modules_without_lang->modules_id.'/'.$lang_id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$modules_elems->name ?? '' }}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savemodules/'.$modules_without_lang->modules_id.'/'.$lang_id) }}" id="edit-form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang" class="select2">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$modules_elems->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$modules_id->alias ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p_id">{{trans('variables.p_id_name')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="p_id" id="p_id" class="select2">
                                <option value="0" {{ !is_null($modules_id) ? (($modules_id->p_id == 0) ? 'selected' : '') : ''}} >{{trans('variables.home')}}</option>
                                {!! SelectModulesTree($lang_id, 0, $modules_id->p_id) !!}
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="controller">{{trans('variables.controller')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="controller" id="controller" value="{{$modules_id->controller ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="view_folder">{{trans('variables.view_folder')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="view_folder" id="view_folder" value="{{$modules_id->view ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="models" class="new_input_label">{{trans('variables.models')}}</label>
                        </div>
                        <div class="input-wrap">
                            <div class="new_input" data-url="{{$url_for_active_elem}}" data-msg-err="{{trans('variables.min_one_input')}}">
                                @if(!empty($modules_id->models))
                                    @foreach(explode(';', $modules_id->models) as $key => $one_module)
                                        <div class="clone_div" data-elem-id="{{$modules_id->id}}">
                                            <input name="models[]" id="models{{$key}}" data-position-el="{{$key}}" value="{{$one_module ?? '' }}">
                                            <span class="destroy_cloned_input destroy_cloned_element"></span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="clone_div" data-elem-id="{{$modules_id->id}}">
                                        <input name="models[]" id="models">
                                        <span class="destroy_cloned_input destroy_cloned_element"></span>
                                    </div>
                                @endif
                                <div class="button-new-input">
                                    <button type="button" class="btn">{{trans('variables.new_element')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$modules_elems->body ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="root">{{trans('variables.for_root_module')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="root" id="root" {{$modules_id->root == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop
