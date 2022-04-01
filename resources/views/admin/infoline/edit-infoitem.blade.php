@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_element') => urlForLanguage($lang, 'createinfoitem'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $info_line_id->alias.'/editinfoitem/'.$info_item_without_lang->info_item_id.'/'.$lang_id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $info_line_id->alias.'/editinfoitem/'.$info_item_without_lang->info_item_id.'/'.$lang_id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'saveinfoitem/'.$info_item_without_lang->info_item_id.'/'.$lang_id) }}" id="edit-form" data-parent-url="{{$url_for_active_elem}}" enctype="multipart/form-data">
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
                            <input name="name" id="name" value="{{$info_item->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$info_item_id->alias ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="datepicker">{{trans('variables.date_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="date-time" class="datetimepicker" name="add_date"
                                   value="{{!empty($info_item_id->add_date) ? date('d-m-Y', strtotime($info_item_id->add_date)) : ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="descr">{{trans('variables.short_description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea id="descr" name="descr" rows="10">{{$info_item->descr ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$info_item->body ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="is_public">{{trans('variables.published_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" id="is_public" name="is_public" {{$info_item_id->is_public == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="show_img">{{trans('variables.show_img')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" id="show_img" name="show_img" {{$info_item_id->show_img == 1 ? 'checked' : ''}}>
                        </div>
                    </div>--}}
                    <div class="fields-row">
                        @include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $info_item_id, 'element_by_lang' => ''])
                    </div>
                    @if(!empty($category_list))
                        <div class="fields-row">
                            <div class="label-wrap">
                                <label for="category"></label>
                            </div>
                            <div class="input-wrap">
                                <select name="category" id="category" class="select2">
                                    @foreach($category_list as $one_category)
                                        <option value="{{$one_category}}" {{$info_item_id->category == $one_category ? 'selected' : ''}}>{{$one_category}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="author">{{trans('variables.author')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="author" id="author" value="{{$info_item->author ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="title">{{trans('variables.general_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="title" id="title" value="{{$info_item->page_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="h1_title">{{trans('variables.h1_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="h1_title" id="h1_title" value="{{$info_item->h1_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title" value="{{$info_item->meta_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords" value="{{$info_item->meta_keywords ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description" value="{{$info_item->meta_description ?? '' }}">
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
