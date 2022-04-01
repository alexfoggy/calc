@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGallerySubject/creategallerysubject'),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart'),
                        trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($gallery_without_lang->name).'/editgallerysubject/'.$gallery_without_lang->gallery_subject_id.'/'.$lang_id)
                    ]
                ])
            @else
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                        trans('variables.add_subject') => urlForLanguage($lang, 'creategallerysubject'),
                        trans('variables.elements_basket') => urlForLanguage($lang, 'gallerysubjectcart'),
                        trans('variables.edit_element') => urlForLanguage($lang, 'editgallerysubject/'.$gallery_without_lang->gallery_subject_id.'/'.$lang_id)
                    ]
                ])
            @endif
        @else
            @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject')
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                        trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart'),
                        trans('variables.edit_element') => urlForFunctionLanguage($lang, Str::slug($gallery_without_lang->name).'/editgallerysubject/'.$gallery_without_lang->gallery_subject_id.'/'.$lang_id)
                    ]
                ])
            @else
                @include('admin.list-elements', [
                    'actions' => [
                        trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                        trans('variables.elements_basket') => urlForLanguage($lang, 'gallerysubjectcart'),
                        trans('variables.edit_element') => urlForLanguage($lang, 'editgallerysubject/'.$gallery_without_lang->gallery_subject_id.'/'.$lang_id)
                    ]
                ])
            @endif
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$gallery_elems->name ?? '' }}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'savesubject/'.$gallery_without_lang->gallery_subject_id.'/'.$lang_id) }}" id="edit-form" enctype="multipart/form-data">
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
                            <input name="name" id="name" value="{{$gallery_elems->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$gallery_subject_id->alias ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p_id">{{trans('variables.p_id_name')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="p_id" id="p_id" class="select2">
                                <option value="0" {{ !is_null($gallery_subject_id) ? (($gallery_subject_id->p_id == 0) ? 'selected' : '') : ''}}>{{trans('variables.home')}}</option>
                                {!! SelectGallerySubjectTree($lang_id, 0 ,$gallery_subject_id->p_id) !!}
                            </select>
                        </div>
                    </div>
                    {{--Картинка--}}
                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $gallery_subject_id, 'element_by_lang' => 'lang'])
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$gallery_elems->body ?? '' }}</textarea>
                        </div>
                    </div>
                    @if(request()->segment(4) == 'catalog')
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="height">Высота</label>
                        </div>
                        <div class="input-wrap">
                            <input name="height" id="height" value="{{$gallery_subject_id->height ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="width">Ширина</label>
                        </div>
                        <div class="input-wrap">
                            <input name="width" id="width" value="{{$gallery_subject_id->width ?? '' }}">
                        </div>
                    </div>
                    @endif
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="page_title">{{trans('variables.general_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="page_title" id="page_title" value="{{$gallery_elems->page_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="h1_title" id="h1_title" value="{{$gallery_elems->h1_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title" value="{{$gallery_elems->meta_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords" value="{{$gallery_elems->meta_keywords ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description" value="{{$gallery_elems->meta_description ?? '' }}">
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
