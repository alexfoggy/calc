@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_video') => urlForLanguage($lang, 'itemsvideo'),
                    trans('variables.add_photo') => urlForLanguage($lang, 'itemsphoto'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'galleryitemcart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'galleryitemcart')
                ]
            ])
        @endif


        <div class="form-page">
            <div class="form-head">
                <span>{{trans('variables.add_video')}} " {{$gallery_subject->name ?? '' }} "</span>
            </div>
            <div class="form-body add-video-form">
                <form action="{{ urlForLanguage($lang, 'createitemsvideo') }}" method="POST" id="add-video-form"
                      enctype="multipart/form-data" class="form">
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
                            <label for="name">{{trans('variables.name_text')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.body')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="youtube_link">{{trans('variables.youtube_id')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="youtube_link" id="youtube_link" url="{{$url_for_active_elem}}" value="">
                            <div class="youtube_id"></div>
                        </div>
                    </div>
                    <button class="btn" onclick="saveForm(this)" data-form-id="add-video-form">{{trans('variables.save_rights')}}</button>
                </form>
            </div>
            <div class="list-table">
                @if(!empty($gallery_item))
                    <table class="table" id="tablelistsorter" action="item" url="{{$url_for_active_elem}}"
                           empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                        <tr>
                            <th>{{trans('variables.photo')}}</th>
                            <th>{{trans('variables.title_table')}}</th>
                            <th>{{trans('variables.edit_element')}}</th>
                            @if($groupSubRelations->active == 1)
                                <th>{{trans('variables.active_table')}}</th>
                                <th>{{trans('variables.show_on_main')}}</th>
                            @endif
                            <th>{{trans('variables.position_table')}}</th>
                            @if($groupSubRelations->del_from_rec == 1)
                                <th class="remove-all">{{trans('variables.delete_table')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gallery_item as $one_gallery_photo)
                            <tr id="{{$one_gallery_photo->gallery_item_id}}">
                                <td class="medium">
                                    <div class="youtube-img">
                                        <a href="https://www.youtube.com/embed/{{$one_gallery_photo->galleryItemId->youtube_id ?? '' }}?autoplay=0"
                                           data-fancybox="gallery">
                                            <img src="https://img.youtube.com/vi/{{$one_gallery_photo->galleryItemId->youtube_id ?? '' }}/0.jpg"
                                                 alt="{{$one_gallery_photo->name ?? '' }}"
                                                 title="{{$one_gallery_photo->name ?? '' }}">
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span>{{!empty(IfHasName($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item')) ? IfHasName($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item') : trans('variables.another_name')}}</span>
                                </td>
                                <td class="edit-links edit-gallery-item">
                                    @foreach($lang_list as $lang_key => $one_lang)
                                        <a href="" data-url="{{$url_for_active_elem}}"
                                           data-lang-id="{{$one_lang->id}}" {{ empty(IfHasName($one_gallery_photo->gallery_item_id, $one_lang->id, 'gallery_item')) && $one_gallery_photo->lang_id == $one_lang->id ? 'class=active' : (!empty(IfHasName($one_gallery_photo->gallery_item_id, $one_lang->id, 'gallery_item')) ? 'class=active' : '')}}
                                           data-item-id="{{$one_gallery_photo->gallery_item_id}}">{{$one_lang->lang}}</a>
                                    @endforeach
                                </td>
                                @if($groupSubRelations->active == 1)
                                    <td class="active-link">
                                        <a href=""
                                           class="change-active{{$one_gallery_photo->galleryItemId->active == 1 ? ' active' : ''}}"
                                           data-active="{{$one_gallery_photo->galleryItemId->active}}"
                                           element-id="{{$one_gallery_photo->gallery_item_id}}" action="item"
                                           url="{{$url_for_active_elem}}"></a>
                                    </td>
                                    <td class="active-link">
                                        <a href=""
                                           class="change-active {{$one_gallery_photo->galleryItemId->show_on_main == 1 ? ' active' : ''}}"
                                           data-active="{{$one_gallery_photo->galleryItemId->show_on_main}}"
                                           element-id="{{$one_gallery_photo->gallery_item_id}}" action="show_on_main"
                                           url="{{$url_for_active_elem}}"></a>
                                    </td>
                                @endif
                                <td class="dragHandle" nowrap=""></td>
                                @if($groupSubRelations->del_to_rec == 1)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_gallery_photo->gallery_item_id}}]"
                                               value="{{$one_gallery_photo->gallery_item_id}}"
                                               url="{{urlForFunctionLanguage($lang, Str::slug($one_gallery_photo->id).'/destroyGalleryItemToCart')}}">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
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
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop

