@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBanner/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($banner_top_without_lang->name).'/edititem/'.$banner_top_without_lang->banner_top_id.'/'.$lang_id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($banner_top_without_lang->name).'/edititem/'.$banner_top_without_lang->banner_top_id.'/'.$lang_id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$banner_top->name ?? '' }}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST"
                      action="{{ urlForLanguage($lang, 'save/'.$banner_top_without_lang->banner_top_id.'/'.$lang_id) }}"
                      id="edit-form" enctype="multipart/form-data" data-parent-url="{{$url_for_active_elem}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="lang">{{trans('variables.lang')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="lang" id="lang" class="select2">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <option value="{{$one_lang->id}}" {{$one_lang->id == $lang_to_edit ? 'selected' : ''}}>{{$one_lang->descr}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$banner_top->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$banner_top->body ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $banner_top, 'element_by_lang' => $banner_top_without_lang])
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="link">{{trans('variables.link')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="link" id="link" value="{{$banner_top->link ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="active">{{trans('variables.active_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="active"
                                   id="active" {{$banner_top_id->active == 1 ? 'checked' : ''}}>
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
