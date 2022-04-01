@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
        @if($groupSubRelations->new == 1)
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.add_item') => urlForLanguage($lang, 'creategoodsitem'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                    trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsitem/'.$goods_without_lang->goods_item_id.'/'.$lang_id)
                ]
            ])
        @else
            @include('admin.list-elements', [
                'actions' => [
                    trans('variables.elements_list') => urlForLanguage($lang, 'memberslist'),
                    trans('variables.elements_basket') => urlForLanguage($lang, 'goodsitemcart'),
                    trans('variables.edit_element') => urlForLanguage($lang, 'editgoodsitem/'.$goods_without_lang->goods_item_id.'/'.$lang_id)
                ]
            ])
        @endif

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$goods_elems->name ?? ''}}"</span>
            </div>


            <div class="form-body">
                <form class="form" method="POST"
                      action="{{ urlForLanguage($lang, 'saveitem/'.$goods_without_lang->goods_item_id.'/'.$lang_id) }}"
                      id="edit-form" enctype="multipart/form-data" page="edit-item">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="position" value="{{ $goods_item_id->position ?? ''}}">
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
{{--                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="tech">Технологии</label>
                        </div>
                        <div class="input-wrap">
                            <select name="tech[]" id="tech" class="select2" multiple>
                                @foreach($techs as $tech_one)
                                    <option value="{{$tech_one->id}}" {{checkIfRelated($tech_one->id,$goods_item_id->tech) ? 'selected' : ''}}>{{$tech_one->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$goods_elems->name ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$goods_item_id->alias ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p_id">{{trans('variables.p_id_name')}}</label>
                        </div>
                        <div class="input-wrap">
                            <select name="p_id" id="p_id" class="select2">
                                {!! SelectGoodsItemTree($lang_id, 0 ,$goods_item_id->goods_subject_id) !!}
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
                                        <option value="{{ $one_goods_subject->id }}" {{ !empty($used_subjects) && in_array($one_goods_subject->id, $used_subjects)? 'selected' : '' }}>{{!empty(IfHasName($one_goods_subject->id, $lang_id, 'goods_subject')) ? IfHasName($one_goods_subject->id, $lang_id, 'goods_subject') : trans('variables.another_name')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>--}}

                 {{--   @if(!$brand->isEmpty())
                        <div class="fields-row">
                            <div class="label-wrap">
                                <label for="brand_id">{{trans('variables.brand')}}</label>
                            </div>
                            <div class="input-wrap">
                                <select name="brand_id" id="brand_id" class="select2">
                                    <option value="0">{{trans('variables.without_brand')}}</option>
                                    @foreach($brand as $one_brand)
                                        @if($lang == 'ru')
                                            <option value="{{$one_brand->id ?? ''}}" {{$goods_item_id->brand_id == $one_brand->id ? 'selected' : ''}}>{{$one_brand->name_ru ?? ''}}</option>
                                        @elseif($lang == 'ro')
                                            <option value="{{$one_brand->id ?? ''}}" {{$goods_item_id->brand_id == $one_brand->id ? 'selected' : ''}}>{{$one_brand->name_ro ?? ''}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="descr">{{trans('variables.short_description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea id="descr" name="short_descr"
                                      rows="10">{{$goods_elems->short_descr ?? ''}}</textarea>
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="body0">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body0"
                                      data-type="ckeditor">{{$goods_elems->body ?? ''}}</textarea>
                        </div>
                    </div>

                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="youtube_link">{{trans('variables.youtube_id')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="youtube_link" name="youtube_link" value="{{$goods_item_id->youtube_link ?? ''}}">
                        </div>
                    </div>--}}

                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="one_c_code">{{trans('variables.1c_code')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input id="one_c_code" name="one_c_code" value="{{$goods_item_id->one_c_code ?? ''}}">
                        </div>
                    </div>--}}

                  {{--  @if(!empty($goods_parameters))
		                @php
		                $curr_goods_subject_id = null;
		                if(!is_null($current_subject_id)){
			                $curr_goods_subject_id = $current_subject_id->id;
		                }
		                else{
			                $curr_goods_subject_id = $goods_item_id->goods_subject_id;
		                }
		                @endphp
                        <input type="hidden" name="param_subj" value="{{ $curr_goods_subject_id }}">
                        @foreach($goods_parameters as $one_parameter)
                            <div class="fields-row">
                                <div class="label-wrap">
                                    <label for="{{$one_parameter->parametrId->alias ?? ''}}">{{$one_parameter->name ?? ''}}</label>
                                </div>
                                <div class="input-wrap">
                                    <input type="hidden" name="goods_parametr_id[]"
                                           value="{{$one_parameter->goods_parametr_id ?? ''}}">
                                    {{addEditParameterInItem($one_parameter->goods_parametr_id, $lang_id, $goods_without_lang->goods_item_id, $curr_goods_subject_id)}}
                                </div>
                            </div>
                        @endforeach
                    @endif--}}
                    {{--<div class="fields-row">
                        <div class="label-wrap">
                            <label for="show_on_main">{{trans('variables.show_on_main')}} (Рекомендуемые)</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="show_on_main"
                                   id="show_on_main" {{$goods_item_id->show_on_main == 1 ? 'checked' : ''}}>
                        </div>
                    </div>--}}

                {{--    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="popular_element">{{trans('variables.popular_element')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="popular_element"
                                   id="popular_element" {{$goods_item_id->popular_element == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="new_element">{{trans('variables.new_element')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="new_element"
                                   id="new_element" {{$goods_item_id->new_element == 1 ? 'checked' : ''}}>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="can_buy_by_bonus">{{trans('variables.can_buy_by_bonus')}} </label>
                        </div>
                        <div class="input-wrap">
                            <input type="checkbox" name="can_buy_by_bonus"
                                   id="can_buy_by_bonus" {{$goods_item_id->can_buy_by_bonus  == 1 ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price">{{trans('variables.price')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="price" id="price" value="{{$goods_item_id->price ?? ''}}">
                        </div>
                    </div>


                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price_old">{{trans('variables.old_price')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="price_old" id="price_old" value="{{$goods_item_id->price_old ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="price_bonus"> {{trans('variables.price_bonus')}} </label>
                        </div>
                        <div class="input-wrap">
                            <input name="price_bonus" id="price_bonus" value="{{$goods_item_id->price_bonus  ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="bonus_plus_item">{{trans('variables.bonus_plus_item')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="bonus_plus_item" id="bonus_plus_item" value="{{$goods_item_id->bonus_plus_item   ?? ''}}">
                        </div>
                    </div>
--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="title">{{trans('variables.general_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="title" id="title" value="{{$goods_elems->page_title ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title" value="{{$goods_elems->meta_title ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords"
                                   value="{{$goods_elems->meta_keywords ?? ''}}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description"
                                   value="{{$goods_elems->meta_description ?? ''}}">
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