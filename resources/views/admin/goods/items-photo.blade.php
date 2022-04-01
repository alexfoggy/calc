@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_subject') => urlForLanguage($lang, 'creategoodsitem'),
                    trans('variables.edit_element') => urlForFunctionLanguage($lang, getSubjectByItem($goods_item_id->goods_subject_id)->alias.'/editgoodsitem/'.$goods_item->goods_item_id.'/'.$lang_id),
                    trans('variables.photo') => urlForLanguage($lang, 'itemsphoto/'.$goods_item->goods_item_id),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart')
                ]
            ])
        @endif


        <div class="form-page">
            <div class="form-head">
                <span>{{trans('variables.add_photo')}} "{{$goods_item->name ?? ''}}"</span>
            </div>
            <div class="form-body dropzone-form">
                <form action="{{url($lang, ['back','upload'])}}" method="post" class="dropzone needsclick dz-clickable"
                      id="image-upload"
                      enctype="multipart/form-data" element-id="{{$goods_item_id->id}}"
                      msg="{{trans('variables.img')}}">
                    <div class="dz-message needsclick">
                        <span></span>
                    </div>
                    <div class="fallback">
                        <input name="file" type="file"/>
                    </div>
                </form>
            </div>
            <div class="list-table">
                @if(!$goods_photo->isEmpty())
                    <table class="table" id="tablelistsorter" action="gallery" url="{{$url_for_active_elem}}"
                           empty-response="{{trans('variables.list_is_empty')}}">
                        <thead>
                        <tr>
                            <th>{{trans('variables.photo')}}</th>
                            @if($groupSubRelations->active == 1)
                                <th>{{trans('variables.active_table')}}</th>
                            @endif
                            <th>{{trans('variables.position_table')}}</th>
                            @if($groupSubRelations->del_from_rec == 1)
                                <th class="remove-all">{{trans('variables.delete_table')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($goods_photo as $one_goods_photo)
                            <tr id="{{$one_goods_photo->id}}">
                                <td class="medium">
                                    <div class="item-image">
                                        @if(!empty($one_goods_photo->img) && file_exists('upfiles/gallery/' . $one_goods_photo->img))
                                            <a href="/upfiles/gallery/{{$one_goods_photo->img ?? ''}}"
                                               data-fancybox="gallery">
                                                <img src="/upfiles/gallery/m/{{$one_goods_photo->img ?? ''}}">
                                            </a>
                                        @else
                                            <img src="{{asset('admin-assets/img/no-image.png')}}" alt="no-image"
                                                 title="No image">
                                        @endif
                                    </div>
                                </td>
                                <td class="active-link">
                                    <a href="" class="change-active{{$one_goods_photo->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_goods_photo->active}}" element-id="{{$one_goods_photo->id}}"
                                       action="gallery"
                                       url="{{$url_for_active_elem}}"></a>

                                </td>
                                <td class="dragHandle" nowrap=""></td>
                                @if($groupSubRelations->del_from_rec == 1)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_goods_photo->id}}]"
                                               value="{{$one_goods_photo->id}}"
                                               url="{{urlForLanguage($lang, 'destroygoodsphoto')}}">
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
