<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerTop;
use App\Models\BannerTopId;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{

    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index()
    {
        $view = 'admin.slider.sliders-list';

        $lang_id = $this->lang_id;
        $slider_list_ids = BannerTopId::where('deleted', 0)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $banner_list = [];
        foreach($slider_list_ids as $key => $one_slider_ids){
            $banner_list[$key] = BannerTop::with('lang')
                ->where('banner_top_id' ,$one_slider_ids->id)
                ->first();
        }
        //Remove all null values --start
        $banner_list = array_filter( $banner_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function cartItems()
    {
        $view = 'admin.slider.slider-cart';

        $lang_id = $this->lang_id;

        $deleted_banner_id = BannerTopId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_banner_list = [];

        foreach($deleted_banner_id as $key => $one_deleted_banner_id){
            $deleted_banner_list[$key] = BannerTop::where('banner_top_id', $one_deleted_banner_id->id)
                ->first();
        }

        $deleted_banner_list = array_filter( $deleted_banner_list, 'strlen' );
        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition(Request $request)
    {
        $neworder = $request->input('neworder');

        $i = 0;
        $neworder = explode("&", $neworder);
        foreach ($neworder as $k=>$v) {
            $id = str_replace("tablelistsorter[]=","", $v);
            $i++;

            if(!empty($id)){
                BannerTopId::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for img position
    public function changeImgPosition()
    {
        return response()->json();
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = BannerTopId::findOrFail($id);

        if(!is_null($element_id))
            $element_name = GetNameByLang($element_id->id, $this->lang_id, 'BannerTop', 'banner_top_id');
        else
            return response()->json([
                'status' => false,
                'type' => 'error',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)]
            ]);

        if($active == 1) {
            $change_active = 0;
            $msg = controllerTrans('variables.element_is_inactive', $this->lang, ['name' => $element_name]);
        }
        else{
            $change_active = 1;
            $msg = controllerTrans('variables.element_is_active', $this->lang, ['name' => $element_name]);
        }

        BannerTopId::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);
    }

    public function createItem()
    {
        $view = 'admin.slider.create-slider';
        $modules_name = $this->menu()['modules_name'];

        return view($view, get_defined_vars());
    }

    public function editItem($id, $lang_id)
    {
        $view = 'admin.slider.edit-slider';

        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$this->lang.'/back/'.$modules_name->modulesId->alias;

        $banner_top_without_lang = BannerTop::where('banner_top_id', $id)
            ->first();

        if(is_null($banner_top_without_lang)){
            return App::abort(503, 'Unauthorized action.');
        }

        $banner_top = BannerTop::where('banner_top_id', $banner_top_without_lang->banner_top_id)
            ->where('lang_id', $lang_id)
            ->first();

        if(!is_null($banner_top_without_lang)){
            $banner_top_id = BannerTopId::where('id', $banner_top_without_lang->banner_top_id)
                ->first();
        }
        elseif(!is_null($banner_top)){
            $banner_top_id = BannerTopId::where('id', $banner_top->banner_top_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function save(Request $request, $id, $lang_id)
    {

        $item = Validator::make($request->all(), [
            'name' => 'required',
//            'link' => 'required',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $active = $request->input('active') == 'on' ? 1 : 0;
        $maxPosition = GetMaxPosition('banner_top_id');
        $img = basename($request->input('file')[0]);

	    if($id){
		    $currentPosition = GetPosition('banner_top_id', $id);
		    $position = $currentPosition;
	    }else{
		    $position = $maxPosition + 1 ;
	    }

//        Check if lang exist
        if(checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        $banner_top_id = BannerTopId::updateOrCreate(['id'=> $id],[
            'position' => $position,
            'active' => $active,
            'deleted' => 0,
        ]);

        $banner_top_id->itemByLang()->updateOrCreate([
            'banner_top_id' => $banner_top_id->id,
            'lang_id' => $request->input('lang'),
        ],[
            'name' => $request->input('name'),
            'body' => $request->input('body'),
            'link' => $request->input('link'),
            'link_name' => $request->input('link_name'),
            'img' => $img,
        ]);
        $banner_top_id->push();

        if(is_null($id)){
            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.save', $this->lang)],
                'redirect' => urlForFunctionLanguage($this->lang, '')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'edititem/'.$id.'/'.$lang_id)
        ]);

    }

    public function destroyBannerFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $banner_item_elems_id = BannerTopId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$banner_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($banner_item_elems_id as $one_banner_item_elems_id) {

                    $banner_item_elems = BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($banner_item_elems)){
                        $banner_item_elems = BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)
                            ->first();
                    }

                    if ($one_banner_item_elems_id->deleted == 1 && $one_banner_item_elems_id->active == 0) {

                        $banner_item_elems_for_img = BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)
                            ->get();

                        if(!$banner_item_elems_for_img->isEmpty()) {
                            foreach ($banner_item_elems_for_img as $item) {
                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $item->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $item->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $item->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $item->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $item->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $item->img);
                            }
                        }

                        $del_message .= $banner_item_elems->name . ', ';

                        BannerTopId::destroy($one_banner_item_elems_id->id);
                        BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)->delete();

                    }

                }

                if (!empty($del_message)) {
                    $del_message = substr($del_message, 0, -2) . '<br />' . controllerTrans('variables.success_deleted', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'del_messages' => $del_message,
                    'deleted_elements' => $deleted_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }

    }

    public function destroyBannerToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $banner_item_elems_id = BannerTopId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$banner_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($banner_item_elems_id as $one_banner_item_elems_id) {

                    $banner_item_elems = BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($banner_item_elems)){
                        $banner_item_elems = BannerTop::where('banner_top_id', $one_banner_item_elems_id->id)
                            ->first();
                    }


                    if ($one_banner_item_elems_id->deleted == 0) {

                        $cart_message .= $banner_item_elems->name . ', ';

                        BannerTopId::where('id', $one_banner_item_elems_id->id)
                            ->update(['active' => 0, 'deleted' => 1]);
                    }
                }

                if (!empty($cart_message)) {
                    $cart_message = substr($cart_message, 0, -2) . '<br />' . controllerTrans('variables.success_added_cart', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'cart_messages' => $cart_message,
                    'deleted_elements' => $deleted_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function restoreBanner(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $slider_item_elems_id = BannerTopId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$slider_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($slider_item_elems_id as $one_slider_item_elems_id) {

                    $slider_name = GetNameByLang($one_slider_item_elems_id->id, $this->lang_id, 'BannerTop', 'banner_top_id');

                    if ($one_slider_item_elems_id->restored == 0) {

                        $cart_message .= $slider_name . ', ';

                        BannerTopId::where('id', $one_slider_item_elems_id->id)
                            ->update(['active' => 1, 'deleted' => 0]);
                    }
                }

                if (!empty($cart_message)) {
                    $cart_message = substr($cart_message, 0, -2) . '<br />' . controllerTrans('variables.success_restored', $this->lang);
                }

                return response()->json([
                    'status' => true,
                    'cart_messages' => $cart_message,
                    'restored_elements' => $restored_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }

    /**
     * return to another url, if method membersList does not exist
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function membersList()
    {
        return redirect(urlForFunctionLanguage($this->lang, ''));
    }

}


