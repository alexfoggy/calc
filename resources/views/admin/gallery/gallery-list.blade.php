@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_subject') => urlForFunctionLanguage($lang, 'createGallerySubject/creategallerysubject'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'gallerySubjectCart/gallerysubjectcart')
                ]
            ])
        @endif


        <div class="list-table">
            @if(!empty($gallery_subject_list))
                <table class="table" id="tablelistsorter" action="subject" url="{{$url_for_active_elem}}" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        <th>{{trans('variables.position_table')}}</th>
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($gallery_subject_list as $key => $one_gallery_subject_list)
                        <tr id="{{$one_gallery_subject_list->gallery_subject_id}}">
                            <td>
                                <a href="{{urlForFunctionLanguage($lang, $one_gallery_subject_list->gallerySubjectId->alias.'/memberslist')}}">{{!empty(IfHasName($one_gallery_subject_list->gallery_subject_id, $lang_id, 'gallery_subject')) ? IfHasName($one_gallery_subject_list->gallery_subject_id, $lang_id, 'gallery_subject') : trans('variables.another_name')}}</a>
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, $one_gallery_subject_list->gallerySubjectId->alias.'/editgallerysubject/'.$one_gallery_subject_list->gallery_subject_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_gallery_subject_list->gallery_subject_id, $one_lang->id, 'gallery_subject')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$one_gallery_subject_list->gallerySubjectId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_gallery_subject_list->gallerySubjectId->active}}"
                                       element-id="{{$one_gallery_subject_list->gallerySubjectId->id}}" action="subject"
                                       url="{{$url_for_active_elem}}"></a>
                                </td>
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
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $gallery_subject_id_list])
                        </td>
                    </tr>
                    </tfoot>
                </table>
            @else
                <div class="empty-response">{{trans('variables.list_is_empty')}}</div>
            @endif
        </div>
        <div id="loader-gif" class="loader-list"></div>
    </div>

@stop

