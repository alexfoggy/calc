@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">
	    <?php
	    $pdf_response = Request::input('n');
	    $pdf_alias = Request::input('alias');?>

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_element') => urlForLanguage($lang, 'createinfoitem'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'infoitemscart')
                ]
            ])
        @endif

        <div class="list-table">
            @if(!empty($info_item))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        @if(request()->segment(4) == 'arhiva')
                            <th>{{trans('variables.pdf_text')}}</th>
                        @endif
                        <th>{{trans('variables.edit_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($info_item as $key => $one_info_item)
                        <tr id="{{$one_info_item->info_item_id}}">
                            <td>
                                <span>{{ !empty(IfHasName($one_info_item->info_item_id, $lang_id, 'info_item')) ? IfHasName($one_info_item->info_item_id, $lang_id, 'info_item') : trans('variables.another_name')}}</span>
                            </td>
                            @if(request()->segment(4) == 'arhiva')
                            <td class="link-block pdf_form_delete">
                                @if($pdf_alias == $one_info_item->InfoItemId->alias)
                                    <p class="pdf_response pdf_response_visible">{{ $pdf_response ?? ''  }}</p>
                                @endif
                                @if(@$one_info_item->InfoItemId->pdffile != '')
                                    <p>{{ $one_info_item->InfoItemId->pdffile ?? ''  }}</p>
                                    <form method="POST"  action="{{ url($lang, ['back', 'deletePdf']) }}" id="pdf_form_delete" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="file_name" value="{{ $one_info_item->InfoItemId->pdffile ?? ''  }}">
                                        <input type="hidden" name="url" value="{{ url()->current() }}">
                                        <input type="hidden" name="subject" value="{{ $one_info_item->InfoItemId->alias }}">
                                        <input type="submit" value="" title="Удалить файл">
                                    </form>
                                @else
                                    <form method="POST"  action="{{ url($lang, ['back', 'uploadPdf']) }}" id="pdf-form" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="file" name="pdf">
                                        <input type="hidden" name="url" value="{{ url()->current() }}">
                                        <input type="hidden" name="subject" value="{{ $one_info_item->InfoItemId->alias }}">
                                    </form>
                                @endif
                            </td>
                            @endif
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForLanguage($lang, 'editinfoitem/'.$one_info_item->info_item_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($one_info_item->info_item_id, $one_lang->id, 'info_item')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$one_info_item->infoItemId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$one_info_item->infoItemId->active}}"
                                       element-id="{{$one_info_item->infoItemId->id}}"
                                       action="infoitemslist"
                                       url="{{$url_for_active_elem}}"></a>
                                </td>
                            @endif
                            @if($groupSubRelations->del_to_rec == 1)
                                    <td class="check-destroy-element">
                                        <input type="checkbox" class="remove-all-elements"
                                               name="destroy_elements[{{$one_info_item->info_item_id}}]"
                                               value="{{$one_info_item->info_item_id}}"
                                               url="{{urlForFunctionLanguage($lang, Str::slug($one_info_item->name).'/destroyInfoItemToCart')}}">
                                    </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $info_items_list])
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
