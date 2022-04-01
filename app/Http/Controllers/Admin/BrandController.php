<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandId;
use App\Models\BrandImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{

    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index(Request $request)
    {
        $view = 'admin.brand.brand-list';

        $brand_list = BrandId::where('deleted', 0)
            /*->join('goods_brand', 'goods_brand.brand_id', '=', 'goods_brand_id.id')
            ->select('*', 'goods_brand_id.id as id')*/
            ->orderBy('position', 'asc')
            ->get();

        $brand_elements = [];
        if ($brand_list) {
            foreach ($brand_list as $key => $brand_id_element) {
                $brand_elements[$key] = Brand::where('goods_brand_id', $brand_id_element->id)
                    ->first();
            }
        }

        $brand_elements = array_filter($brand_elements, 'strlen');

        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition(Request $request)
    {
        $neworder = $request->input('neworder');

        $i = 0;
        $neworder = explode("&", $neworder);
        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            $i++;

            if (!empty($id)) {
                BrandId::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    public function cartItems()
    {
        $view = 'admin.brand.brand-cart';

        /*$deleted_brand_list = BrandId::where('deleted', 1)
            ->where('active', 0)
            ->get();*/

        $deleted_brand_list = BrandId::where('deleted', 1)
            ->where('active', 0)
            ->join('goods_brand', 'goods_brand.goods_brand_id', '=', 'goods_brand_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_brand_id.id as id')
            ->orderBy('position', 'asc')
            ->get();

        return view($view, get_defined_vars());
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = BrandId::join('goods_brand', 'goods_brand.goods_brand_id', '=', 'goods_brand_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_brand_id.id as id')
            ->findOrFail($id);

        if (!is_null($element_id))
            $element_name = $element_id->name;
        else
            return response()->json([
                'status' => false,
                'type' => 'error',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)]
            ]);

        if ($active == 1) {
            $change_active = 0;
            $msg = controllerTrans('variables.element_is_inactive', $this->lang, ['name' => $element_name]);
        } else {
            $change_active = 1;
            $msg = controllerTrans('variables.element_is_active', $this->lang, ['name' => $element_name]);
        }

        BrandId::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);
    }


    // ajax response for img position
    public function changeImgPosition(Request $request)
    {
        $newOrder = $request->input('newOrder');

        $i = 0;
        foreach ($newOrder as $k => $v) {
            $id = $v;
            $i++;

            if (!empty($id)) {
                BrandImages::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    public function createItem()
    {
        $view = 'admin.brand.create-brand';

        $modules_name = $this->menu()['modules_name'];

        return view($view, get_defined_vars());
    }

    public function editItem($id, $lang_id)
    {
        $view = 'admin.brand.edit-brand';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $brand_without_lang = Brand::where('goods_brand_id', $id)->first();

        if (is_null($brand_without_lang)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $brand_elems = Brand::where('lang_id', $lang_id)
            ->where('goods_brand_id', $brand_without_lang->goods_brand_id)
            ->first();

        if (!is_null($brand_without_lang)) {
            $brand_id = BrandId::where('id', $brand_without_lang->goods_brand_id)
                ->first();
        } elseif (!is_null($brand_elems)) {
            $brand_id = BrandId::where('id', $brand_elems->goods_brand_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function save(Request $request, $id, $lang_id)
    {

        if (is_null($id)) {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_brand_id',
            ]);
        } else {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required',
            ]);
        }

        if ($item->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $array_img = [];
        if (!is_null($request->input('file')) && !empty($request->input('file'))) {
            foreach ($request->input('file') as $item) {
                if (!is_null($item))
                    $array_img[] = basename($item);
            }
        }

        $maxPosition = GetMaxPosition('goods_brand_id');

        if ($id) {
            $currentPosition = GetPosition('goods_brand_id', $id);
            $position = $currentPosition;
        } else {
            $position = $maxPosition + 1;
        }

//        Check if lang exist
        if (checkIfLangExist(request()->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        $brand_id = BrandId::updateOrCreate(['id' => $id], [
            'position' => $position,
            'alias' => request()->input('alias'),
        ]);

        $brand_id->itemByLang()->updateOrCreate([
            'goods_brand_id' => $brand_id->id,
            'lang_id' => request()->input('lang'),
        ], [
            'name' => request()->input('name'),
            'body' => request()->input('body'),
            'link' => request()->input('link'),
            'meta_title' => request()->input('meta_title'),
            'meta_keywords' => request()->input('meta_keywords'),
            'meta_description' => request()->input('meta_description'),
        ]);
        $brand_id->push();


        $exist_brand_images = BrandImages::where('goods_brand_id', $brand_id->id)
            ->whereIn('img', $array_img)
            ->pluck('img')
            ->toArray();

        if ($exist_brand_images && !is_null($request->input('file')) && !empty($request->input('file')) && !empty($array_img)) {

            if (!is_array($exist_brand_images)) $exist_brand_images = [];
            if (!is_array($array_img)) $array_img = [];

            $img_arr_diff = array_diff($array_img, $exist_brand_images);

            if (!empty($img_arr_diff)) {
                foreach (array_reverse($img_arr_diff) as $item) {
                    $maxImgPosition = GetMaxPosition('goods_brand_images');
                    $img = basename($item);

                    $brand_img = new BrandImages();
                    $brand_img->goods_brand_id = $brand_id->id;
                    $brand_img->img = $img;
                    $brand_img->active = 1;
                    $brand_img->position = $maxImgPosition + 1;
                    $brand_img->save();
                }
            }
        } else {
            if (!is_null($request->input('file')) && !empty($request->input('file')) && !empty($array_img)) {
                foreach (array_reverse($request->input('file')) as $item) {
                    $maxImgPosition = GetMaxPosition('goods_brand_images');
                    $img = basename($item);

                    $brand_img = new BrandImages();
                    $brand_img->goods_brand_id = $brand_id->id;
                    $brand_img->img = $img;
                    $brand_img->active = 1;
                    $brand_img->position = $maxImgPosition + 1;
                    $brand_img->save();
                }
            }
        }

        if (is_null($id)) {
            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.save', $this->lang)],
                'redirect' => urlForFunctionLanguage($this->lang, '')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'edititem/' . $id . '/' . $lang_id)
        ]);

    }

    public function destroyBrandToCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $brand_item_elems_id = BrandId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$brand_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($brand_item_elems_id as $one_brand_item_elems_id) {

                    $brand_name = GetNameByLang($one_brand_item_elems_id->id, $this->lang_id, 'Brand', 'goods_brand_id');

                    if ($one_brand_item_elems_id->deleted == 0) {

                        $cart_message .= $brand_name . ', ';

                        BrandId::where('id', $one_brand_item_elems_id->id)
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

    public function destroyBrandFromCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $brand_item_elems_id = BrandId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$brand_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($brand_item_elems_id as $one_brand_item_elems_id) {

                    $brand_name = GetNameByLang($one_brand_item_elems_id->id, $this->lang_id, 'Brand', 'goods_brand_id');

                    if ($one_brand_item_elems_id->deleted == 1 && $one_brand_item_elems_id->active == 0) {

                        $brand_images = $one_brand_item_elems_id->moduleMultipleImg;

                        if (!is_null($brand_images) && !$brand_images->isEmpty()) {
                            foreach ($brand_images as $brand_image) {
                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $brand_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $brand_image->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $brand_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $brand_image->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $brand_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $brand_image->img);
                            }
                        }

                        $del_message .= $brand_name . ', ';

                        BrandId::destroy($one_brand_item_elems_id->id);

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

    public function restoreBrand(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $brand_item_elems_id = BrandId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$brand_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($brand_item_elems_id as $one_brand_item_elems_id) {

                    $brand_name = GetNameByLang($one_brand_item_elems_id->id, $this->lang_id, 'Brand', 'goods_brand_id');

                    if ($one_brand_item_elems_id->restored == 0) {

                        $cart_message .= $brand_name . ', ';

                        BrandId::where('id', $one_brand_item_elems_id->id)
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


