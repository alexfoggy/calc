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
                <span>{{trans('variables.add_photo')}} "{{$gallery_subject->name ?? '' }} "</span>
            </div>
            <div class="form-body dropzone-form">
                <form action="{{url($lang, ['back','uploadGalleryPhoto'])}}" method="post"
                      class="dropzone needsclick dz-clickable"
                      id="image-upload"
                      enctype="multipart/form-data" element-id="{{$gallery_subject_id->id}}"
                      msg="{{trans('variables.img')}}">
                    <div class="dz-message needsclick">
                        <span></span>
                        {{--<input type="hidden" name="height" value="{{ $gallery_parent_subject_id->height }}"/>--}}
                        {{--<input type="hidden" name="width" value="{{ $gallery_parent_subject_id->width }}"/>--}}
                    </div>
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
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
                            <th>{{trans('variables.short_description')}}</th>
                            <th>{{trans('variables.edit')}}</th>
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
                                    <div class="item-image">
                                        @if(!empty($one_gallery_photo->galleryItemId->img) && file_exists('upfiles/galleryItems/' . $one_gallery_photo->galleryItemId->img))
                                            <a href="/upfiles/galleryItems/{{$one_gallery_photo->galleryItemId->img ?? '' }}"
                                               data-fancybox="gallery">
                                                <img src="/upfiles/galleryItems/m/{{$one_gallery_photo->galleryItemId->img ?? '' }}"
                                                     alt="{{$one_gallery_photo->name ?? '' }}"
                                                     title="{{$one_gallery_photo->name ?? '' }}">
                                            </a>
                                        @else
                                            <img src="{{asset('admin-assets/img/no-image.png')}}" alt="no-image"
                                                 title="No image">
                                        @endif
                                    </div>
                                </td>
                                <td class="medium photo-name">
                                    <span>{{!empty(IfHasName($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item')) ? IfHasName($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item') : trans('variables.another_name')}}</span>
                                </td>
                                <td class="medium photo-descr">
                                    <span>{{!empty(IfHasBody($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item')) ? IfHasBody($one_gallery_photo->gallery_item_id, $lang_id, 'gallery_item') : trans('variables.another_name')}}</span>
                                </td>
                                <td class="edit-links edit-gallery-photo">
                                    @foreach($lang_list as $lang_key => $one_lang)
                                        <a href="" data-url="{{$url_for_active_elem}}"
                                           data-lang-id="{{$one_lang->id}}"
                                           {{ !empty(IfHasName($one_gallery_photo->gallery_item_id, $one_lang->id, 'gallery_item')) ? 'class=active' : ''}}
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

