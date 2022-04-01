@php
    if(!@$one_image)
        $one_image = null;

    if(!@$images)
        $images = [];

    if(!@$hidden_img)
        $hidden_img = null;
@endphp

<div class="fields-row upload_photos">
    <div class="label-wrap">
        <label for="img">Выберите изображение</label>
    </div>
    <div class="input-wrap">
        @if($one_image && $one_image->img)
            <div class="one_photo" data-id="{{$one_image->id}}" data-path="{{$modules_name->modulesId->alias}}">
                <div class="ann_photo_bg" style="background-image: url('{{ asset($one_image->img? 'upfiles/'.$modules_name->modulesId->alias.'/'.$one_image->img : '') }}');" ></div>
                <div class="ann_controls">
                    <div class="delete_photo remove_uploaded_img" data-file-name="{{$one_image->img}}" data-destroy-url="{{url($lang, ['back', 'destroyFile'])}}">x</div>
                </div>
            </div>
        @elseif($images && count($images))
            @foreach($images as $one_image)
                <div class="one_photo" data-id="{{$one_image->id}}" data-path="{{$modules_name->modulesId->alias}}">
                    <div class="ann_photo_bg" style="background-image: url('{{ asset($one_image->img? 'upfiles/'.$modules_name->modulesId->alias.'/'.$one_image->img : '') }}');"></div>
                    <div class="ann_controls">
                        <div class="delete_photo remove_uploaded_img" data-file-name="{{$one_image->img}}" data-destroy-url="{{url($lang, ['back', 'destroyFiles'])}}">x</div>
                    </div>
                </div>
            @endforeach
        @endif
        <div class="images_list"></div>
        <div class="file-div">
            <label for="image_upload" class="btn upload-img upload_file_btn">Выберите файл</label>
            <input type="file" name="files[]" class="file" id="image_upload" {{ @$multiple? 'multiple' : '' }}>
            @if(!empty($hidden_img))
                <input type="hidden" class="hidden_img" name="hidden_img" value="{{$hidden_img}}">
            @endif
        </div>
    </div>
</div>