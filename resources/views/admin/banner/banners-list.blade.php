@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="list-page">

        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.add_element') => urlForFunctionLanguage($lang, 'createBanner/createitem'),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForFunctionLanguage($lang, ''),
                    trans('variables.elements_basket') => urlForFunctionLanguage($lang, 'bannersCart/cartitems')
                ]
            ])
        @endif


        <div class="list-table">
            @if(!empty($banner_list))
                <table class="table" empty-response="{{trans('variables.list_is_empty')}}">
                    <thead>
                    <tr>
                        <th>{{trans('variables.title_table')}}</th>
                        <th>{{trans('variables.description')}}</th>
                        <th>{{trans('variables.edit_table')}}</th>
                        <th>{{trans('variables.date_table')}}</th>
                        @if($groupSubRelations->active == 1)
                            <th>{{trans('variables.active_table')}}</th>
                        @endif
                        @if($groupSubRelations->del_to_rec == 1)
                            <th class="remove-all">{{trans('variables.delete_table')}}</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banner_list as $key => $banner)
                        <tr id="{{$banner->banner_id}}">
                            <td>
                                <span>{{ !empty(IfHasName($banner->banner_id, $lang_id, 'banner')) ? IfHasName($banner->banner_id, $lang_id, 'banner') : trans('variables.another_name')}}</span>
                            </td>
                            <td class="medium">
                                <span>{!! !empty($banner->body) ? strPosText($banner->body, 200) : '' !!}</span>
                            </td>
                            <td class="edit-links">
                                @foreach($lang_list as $lang_key => $one_lang)
                                    <a href="{{urlForFunctionLanguage($lang, Str::slug($banner->name).'/edititem/'.$banner->banner_id.'/'.$one_lang->id)}}" {{ !empty(IfHasName($banner->banner_id, $one_lang->id, 'banner')) ? 'class=active' : ''}}>{{$one_lang->lang}}</a>
                                @endforeach
                            </td>
                            <td>
                                <span>{{date('d-m-Y H:i', strtotime($banner->bannerId->created_at))}}</span>
                            </td>
                            @if($groupSubRelations->active == 1)
                                <td class="small active-link">
                                    <a href=""
                                       class="change-active{{$banner->bannerId->active == 1 ? ' active' : ''}}"
                                       data-active="{{$banner->bannerId->active}}"
                                       element-id="{{$banner->bannerId->id}}"></a>
                                </td>
                            @endif
                            @if($groupSubRelations->del_to_rec == 1)
                                <td class="check-destroy-element">
                                    <input type="checkbox" class="remove-all-elements"
                                           name="destroy_elements[{{$banner->banner_id}}]" value="{{$banner->banner_id}}"
                                           url="{{urlForFunctionLanguage($lang, Str::slug($banner->name).'/destroyBannerToCart')}}">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('admin.pagination', ['paginator' => $banner_list_ids])
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
