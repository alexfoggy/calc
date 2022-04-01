@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBrand/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'brandsCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($brand_without_lang->name).'/edititem/'.$brand_id->id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'brandsCart/cartitems'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($brand_without_lang->name).'/edititem/'.$brand_id->id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}}
                    "{{ $brand_elems->name ?? ''}}"
                </span>
            </div>
            <div class="form-body">
                <form class="form" method="POST"
                      action="{{ urlForLanguage($lang, 'save/'.$brand_without_lang->goods_brand_id) }}" id="edit-form"
                      enctype="multipart/form-data" data-parent-url="{{$url_for_active_elem}}">
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
                            <input name="name" id="name" value="{{$brand_elems->name ?? '' }}">
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$brand_id->alias ?? '' }}">
                        </div>
                    </div>

                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $brand_id, 'element_by_lang' => ''])
                    </div>

                    <div class="fields-row ckeditor hide">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$brand_elems->body ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="link">{{trans('variables.link')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="link" id="link" value="{{$brand_elems->link ?? ''}}">
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title" value="{{$brand_elems->meta_title ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords" value="{{$brand_elems->meta_keywords ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description" value="{{$brand_elems->meta_description ?? ''}}">
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