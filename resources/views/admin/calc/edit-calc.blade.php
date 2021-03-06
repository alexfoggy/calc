@extends('admin.app')

@section('content')

    @include('admin.breadcrumbs')

    <div class="form-content">
{{--        @if($groupSubRelations->new == 1)--}}
{{--            @include('admin.list-elements', [--}}
{{--                'actions' => [--}}
{{--                    trans('variables.elements_list') => ($menu_id->level == 1 ? urlForFunctionLanguage($lang, '') : urlForFunctionLanguage($lang, GetParentAlias($menu_without_lang->menu_id, 'menu_id').'/memberslist')),--}}
{{--                    trans('variables.add_element') => ($menu_id->level == 1 ? urlForFunctionLanguage($lang, 'createMenu/createmenu') : urlForFunctionLanguage($lang, GetParentAlias($menu_without_lang->menu_id, 'menu_id').'/createmenu')),--}}
{{--                    trans('variables.elements_basket') => ($menu_id->level == 1 ? urlForFunctionLanguage($lang, 'menuCart/menucart') : urlForFunctionLanguage($lang, GetParentAlias($menu_without_lang->menu_id, 'menu_id').'/menucart')),--}}
{{--                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $menu_id->alias . '/editmenu/'.$menu_without_lang->menu_id.'/'.$lang_id)--}}
{{--                ]--}}
{{--            ])--}}
{{--        @else--}}
{{--            @include('admin.list-elements', [--}}
{{--                'actions' => [--}}
{{--                    trans('variables.elements_list') => ($menu_id->level == 1 ? urlForFunctionLanguage($lang, '') : urlForFunctionLanguage($lang, GetParentAlias($menu_without_lang->menu_id, 'menu_id').'/memberslist')),--}}
{{--                    trans('variables.elements_basket') => ($menu_id->level == 1 ? urlForFunctionLanguage($lang, 'menuCart/menucart') : urlForFunctionLanguage($lang, GetParentAlias($menu_without_lang->menu_id, 'menu_id').'/menucart')),--}}
{{--                    trans('variables.edit_element') => urlForFunctionLanguage($lang, $menu_id->alias . '/editmenu/'.$menu_without_lang->menu_id.'/'.$lang_id)--}}
{{--                ]--}}
{{--            ])--}}
{{--        @endif--}}

        <div class="form-page">

            <div class="form-head">
                <span>{{trans('variables.edit_element')}} "{{$menu_elems->name ?? '' }}"</span>
            </div>
            <div class="form-body">
                <form class="form" method="POST" action="{{ urlForLanguage($lang, 'saveCalc/'.$calc_id->id.'/'.$lang_to_edit) }}" id="edit-form" data-parent-url="{{--{{$url_for_active_elem}}--}}" enctype="multipart/form-data">
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
                            <label for="type_calc">?????? ????????????????????????</label>
                        </div>
                        <div class="input-wrap">
                            <select name="type_calc" id="type_calc" class="select2">
                                @foreach($types as $type_key => $one_type)
                                    <option value="{{$one_type}}" {{$calc_id->type_calc == $one_type ? 'selected' : ''}}>{{$one_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="name">{{trans('variables.title_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="name" id="name" value="{{$calc_with_lang->name ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="alias">{{trans('variables.alias_table')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="alias" id="alias" value="{{$calc_id->alias ?? '' }}">
                        </div>
                    </div>


                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="formula">??????????????</label>
                        </div>
                        <div class="formula-ajax">
                            @if(!empty($formulas))
                                @foreach($formulas as $one_formula)
                        <div class="d-flex between">
                            <div class="input-wrap w-20">
                                <input name="formula[{{$one_formula->id}}]" id="" value="{{$one_formula->formula}}" placeholder="??????????????">
                            </div>
                            <div class="input-wrap w-20 ">
                                <input name="formula_dime[{{$one_formula->id}}]" id="" value="{{$one_formula->itemByLang->dime ?? '' }}" placeholder="?????????? ?????????? ?????????????? ">
                            </div>
                            <div class="input-wrap w-20 ">
                                <input name="formula_dimetext[{{$one_formula->id}}]" id="" value="{{$one_formula->itemByLang->dime_text ?? '' }}" placeholder="?????????????? ??????????????????">
                            </div>
                            <div class="input-wrap w-20 ">
                                <input name="formula_name[{{$one_formula->id}}]" id="" value="{{$one_formula->itemByLang->name ?? '' }}" placeholder="???????????????? ??????????????">
                            </div>
                            <div class="input-wrap w-20 ">
                                <select name="parents_formulas[{{$one_formula->id}}]" id="" class="select2">
                                    <option value="0">????????????????</option>
                                    @foreach($formulas as $one_formula_row)
                                        @if($one_formula_row->id != $one_formula->id)
                                        <option value="{{$one_formula_row->id}}" @if($one_formula_row->id == $one_formula->p_id) selected @endif>{{$one_formula_row->itemByLang->name ?? ''}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="delete-row-calc delete-row-calc-formula" data-delete="{{$one_formula->id}}">
                                ??????????????
                            </div>
                        </div>
                                @endforeach
                                @else
                                <div class="d-flex between">
                                    <div class="input-wrap w-20 ">
                                        <input name="formula[]" id="" value="" placeholder="??????????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="formula_name[]" id="" value="" placeholder="???????????????? ??????????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="formula_dimetext[]" id="" value="" placeholder="?????????????? ??????????????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="formula_name[]" id="" value="" placeholder="???????????????? ??????????????">
                                    </div>
                                    <div class="delete-row-calc delete-row-calc-formula" data-delete="0">
                                        ??????????????
                                    </div>
                                </div>
                            @endif
                        </div>
{{--                        <div class="input-wrap">--}}
{{--                            <input name="formula" id="formula" value="{{$calc_id->formula ?? '' }}">--}}
{{--                        </div>--}}
                    </div>


                    <div class="add-new-variable new-row-formula" data-action="{{ urlForLanguage($lang, 'deleteCalcFormula') }}">
                        <input type="hidden" value="-1" class="status_new_row">
                        ???????????????? ?????? ??????????????
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="p[0]">??????????????????</label>
                        </div>
                        <div class="ajax-add">
                            @if($calc_vars )
                                @foreach($calc_vars as $one_var)
                        <div class="d-flex between">
                        <div class="input-wrap w-20">
                            <input name="p[{{$one_var->id}}]" id="" value="{{$one_var->itemByLang->name ?? ''}}" placeholder="????????????????">
                        </div>
                        <div class="input-wrap w-20 ">
                            <input name="v[{{$one_var->id}}]" id="" value="{{$one_var->variable ?? ''}}" placeholder="???????????????????? ???? ??????????????">
                        </div>
                            <div class="input-wrap w-20 ">
                                <input name="v_before[{{$one_var->id}}]" id="" value="{{$one_var->itemByLang->before_text ?? ''}}" placeholder="?????????? ?????????? ????????">
                            </div>
                            <div class="input-wrap w-20 ">
                                <input name="v_after[{{$one_var->id}}]" id="" value="{{$one_var->itemByLang->after_text ?? ''}}" placeholder="?????????? ?????????? ????????">
                            </div>
                            <div class="delete-row-calc delete-row-calc-var" data-delete="{{$one_var->id}}">
                                ??????????????
                            </div>
                        </div>

                                @endforeach
                                @else
                                <div class="d-flex between">
                                    <div class="input-wrap w-20">
                                        <input name="p[]" id="" value="" placeholder="????????????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="v[]" id="" value="" placeholder="???????????????????? ???? ??????????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="v_before[]" id="" value="" placeholder="?????????? ?????????? ????????">
                                    </div>
                                    <div class="input-wrap w-20 ">
                                        <input name="v_after[]" id="" value="" placeholder="?????????? ?????????? ????????">
                                    </div>
                                    <div class="delete-row-calc delete-row-calc-var" data-delete="0">
                                        ??????????????
                                    </div>
                                </div>
                                @endif

                        </div>
                    </div>

                    <div class="add-new-variable new-row-variable" data-action="{{ urlForLanguage($lang, 'deleteCalcRow') }}">
                        <input type="hidden" value="-1" class="status_new_row">
                        ???????????????? ?????? ????????????????????
                    </div>


{{--                    <div class="fields-row">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="related_works">???????????????? ??????????</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <select name="related_works[]" class="select2" multiple>--}}
{{--                                @foreach($works->goodsItemId as $one_work)--}}
{{--                                    <option value="{{$one_work->id}}" {{checkIfRelated($one_work->id,$menu_id->related_works) ? 'selected' : ''}}>{{$one_work->itemByLang->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="fields-row">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="p_id">{{trans('variables.p_id_name')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <select name="p_id" id="p_id" class="select2">--}}
{{--                                <option value="0" {{ !is_null($calc_id) ? (($menu_id->p_id == 0) ? 'selected' : '') : ''}} >{{trans('variables.home')}}</option>--}}
{{--                                {!! SelectTree($lang_id, 0, $menu_id->p_id) !!}--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="fields-row">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="set_type">{{trans('variables.parameter_type')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <select name="page_type" id="set_type" class="select2">--}}
{{--                                <option value="page" {{ !is_null($menu_id) ? (($menu_id->page_type == 'page') ? 'selected' : '') : ''}}>{{trans('variables.html_page')}}</option>--}}
{{--                                <option value="link" {{ !is_null($menu_id) ? (($menu_id->page_type == 'link') ? 'selected' : '') : ''}}>{{trans('variables.link')}}</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="fields-row link hide">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="link">{{trans('variables.link')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <input name="link" id="link" value="{{$menu_elems->link ?? '' }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="fields-row">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="title">{{trans('variables.general_title_page')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <input name="title" id="title" value="{{$menu_elems->page_title ?? '' }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="fields-row ckeditor hide">
                        <div class="label-wrap">
                            <label for="body">{{trans('variables.description')}}</label>
                        </div>
                        <div class="input-wrap">
                            <textarea name="body" id="body" data-type="ckeditor">{{$calc_with_lang->body ?? '' }}</textarea>
                        </div>
                    </div>

{{--                    @include('admin.newUploadPhotos', ['modules_name' => $modules_name, 'multiple' => true, 'one_image' => null, 'images' => $images])--}}

{{--                    <div class="fields-row">--}}
{{--                        <div class="label-wrap">--}}
{{--                            <label for="h1_title">{{trans('variables.h1_title_page')}}</label>--}}
{{--                        </div>--}}
{{--                        <div class="input-wrap">--}}
{{--                            <input name="h1_title" id="h1_title" value="{{$menu_elems->h1_title ?? '' }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_title">{{trans('variables.meta_title_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_title" id="meta_title" value="{{$calc_with_lang->meta_title ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_keywords">{{trans('variables.meta_keywords_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_keywords" id="meta_keywords" value="{{$calc_with_lang->meta_keywords ?? '' }}">
                        </div>
                    </div>
                    <div class="fields-row">
                        <div class="label-wrap">
                            <label for="meta_description">{{trans('variables.meta_description_page')}}</label>
                        </div>
                        <div class="input-wrap">
                            <input name="meta_description" id="meta_description" value="{{$calc_with_lang->meta_description ?? '' }}">
                        </div>
                    </div>
                    @if($groupSubRelations->save == 1)
                        <button class="btn" onclick="saveForm(this)" data-form-id="edit-form">{{trans('variables.save_it')}}</button>
                    @endif
                </form>
            </div>
        </div>
    </div>

@stop
