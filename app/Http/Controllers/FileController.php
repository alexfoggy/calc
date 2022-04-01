<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\GalleryItemId;
use App\Models\GoodsPhoto;
use App\Models\InfoItemId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->middleware('auth');
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function upload(Request $request) {

        if($request->file())
        {
            if(!is_null($request->input('gallery-id'))){
                return $this->uploadGallery($request);
            }
            else{
                return $this->uploadOneImg($request);
            }
        }

        return response()->json([
            'status'=>false,
            'messages' => controllerTrans('variables.something_wrong', $this->lang)
        ]);
    }

	public function uploadPdf(Request $request)
	{
		$url = $request->input('url');

		$pdf = $request->file('pdf');
		$path = 'upfiles/file_revista/';
		$subject = $request->input('subject');
		$response = '';
		if(Input::hasFile('pdf')){
			if($pdf->getClientOriginalExtension() == 'pdf'){
				if($pdf->getClientSize() < 60000000000){
					if (File::exists($path. '' .$subject. '.pdf')){
						File::delete($path. '' .$subject. '.pdf');
						$pdf->move('upfiles/file_revista/', $subject .'.pdf');

						DB::table('info_item_id')
						  ->where('alias', $subject)
						  ->update(['pdffile' => $subject.'.pdf']);

						$response = trans('variables.pdf_edited');
					}
					else{
						$pdf->move('upfiles/file_revista/', $subject .'.pdf');

						DB::table('info_item_id')
						  ->where('alias', $subject)
						  ->update(['pdffile' => $subject.'.pdf']);

						$response = trans('variables.pdf_saved');
					}
				}
				else $response = trans('variables.pdf_size');
			}
			else $response = trans('variables.pdf_format');
		}
		else $response = trans('variables._wrong_message');

		return redirect($url.'?alias='.$subject.'&n='.$response);
	}

	public function deletePdf(Request $request)
	{
		$url = $request->input('url');
		$pdf_name = $request->input('file_name');
		$path = 'upfiles/file_revista/';
		$subject = $request->input('subject');

		$pdf = InfoItemId::where('alias', $subject)
		                     ->first();

		$response = '';

		if($pdf->pdffile != ''){
			if($pdf->pdffile == $pdf_name){
				if (File::exists($path. '' .$subject. '.pdf')){
					File::delete($path. '' .$subject. '.pdf');

					DB::table('info_item_id')
					  ->where('alias', $subject)
					  ->update(['pdffile' => '']);

					$response = trans('variables.pdf_deleted');
				}
				else{
					DB::table('info_item_id')
					  ->where('alias', $subject)
					  ->update(['pdffile' => '']);

					$response = trans('variables.pdf_deleted');
				}
			}
		}
		else
			$response = trans('variables._wrong_message');


		return redirect($url.'?alias='.$subject.'&n='.$response);
	}

    public function uploadOneImg(Request $request){
        $response = [];
        $key = 0;
        $uploadPath = $request->input('uploadPath');
        foreach($request->file() as $singleFile) {
            foreach ($singleFile as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = md5(time()) . rand(11111111, 99999999) . '.' . $extension;
                switch (strtolower($file->getClientOriginalExtension())) {
                    case 'jpg':
                    case 'png':
                    case 'svg':
                    case 'jpeg': {
                        $fileType = 'img';
                        $destinationPath = 'upfiles/' . $uploadPath;
                        break;
                    }
                    default : {
                        return response()->json([
                            'status' => false,
                            'messages' => controllerTrans('variables.invalid_img_format', $this->lang)
                        ]);
                        break;
                    }
                }

                $file->move($destinationPath, $fileName);

                if (!File::exists($destinationPath . '/s')) {
                    File::makeDirectory($destinationPath . '/s');
                }
                if (!File::exists($destinationPath . '/m')) {
                    File::makeDirectory($destinationPath . '/m');
                }

                if ($uploadPath == 'gallery') {
                	CreateImageManipulator($uploadPath, $destinationPath . '/m/', $fileName, 270, 273);
                }

	            if ($uploadPath == 'menu') {
		            CreateImageManipulator($uploadPath, $destinationPath . '/s/', $fileName, 177, 240);
	            }
	            if ($uploadPath == 'info_line') {
		            CreateImageManipulator($uploadPath, $destinationPath . '/s/', $fileName, 400, 206);
		            CreateImageManipulator($uploadPath, $destinationPath . '/m/', $fileName, 732, 375);
	            }

                if ($uploadPath == 'admin_user') {
                    CreateImageManipulator($uploadPath, $destinationPath . '/s/', $fileName, 52, 42);
                }

//                    Image::make($destinationPath.'/'.$fileName)->resize(200,150)->save($destinationPath.'/s/'.$fileName);
//                    Image::make($destinationPath.'/'.$fileName)->resize(500,450)->save($destinationPath.'/m/'.$fileName);

                $response['fileName'][$key] = $fileName;
                $response['fileType'][$key] = $fileType;
                $response['url'][$key] = asset($destinationPath . '/' . $fileName);
                $key++;
            }
        }
        return response()->json([
            $response,
            'status'=>true
        ]);
    }


    /*destroy files*/
    public function destroyOneSingleImg(Request $request)
    {
        $curr_img = $request->input('curr_img');
        $curr_id = $request->input('curr_id');
        $uploadPath = $request->input('uploadPath');

        if(is_null($curr_img) || is_null($uploadPath))
            return response()->json([
                'status'=>false,
                'messages' => controllerTrans('variables.something_wrong', $this->lang)
            ]);

        if($uploadPath == 'gallery')
	        DB::table('gallery_subject_id')
	          ->where('id', $curr_id)
	          ->update(['img' => '']);
        elseif($uploadPath == 'menu')
	        DB::table('menu_id')
	          ->where('id', $curr_id)
	          ->update(['img' => null]);
        elseif($uploadPath == 'goods')
            DB::table('goods_subject_id')
                ->where('id', $curr_id)
                ->update(['img' => null]);
        elseif($uploadPath == 'shops'){
            DB::table('shops_id')
                ->where('id', $curr_id)
                ->update(['img' => '']);
        }
	    else
        DB::table($uploadPath)
            ->where('id', $curr_id)
            ->update(['img' => '']);

        $destinationPath = 'upfiles/'.$uploadPath;

        if (File::exists($destinationPath . '/s/' . $curr_img))
            File::delete($destinationPath . '/s/' . $curr_img);

        if (File::exists($destinationPath . '/m/' . $curr_img))
            File::delete($destinationPath . '/m/' . $curr_img);

        if (File::exists($destinationPath . '/' . $curr_img))
            File::delete($destinationPath . '/' . $curr_img);

        return response()->json([
            'status'=>true,
            'messages' => controllerTrans('variables.removed', $this->lang)
        ]);
    }

    public function destroyOneMultipleImg(Request $request)
    {
        $curr_img = $request->input('curr_img');
        $curr_id = $request->input('curr_id');
        $uploadPath = $request->input('uploadPath');

        if(is_null($uploadPath) || ( is_null($curr_id) && is_null($curr_img)))
            return response()->json([
                'status'=>false,
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)]
            ]);

        if ($uploadPath == 'brand')
            DB::table('goods_brand_images')
                ->where('id', $curr_id)
                ->delete();
        else
            DB::table($uploadPath . '_images')
                ->where('id', $curr_id)
                ->delete();

        if(!is_null($curr_img)) {
            $destinationPath = 'upfiles/' . $uploadPath;

            if (File::exists($destinationPath . '/s/' . $curr_img))
                File::delete($destinationPath . '/s/' . $curr_img);

            if (File::exists($destinationPath . '/m/' . $curr_img))
                File::delete($destinationPath . '/m/' . $curr_img);

            if (File::exists($destinationPath . '/' . $curr_img))
                File::delete($destinationPath . '/' . $curr_img);

        }

        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.removed', $this->lang)]
        ]);
    }

    public function activateOneImg(Request $request)
    {
        $active = $request->input('active');
        $curr_id = $request->input('curr_id');
        $uploadPath = $request->input('uploadPath');

        if(is_null($uploadPath) || is_null($curr_id))
            return response()->json([
                'status'=>false,
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)]
            ]);

        if($active == 1) {
            $active = 0;
            $msg = controllerTrans('variables.element_is_inactive', $this->lang, ['name' => '']);
        }
        else {
            $active = 1;
            $msg = controllerTrans('variables.element_is_active', $this->lang, ['name' => '']);
        }

        DB::table($uploadPath . '_images')
            ->where('id', $curr_id)
            ->update(['active' => $active]);

        return response()->json([
            'status' => true,
            'messages' => [$msg]
        ]);
    }

    public function uploadGallery(Request $request){
        $uploadPath = 'gallery';
        foreach($request->file() as $singleFile){
            foreach($singleFile as $file){
                $extension = $file->getClientOriginalExtension();
                $fileName = md5(time()) . rand(11111111,99999999).'.'.$extension;
                switch(strtolower($file->getClientOriginalExtension())){
                    case 'jpg':
                    case 'png':
                    case 'svg':
                    case 'jpeg':{
                        $destinationPath = 'upfiles/'.$uploadPath;
                        break;
                    }
                    default : {
                        return response()->json([
                            'status'=>false,
                            'messages' => [controllerTrans('variables.invalid_img_format', $this->lang)]
                        ]);
                        break;
                    }
                }

                $file->move($destinationPath, $fileName);

                if(!File::exists($destinationPath.'/s')){
                    File::makeDirectory($destinationPath.'/s');
                }
                if(!File::exists($destinationPath.'/m')){
                    File::makeDirectory($destinationPath.'/m');
                }

                CreateImageManipulator($uploadPath, $destinationPath.'/m/', $fileName, 350, 264);
                CreateImageManipulator($uploadPath, $destinationPath.'/s/', $fileName, 90, 90);

//                watermark($destinationPath.'/'.$fileName, asset('/admin-assets/img/watermark.png'), $destinationPath.'/'.$fileName);

                $maxPosition = GetMaxPosition('goods_foto');
                $data = [
                    'goods_item_id' => $request->input('gallery-id'),
                    'img' => $fileName,
                    'position' => $maxPosition + 1,
                    'active' => 1
                ];

                GoodsPhoto::create($data);
            }
        }
        return response()->json([
            'status' => true,
            'messages' => ['Save'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'productionsphoto/'.$request->input('gallery-id'))
        ]);
    }

    public function uploadGalleryPhoto(Request $request){
        $uploadPath = 'galleryItems';
        foreach($request->file() as $singleFile){
            foreach($singleFile as $file){


                $extension = $file->getClientOriginalExtension(); // getting image extension
                $fileName = md5(time()) . rand(11111111,99999999).'.'.$extension; // renameing image
                $original_name = $file->getClientOriginalName();

                switch(strtolower($file->getClientOriginalExtension())){
                    case 'jpg':
                    case 'svg':
                    case 'png':
                    case 'jpeg':{
                        $destinationPath = 'upfiles/'.$uploadPath;
                        break;
                    }
                    default : {
                        return response()->json([
                            'status'=>false,
                            'messages' => [controllerTrans('variables.invalid_img_format', $this->lang)]
                        ]);
                        break;
                    }
                }

                $file->move($destinationPath, $fileName);

                if(!File::exists($destinationPath.'/s')){
                    File::makeDirectory($destinationPath.'/s');
                }
                if(!File::exists($destinationPath.'/m')){
                    File::makeDirectory($destinationPath.'/m');
                }

                resizeIMGbyMaxSize($uploadPath, $destinationPath.'/s/', $fileName, 210);
                resizeIMGbyMaxSize($uploadPath, $destinationPath.'/m/', $fileName, 275, 155);
//
//                if(!is_null($request->input('height')) && !is_null($request->input('width')))
//	                CreateImageManipulator($uploadPath, $destinationPath.'/m/', $fileName, $request->input('width'), $request->input('width'), true);
//                else
//                    CreateImageManipulator($uploadPath, $destinationPath.'/m/', $fileName, 270, 273, true);


                $maxPosition = GetMaxPosition('gallery_item_id');

                $data = [
                    'gallery_subject_id' => $request->input('gallery-id'),
                    'img' => $fileName,
                    'position' => $maxPosition + 1,
                    'active' => 1,
                    'deleted' => 0,
                    'type' => 'photo',
                    'youtube_id' => '',
                    'youtube_link' => '',
                    'alias' => Str::slug(pathinfo($fileName, PATHINFO_FILENAME)),
                    'show_on_main' => 0
                ];

                $gallery_item_id = GalleryItemId::create($data);

                $data = [
                    'gallery_item_id' => $gallery_item_id->id,
                    'lang_id' => $this->lang_id,
                    'name' => pathinfo($original_name, PATHINFO_FILENAME)
                ];

                GalleryItem::create($data);
            }
        }
        return response()->json([
            'status' => true,
            'messages' => ['Save'],
            'redirect' => urlForLanguage($this->lang()['lang'], 'itemsphoto')
        ]);
    }
}
