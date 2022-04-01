@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @if(request()->segment(5) == '' || request()->segment(4) == 'createModules')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.add_element') => urlForFunctionLanguage($lang, 'createModules/createmodules'),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'modulesCart/modulescart')
                    ]
                ])
            @else
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                        trans('variables.add_element') => urlForLanguage($lang, 'createmodules'),
                        trans('variables.elements_basket') => urlForLanguage($lang, 'modulescart')
                    ]
                ])
            @endif
        @else
            @if(request()->segment(5) == '' || request()->segment(4) == 'createModules')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'modulesCart/modulescart')
                    ]
                ])
            @else
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                        trans('variables.elements_basket') => urlForLanguage($lang, 'modulescart')
                    ]
                ])
            @endif
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savemodules') }}" id="add-form"
                      enctype="multipart/form-data">
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
                            <input name="name" id="name">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p_id">{{trans('variables.p_id_name')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="p_id" id="p_id" class="select2">
                                <option value="0">{{trans('variables.home')}}</option>
                                {!! SelectModulesTree($lang_id, 0, $curr_page_id) !!}
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="controller">{{trans('variables.controller')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="controller" id="controller">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="view_folder">{{trans('variables.view_folder')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="view_folder" id="view_folder">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="models" class="new_input_label">{{trans('variables.models')}}</label>
                        </div>
                        <div class="input-wrap">
                            <div class="new_input">
                                <div class="clone_div">
                                    <input name="models[]" id="models">
                                </div>
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
                            <textarea name="body" id="body" data-type="ckeditor"></textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="root">{{trans('variables.for_root_module')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="root" id="root">
                        </div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="add-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop