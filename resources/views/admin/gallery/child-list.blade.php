@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if(empty($child_gallery_list) && empty($child_gallery_item_list))
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject' || request()->segment(4) == 'createGalleryItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGallerySubject/creategallerysubject'),
                            trans('variables.add_video') => urlForFunctionLanguage($lang, 'createGalleryVideo/itemsvideo'),
                            trans('variables.add_photo') => urlForFunctionLanguage($lang, 'createGalleryPhoto/itemsphoto'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart'),
                            trans('variables.item_basket') => urlForFunctionLanguage($lang, 'galleryItemCart/galleryitemcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_subject') => urlForLanguage($lang, 'creategallerysubject'),
                            trans('variables.add_video') => urlForLanguage($lang, 'itemsvideo'),
                            trans('variables.add_photo') => urlForLanguage($lang, 'itemsphoto'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'gallerysubjectcart'),
                            trans('variables.item_basket') => urlForLanguage($lang, 'galleryitemcart'),
                        ]
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'galleryItemCart/galleryitemcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'galleryitemcart'),
                        ]
                    ])
                @endif

            @endif
        @elseif(CheckIfSubjectHasItems('gallery', $gallery_list_id->id)->isEmpty())
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGallerySubject/creategallerysubject'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_subject') => urlForLanguage($lang, 'creategallerysubject'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'gallerysubjectcart'),
                        ]
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGallerySubject')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'gallerysubjectcart'),
                        ]
                    ])
                @endif
            @endif
        @else
            @if($groupSubRelations->new == 1)
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGalleryItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.add_video') => urlForFunctionLanguage($lang, 'createGalleryVideo/itemsvideo'),
                            trans('variables.add_photo') => urlForFunctionLanguage($lang, 'createGalleryPhoto/itemsphoto'),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'galleryItemCart/galleryitemcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.add_video') => urlForLanguage($lang, 'itemsvideo'),
                            trans('variables.add_photo') => urlForLanguage($lang, 'itemsphoto'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'galleryitemcart'),
                        ]
                    ])
                @endif
            @else
                @if(request()->segment(5) == '' || request()->segment(4) == 'createGalleryItem')
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                            trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'galleryItemCart/galleryitemcart'),
                        ]
                    ])
                @else
                    @include('admin.list-elements', [
                        'actions' => [
                            trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                            trans('variables.elements_basket') => urlForLanguage($lang, 'galleryitemcart'),
                        ]
                    ])
                @endif
            @endif
        @endif


        <div class="list-table">
            @if(CheckIfSubjectHasItems('gallery', $gallery_list_id->id)->isEmpty())
                @if(!empty($child_gallery_list))
                    <table class="table" id="tablelistsorter" action="subject" url="{{$url_for_active_elem}}"
                           empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                        <tr>
                            <th>{{trans('variables.title_table')}}</th>
                            <th>{{trans('variables.edit_table')}}</th>
                            @if($groupSubRelations->active == 1)
                                <th>{{trans('variables.active_table')}}</th>
                            @endif
                            @if($child_gallery_list[0]->gallerySubjectId->level == 3)
                            <th>{{trans('variables.leader_table')}}</th>
                            <th>{{trans('variables.new_table')}}</th>
                            @endif
                            <th>{{trans('variables.position_table')}}</th>
                            @if($groupSubRelations->del_to_rec == 1)
                                <th class="remove-all">{{trans('variables.delete_table')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($child_gallery_list as $key => $one_gallery_subject_list)
                            <tr id="{{$one_gallery_subject_list->gallery_subject_id}}">
                                <td>
                                    <a href="{{urlForFunctionLanguage($lang, $one_gallery_subject_list->gallerySubjectId->alias.'/memberslist')}}">{{!empty(IfHasName($one_gallery_subject_list->gallery_subject_id, $lang_id, 'gallery_subject')) ? IfHasName($one_gallery_subject_list->gallery_subject_id, $lang_id, 'gallery_subject') : trans('variables.another_name')}}</a>
                                </td>
                                <td class="edit-links">
                                    @foreach($lang_list as $lang_key => $one_lang)
                                        <a href="{{urlForFunctionLanguage($lang, GetParentAlias($one_gallery_subject_list->gallery_subject_id, 'gallery_subject_id').'/editgallerysubject/'.$one_gallery_subject_list->gallery_subject_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_gallery_subject_list->gallery_subject_id, $one_lang->id, 'gallery_subject')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                    @endforeach
                                </td>
                                @if($groupSubRelations->active == 1)
                                    <td class="small active-link">
                                        {{--{{ dump($one_gallery_subject_list->gallerySubjectId->leader) }}--}}
                                        <a href=""
                                           class="change-active{{$one_gallery_subject_list->gallerySubjectId->active == 1 ? ' active' : ''}}"
                                           data-active="{{$one_gallery_subject_list->gallerySubjectId->active}}"
                                           element-id="{{$one_gallery_subject_list->gallerySubjectId->id}}"
                                           action="subject"
                                           url="{{$url_for_active_elem}}"></a>
                                    </td>
                                    @if($one_gallery_subject_list->gallerySubjectId->level == 3)
                                    <td class="small active-link">
                                        <a href=""
                                                class="change-leader{{$one_gallery_subject_list->gallerySubjectId->leader == 1 ? ' active' : ''}}"
                                                data-active="{{$one_gallery_subject_list->gallerySubjectId->leader}}"
                                                element-id="{{$one_gallery_subject_list->gallerySubjectId->id}}"
                                                action="subject"
                                                url="{{$url_for_active_elem}}"></a>
                                    </td>
                                    <td class="small active-link">
                                        <a href=""
                                                class="change-new{{$one_gallery_subject_list->gallerySubjectId->new == 1 ? ' active' : ''}}"
                                                data-active="{{$one_gallery_subject_list->gallerySubjectId->new}}"
                                                element-id="{{$one_gallery_subject_list->gallerySubjectId->id}}"
                                                action="subject"
                                                url="{{$url_for_active_elem}}"></a>
                                    </td>
                                     @endif
                                @endif
                                <td class="dragHandle"></td>
                                @if($groupSubRelations->del_to_rec == 1)
                                    @if(IfHasChildActive($one_gallery_subject_list->gallery_subject_id, 'gallery_subject')->isEmpty() && CheckIfSubjectHasItems('gallery', $one_gallery_subject_list->gallery_subject_id)->isEmpty())
                                        <td class="check-destroy-element">
                                            <input type="checkbox" class="remove-all-elements"
                                                   name="destroy_elements[{{$one_gallery_subject_list->gallery_subject_id}}]"
                                                   value="{{$one_gallery_subject_list->gallery_subject_id}}"
                                                   url="{{urlForFunctionLanguage($lang, Str::slug($one_gallery_subject_list->id).'/destroyGallerySubjectToCart')}}">
                                        </td>
                                    @else
                                        <td>
                                            <span>{{trans('variables.delete_inner_modules')}}</span>
                                        </td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <td colspan="10"></td>
                        </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif

            @elseif(!CheckIfSubjectHasItems('gallery', $gallery_list_id->id)->isEmpty())
                @if(!empty($child_gallery_item_list))
                    <table class="table" id="tablelistsorter" action="item" url="{{$url_for_active_elem}}"
                           empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                        <tr>
                            <th>{{trans('variables.photo')}}</th>
                            <th>{{trans('variables.title_table')}}</th>
                            @if($groupSubRelations->active == 1)
                                <th>{{trans('variables.active_table')}}</th>
                                <th>{{trans('variables.show_on_main')}}</th>
                            @endif
                            <th>{{trans('variables.position_table')}}</th>
                            @if($groupSubRelations->del_to_rec == 1)
                                <th class="remove-all">{{trans('variables.delete_table')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($child_gallery_item_list as $key => $one_gallery_item_list)
                            <tr id="{{$one_gallery_item_list->gallery_item_id}}">
                                <td class="medium">
                                    @if($one_gallery_item_list->galleryItemId->type == 'photo')
                                        <div class="item-image">
                                            @if(!empty($one_gallery_item_list->galleryItemId->img) && file_exists('upfiles/galleryItems/' . $one_gallery_item_list->galleryItemId->img))
                                                <a href="/upfiles/galleryItems/{{$one_gallery_item_list->galleryItemId->img ?? '' }}"
                                                   data-fancybox="gallery">
                                                    <img src="/upfiles/galleryItems/m/{{$one_gallery_item_list->galleryItemId->img ?? '' }}"
                                                         alt="{{$one_gallery_item_list->name ?? '' }}"
                                                         title="{{$one_gallery_item_list->name ?? '' }}">
                                                </a>
                                            @else
                                                <img src="{{asset('admin-assets/img/no-image.png')}}" alt="no-image"
                                                     title="No image">
                                            @endif
                                        </div>
                                    @else
                                        <div class="youtube-img">
                                            <a href="https://www.youtube.com/embed/{{$one_gallery_item_list->galleryItemId->youtube_id ?? '' }}?autoplay=0"
                                               data-fancybox="gallery">
                                                <img src="https://img.youtube.com/vi/{{$one_gallery_item_list->galleryItemId->youtube_id ?? '' }}/0.jpg"
                                                     alt="{{$one_gallery_item_list->name ?? '' }}"
                                                     title="{{$one_gallery_item_list->name ?? '' }}">
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="medium">
                                    <span>{{!empty(IfHasName($one_gallery_item_list->gallery_item_id, $lang_id, 'gallery_item')) ? IfHasName($one_gallery_item_list->gallery_item_id, $lang_id, 'gallery_item') : trans('variables.another_name')}}</span>
                                </td>
                                @if($groupSubRelations->active == 1)
                                    <td class="active-link">
                                        <a href=""
                                           class="change-active{{$one_gallery_item_list->galleryItemId->active == 1 ? ' active' : ''}}"
                                           data-active="{{$one_gallery_item_list->galleryItemId->active}}"
                                           element-id="{{$one_gallery_item_list->galleryItemId->id}}" action="item"
                                           url="{{$url_for_active_elem}}"></a>
                                    </td>
                                    <td class="active-link">
                                        <a href=""
                                           class="change-active {{$one_gallery_item_list->galleryItemId->show_on_main == 1 ? ' active' : ''}}"
                                           data-active="{{$one_gallery_item_list->galleryItemId->show_on_main}}"
                                           element-id="{{$one_gallery_item_list->gallery_item_id}}"
                                           action="show_on_main"
                                           url="{{$url_for_active_elem}}"></a>
                                    </td>
                                @endif
                                <td class="dragHandle"></td>
                                @if($groupSubRelations->del_to_rec == 1)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_gallery_item_list->gallery_item_id}}]"
                                               value="{{$one_gallery_item_list->gallery_item_id}}"
                                               url="{{urlForFunctionLanguage($lang, Str::slug($one_gallery_item_list->id).'/destroyGalleryItemToCart')}}">
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tfoot>
                        <tr>
                            <td colspan="10">
                                @include('admin.pagination', ['paginator' => $child_gallery_item_list_id])
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                @else
                    <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
                @endif
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop
