@extends('admin.app')



@section('content')



    @include('admin.breadcrumbs')



    <div class="form-content">


        <div class="form-page">



            <div class="form-head">

                <span>Add colors</span>

            </div>

            <div class="form-body">

                <form class="form" method="POST" action="" id="edit-form" data-parent-url="" enctype="multipart/form-data">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    {{--<div class="fields-row">--}}

                        {{--<div class="label-wrap">--}}

                            {{--<label for="lang">Lang</label>--}}

                        {{--</div>--}}

                        {{--<div class="input-wrap">--}}

                            {{--<select name="lang" id="lang" class="select2">--}}

                                {{--@foreach($lang_list as $lang_key => $one_lang)--}}

                                    {{--<option value="{{$one_lang->id}}" {{$one_lang->id == $edited_lang_id ? 'selected' : ''}}>{{$one_lang->descr}}</option>--}}

                                {{--@endforeach--}}

                            {{--</select>--}}

                        {{--</div>--}}

                    {{--</div>--}}

                    <div class="fields-row">

                        <div class="label-wrap">

                            <label for="name">Title</label>

                        </div>

                        <div class="input-wrap">

                            <input name="name" id="name" value="" placeholder="">

                        </div>

                    </div>


                    {{--<div class="fields-row link hide">--}}

                        {{--<div class="label-wrap">--}}

                            {{--<label for="link">{{trans('variables.link')}}</label>--}}

                        {{--</div>--}}

                        {{--<div class="input-wrap">--}}

                            {{--<input name="link" id="link" value="{{$menu_id->link ?? ''}}">--}}

                        {{--</div>--}}

                    {{--</div>--}}



                    <div class="fields-row">

                        {{--@include('admin.uploadOnePhoto', ['modules_name' => $modules_name, 'element_id' => $menu_id, 'element_by_lang' => ''])--}}

                    </div>

                    @if($groupSubRelations->save == 1)

                        <button class="btn" onclick="saveForm(this)" data-form-id="edit-form">Submit</button>

                    @endif

                </form>

            </div>

        </div>

    </div>



@stop