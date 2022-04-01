@if(!empty($modules_name))
    <div class="label-wrap">
        <label for="img">{{trans('variables.img')}}</label>
    </div>
    <div class="input-wrap">
        <div class='file-div'>
            <button type="button" class='btn upload-img'>
                <span class='glyphicon glyphicon-refresh-animate'>{{trans('variables.select_file')}}</span>
                <span class="loader-list"></span>
            </button>
            @if(empty($element_id) && empty($element_by_lang))
                <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                       data-destroy-url="{{url($lang, ['back', 'destroyFiles'])}}"
                       data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                       path="{{$modules_name->modulesId->alias}}"/>
            @else
                @if(!empty($element_id) && !is_null($element_id->moduleMultipleImg) && !$element_id->moduleMultipleImg->isEmpty())
                    @foreach($element_id->moduleMultipleImg as $one_img)
                        <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                               path="{{$modules_name->modulesId->alias}}"
                               data-destroy-url="{{url($lang, ['back', 'destroyFiles'])}}"
                               data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                               value="{{asset("upfiles/{$modules_name->modulesId->alias}/{$one_img->img}")}}"/>
                        @if(file_exists("upfiles/" . $modules_name->modulesId->alias . "/" . $one_img->img) && !empty($one_img->img))
                            <div class="img-block"
                                 style="background: url('{{asset("/upfiles/{$modules_name->modulesId->alias}/{$one_img->img}")}}') center; background-size: cover;"
                                 data-id="{{$one_img->id}}">
                                <span class="hidden-text">{{$element_id->alias ?? '' }}</span>
                                <span class="remove-upload-img" data-file-name="{{$one_img->img}}">x</span>
                                <span class="active-upload-img{{$one_img->active == 1 ? ' active' : ''}}"
                                      data-active="{{$one_img->active}}"
                                      data-file-name="{{$one_img->img}}">{{$one_img->active == 1 ? ' +' : '-'}}</span>
                            </div>
                        @else
                            <div class="img-block"
                                 style="background: url({{asset('front-assets/img/no-image.png')}}) center; background-size: cover;"
                                 data-id="{{$one_img->id}}">
                                <span class="hidden-text">{{$element_id->alias ?? '' }}</span>
                                <span class="remove-upload-img" data-file-name="{{$one_img->img}}">x</span>
                            </div>
                        @endif
                    @endforeach
                @elseif(!empty($element_id) && !empty($element_by_lang) && !empty($element_id->img))
                    <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                           path="{{$modules_name->modulesId->alias}}"
                           data-destroy-url="{{url($lang, ['back', 'destroyFile'])}}"
                           data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                           value="{{asset("upfiles/{$modules_name->modulesId->alias}/{$element_id->img}")}}"/>
                    @if(file_exists("upfiles/" . $modules_name->modulesId->alias . "/" . $element_id->img) && !empty($element_id->img))
                        <div class="img-block"
                             style="background: url('/upfiles/{{$modules_name->modulesId->alias}}/{{$element_id->img}}') center; background-size: cover;"
                             data-id="{{$element_id->id}}">
                            <span class="hidden-text">{{$element_id->alias ?? '' }}</span>
                            <span class="remove-upload-img" data-file-name="{{$element_id->img}}">x</span>
                        </div>
                    @else
                        <div class="img-block"
                             style="background: url({{asset('front-assets/img/no-image.png')}}) center; background-size: cover;"
                             data-id="{{$element_id->id}}">
                            <span class="hidden-text">{{$element_by_lang->name ?? '' }}</span>
                            <span class="remove-upload-img" data-file-name="{{$element_by_lang->img}}">x</span>
                        </div>
                    @endif
                @elseif(!empty($element_id) && !empty($element_id->img))
                    <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                           path="{{$modules_name->modulesId->alias}}"
                           data-destroy-url="{{url($lang, ['back', 'destroyFile'])}}"
                           data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                           value="{{asset("upfiles/{$modules_name->modulesId->alias}/{$element_id->img}")}}"/>
                    @if(file_exists("upfiles/" . $modules_name->modulesId->alias . "/" . $element_id->img) && !empty($element_id->img))
                        <div class="img-block"
                             style="background: url('/upfiles/{{$modules_name->modulesId->alias}}/{{$element_id->img}}') center; background-size: cover;"
                             data-id="{{$element_id->id}}">
                            <span class="hidden-text">{{$element_id->alias or ''}}</span>
                            <span class="remove-upload-img" data-file-name="{{$element_id->img}}">x</span>
                        </div>
                    @else
                        <div class="img-block"
                             style="background: url({{asset('front-assets/img/no-image.png')}}) center; background-size: cover;"
                             data-id="{{$element_id->id}}">
                            <span class="hidden-text">{{$element_by_lang->name or ''}}</span>
                            <span class="remove-upload-img" data-file-name="{{$element_by_lang->img}}">x</span>
                        </div>
                    @endif
                @elseif(empty($element_id) && !empty($element_by_lang))
                    <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                           data-destroy-url="{{url($lang, ['back', 'destroyFile'])}}"
                           data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                           path="{{$modules_name->modulesId->alias}}"/>
                @else
                    <input type="hidden" name="file[]" class="file" data-url="{{url($lang, ['back', 'upload'])}}"
                           data-destroy-url="{{url($lang, ['back', 'destroyFiles'])}}"
                           data-activate-url="{{url($lang, ['back', 'activateFile'])}}"
                           path="{{$modules_name->modulesId->alias}}"/>
                @endif
            @endif
        </div>
    </div>
@endif