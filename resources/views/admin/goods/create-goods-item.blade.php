@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_object') => urlForLanguage($lang, 'creategoodsitem'),
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
                <span>{{trans('variables.add_element')}}</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'saveitem') }}" id="add-form"
                      enctype="multipart/form-data" page="add-item">
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
                            <input name="name" id="name">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p_id">{{trans('variables.p_id_name')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="p_id" id="p_id" class="select2">
                                {!! SelectGoodsItemTree($lang_id, 0 ,$curr_page_id) !!}
                            </select>
                        </div>
                    </div>

                     {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="used">Привязать к категории</label>
                        </div>
                        <div class="input-wrap">
                            <select name="subject[]" id="subject" class="select2" multiple="multiple">
                                <option value=""></option>
                                @if(!empty($all_goods_subjects))
                                    @foreach($all_goods_subjects as $one_goods_subject)
                                        <option value="{{ $one_goods_subject->id }}" >{{!empty(IfHasName($one_goods_subject->id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject->id, $lang_id, 'goods_subject') : trans('variables.another_name')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>--}}

                    @if(!$brand->isEmpty())
                        <div class="fields-row">
                            <div class="label-wrap">
                                <label for="brand_id">{{trans('variables.brand')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="brand_id" id="brand_id" class="select2">
                                    <option value="0" selected>{{trans('variables.without_brand')}}</option>
                                    @foreach($brand as $one_brand)
                                        @if($lang == 'ru')
                                            <option value="{{$one_brand->id ?? ''}}">{{$one_brand->name_ru ?? ''}}</option>
                                        @elseif($lang == 'ro')
                                            <option value="{{$one_brand->id ?? ''}}">{{$one_brand->name_ro ?? ''}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="descr">{{trans('variables.short_description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea id="descr" name="short_descr" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body0">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body0" data-type="ckeditor"></textarea>
                        </div>
                    </div>
                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="one_c_code">{{trans('variables.1c_code')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="one_c_code" name="one_c_code">
                        </div>
                    </div>--}}
                    {{--@if(!empty($goods_parameters))
                        @foreach($goods_parameters as $one_parameter)
                            <div class="fields-row">
                                <div class="label-wrap">
                                    <label for="{{$one_parameter->parametrId->alias ?? ''}}">{{$one_parameter->name ?? ''}}</label>
                                </div>
                                <div class="input-wrap">
                                    <input type="hidden" name="goods_parametr_id[]"
                                           value="{{$one_parameter->goods_parametr_id ?? ''}}">
                                    {{addEditParameterInItem($one_parameter->goods_parametr_id, $lang_id, null, $curr_page_id)}}
                                </div>
                            </div>
                        @endforeach
                    @endif--}}

                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="show_on_main">{{trans('variables.show_on_main')}} (Рекомендуемые)</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="show_on_main" id="show_on_main">
                        </div>
                    </div>--}}

                   {{-- <div class="fields-row">
                        <div class="label-wrap">
                            <label for="popular_element">{{trans('variables.popular_element')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="popular_element" id="popular_element">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="new_element">{{trans('variables.new_element')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="new_element" id="new_element">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="can_buy_by_bonus">{{trans('variables.can_buy_by_bonus')}} </label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="can_buy_by_bonus"
                                   id="can_buy_by_bonus">
                        </div>
                    </div>
                    <div class="fields-row hidden">
                        <div class="label-wrap">
                            <label for="datepicker">{{trans('variables.date_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input class="datetimepicker" id="datepicker" name="add_date"
                                   value="{{date('d-m-Y')}}">
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price">{{trans('variables.price')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="price" id="price">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price_old">{{trans('variables.old_price')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="price_old" id="price_old">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price_bonus"> {{trans('variables.price_bonus')}} </label>
                        </div>
                        <div class="input-wrap">
                            <input name="price_bonus" id="price_bonus">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="bonus_plus_item">{{trans('variables.bonus_plus_item')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="bonus_plus_item" id="bonus_plus_item" value="{{$goods_item_id->bonus_plus_item   ?? ''}}">
                        </div>
                    </div>--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="title">{{trans('variables.general_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="title" id="title">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description">
                        </div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)"
                                data-form-id="add-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop