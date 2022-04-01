<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandId;					   
use App\Models\GoodsColors;
use App\Models\GoodsColorsId;
use App\Models\GoodsItemsColors;
use App\Models\Promotions;
use App\Models\PromotionsId;
use App\Models\GoodsImages;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\GoodsItemModules;
use App\Models\GoodsItemModulesId;
use App\Models\GoodsMeasure;
use App\Models\GoodsMeasureId;
use App\Models\GoodsMeasureList;
use App\Models\GoodsParametr;
use App\Models\GoodsParametrId;
use App\Models\GoodsParametrItemId;
use App\Models\GoodsParametrItemMeasure;
use App\Models\GoodsParametrItemRsc;
use App\Models\GoodsParametrItemSimple;
use App\Models\GoodsParametrValue;
use App\Models\GoodsParametrValueId;
use App\Models\GoodsSubject;
use App\Models\GoodsSubjectId;
use App\Models\GoodsPhoto;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class GoodsController extends Controller
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
        $view = 'admin.goods.goods-list';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $goods_subject_id_list = GoodsSubjectId::where('deleted', 0)
            ->where('p_id', 0)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $goods_subject_list = [];
        foreach ($goods_subject_id_list as $key => $one_goods_subject_id_list) {
            $goods_subject_list[$key] = GoodsSubject::where('goods_subject_id', $one_goods_subject_id_list->id)
                ->first();

        }

        //Remove all null values --start
        $goods_subject_list = array_filter($goods_subject_list, 'strlen');
        //Remove all null values --end

//        Filter
        $search_result = trim($request->input('search-key'));
//        Filter

        if (!is_null($search_result) && !empty($search_result)) {
            return $this->globalSearchObjects($search_result);
        }

        return view($view, get_defined_vars());
    }

    // ajax response for position
    public function changePosition(Request $request)
    {
        $neworder = $request->input('neworder');
        $action = $request->input('action');
        $i = 0;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {
            $id = str_replace("tablelistsorter[]=", "", $v);
            $i++;

            if (!empty($id)) {
                if ($action == 'item')
                    GoodsItemId::where('id', $id)->update(['position' => $i]);
                elseif ($action == 'subject')
                    GoodsSubjectId::where('id', $id)->update(['position' => $i]);
                elseif ($action == 'gallery')
                    GoodsPhoto::where('id', $id)->update(['position' => $i]);
                elseif ($action == 'parameter')
                    GoodsParametrId::where('id', $id)->update(['position' => $i]);
            }
        }
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
                GoodsImages::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');
        $action = $request->input('action');

        if ($action == 'item')
            $element_id = GoodsItemId::findOrFail($id);
        elseif ($action == 'subject')
            $element_id = GoodsSubjectId::findOrFail($id);
        elseif ($action == 'gallery')
            $element_id = GoodsPhoto::findOrFail($id);
        elseif ($action == 'parameter')
            $element_id = GoodsParametrId::findOrFail($id);
        else
            $element_id = '';

        if (!is_null($element_id)) {
            if ($action == 'item')
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'GoodsItem', 'goods_item_id');
            elseif ($action == 'subject')
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'GoodsSubject', 'goods_subject_id');
            elseif ($action == 'parameter')
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'GoodsParametr', 'goods_parametr_id');
            else
                $element_name = '';
        } else
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

        if ($action == 'item')
            GoodsItemId::where('id', $id)->update(['active' => $change_active]);
        elseif ($action == 'subject')
            GoodsSubjectId::where('id', $id)->update(['active' => $change_active]);
        elseif ($action == 'gallery')
            GoodsPhoto::where('id', $id)->update(['active' => $change_active]);
        elseif ($action == 'parameter')
            GoodsParametrId::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);
    }

    public function createGoodsSubject()
    {
        $view = 'admin.goods.create-goods-subject';

        $modules_name = $this->menu()['modules_name'];

        $curr_page_id = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();

        if (!is_null($curr_page_id)) {
            $curr_page_id = $curr_page_id->id;
        } else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());

    }

    public function editGoodsSubject(Request $request, $id, $lang_id)
    {
        $view = 'admin.goods.edit-goods-subject';
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $goods_without_lang = GoodsSubject::where('goods_subject_id', $id)->first();

        if (is_null($goods_without_lang)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $goods_elems = GoodsSubject::where('lang_id', $lang_id)
            ->where('goods_subject_id', $goods_without_lang->goods_subject_id)
            ->first();

        $goods_subject_id = '';

        if (!is_null($goods_without_lang)) {
            $goods_subject_id = GoodsSubjectId::where('id', $goods_without_lang->goods_subject_id)
                ->first();
        } elseif (!is_null($goods_elems)) {
            $goods_subject_id = GoodsSubjectId::where('id', $goods_elems->goods_subject_id)
                ->first();
        }

        if (!empty($goods_subject_id->img))
            $category_list = explode(';', $goods_subject_id->img);
        else
            $category_list = '';

        return view($view, get_defined_vars());
    }

    public function saveSubject(Request $request, $id, $lang_id)
    {
        if (is_null($id)) {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_subject_id'

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

        $maxPosition = GetMaxPosition('goods_subject_id');
        $level = GetLevel($request->input('p_id'), 'goods_subject_id');

        if ($id) {
            $currentPosition = GetPosition('goods_subject_id', $id);
            $position = $currentPosition;
        } else {
            $position = $maxPosition + 1;
        }

        //Check if lang exist
        if (checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        //uploadAdminImages($request->file('files'), $request->input('uploaded_images'), 'goods', 0, $request->input('hidden_img'));

        $goods_subject_id = GoodsSubjectId::updateOrCreate(['id' => $id], [
            'p_id' => $request->input('p_id'),
            'level' => $level + 1,
            'alias' => $request->input('alias'),
            'position' => $position,
            'img' => uploadAdminImages($request->file('files'), $request->input('uploaded_images'), 'goods', 0, $request->input('hidden_img')),
            'active' => 1,
            'deleted' => 0,
        ]);

        $goods_subject_id->itemByLang()->updateOrCreate([
            'goods_subject_id' => $goods_subject_id->id,
            'lang_id' => $request->input('lang'),
        ], [
            'name' => $request->input('name'),
            'body' => $request->input('body'),
            'page_title' => $request->input('page_title'),
            'h1_title' => $request->input('h1_title'),
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
        ]);

        $goods_subject_id->push();

        if (is_null($id)) {
            if ($goods_subject_id->level == 1) {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, '')
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, GetParentAlias($goods_subject_id->id, 'goods_subject_id') . '/memberslist')
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editgoodssubject/' . $id . '/' . $lang_id)
        ]);
    }

    public function goodsSubjectCart()
    {
        $view = 'admin.goods.subject-cart';

        $lang_id = $this->lang_id;

        $deleted_elems_by_alias = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();

        if (is_null($deleted_elems_by_alias)) {
            $deleted_subject_id_elems = GoodsSubjectId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', 0)
                ->get();
        } else {
            $deleted_subject_id_elems = GoodsSubjectId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', $deleted_elems_by_alias->id)
                ->get();
        }

        $deleted_subject_elems = [];
        foreach ($deleted_subject_id_elems as $key => $one_deleted_subject_elem) {
            $deleted_subject_elems[$key] = GoodsSubject::where('goods_subject_id', $one_deleted_subject_elem->id)
                ->first();
        }

        $deleted_subject_elems = array_filter($deleted_subject_elems, 'strlen');

        return view($view, get_defined_vars());

    }

    public function destroyGoodsSubjectFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $subject_elems_id = GoodsSubjectId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$subject_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($subject_elems_id as $one_subject_elems_id) {

                    $subject_elems = GoodsSubject::where('goods_subject_id', $one_subject_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (is_null($subject_elems)) {
                        $subject_elems = GoodsSubject::where('goods_subject_id', $one_subject_elems_id->id)
                            ->first();
                    }

                    if ($one_subject_elems_id->deleted == 1 && $one_subject_elems_id->active == 0) {

                        $goods_images = $one_subject_elems_id->moduleMultipleImg;

                        if (!is_null($goods_images) && !$goods_images->isEmpty()) {
                            foreach ($goods_images as $goods_image) {
                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $goods_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $goods_image->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $goods_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $goods_image->img);

                                if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $goods_image->img))
                                    File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $goods_image->img);
                            }
                        }

                        $del_message .= $subject_elems->name . ', ';

                        GoodsSubjectId::destroy($one_subject_elems_id->id);
                        GoodsSubject::where('goods_subject_id', $one_subject_elems_id->id)->delete();

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

    public function destroyGoodsSubjectToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $subject_elems_id = GoodsSubjectId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$subject_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($subject_elems_id as $one_subject_elems_id) {

                    $subject_elems = GoodsSubject::where('goods_subject_id', $one_subject_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (is_null($subject_elems)) {
                        $subject_elems = GoodsSubject::where('goods_subject_id', $one_subject_elems_id->id)
                            ->first();

                    }

                    if ($one_subject_elems_id->deleted == 0) {

                        $cart_message .= $subject_elems->name . ', ';

                        GoodsSubjectId::where('id', $one_subject_elems_id->id)
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

    public function restoreGoodsSubject(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $goods_item_elems_id = GoodsSubjectId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {

                    $goods_name = GetNameByLang($one_goods_item_elems_id->id, $this->lang_id, 'GoodsSubject', 'goods_subject_id');

                    if ($one_goods_item_elems_id->restored == 0) {

                        $cart_message .= $goods_name . ', ';

                        GoodsSubjectId::where('id', $one_goods_item_elems_id->id)
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

    public function membersList(Request $request)
    {
        $view = 'admin.goods.child-list';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        //        Filter
        $search_result = trim($request->input('search-key'));
        //        Filter

        if (!is_null($search_result) && !empty($search_result)) {
            return $this->globalSearchObjects($search_result);
        }

        $goods_list_id = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();

        if (is_null($goods_list_id)) {
            return App::abort(503, 'Unauthorized action.');
        }

        if (CheckIfSubjectHasOtherItems('goods', $goods_list_id->id)->isEmpty()) {
            $child_goods_list_id = GoodsSubjectId::where('p_id', $goods_list_id->id)
                ->where('deleted', 0)
                ->orderBy('position', 'asc')
                ->get();
            $child_goods_list = [];
            foreach ($child_goods_list_id as $key => $one_goods_elem) {
                $child_goods_list[$key] = GoodsSubject::where('goods_subject_id', $one_goods_elem->id)
                    ->first();
            }

            $child_goods_list = array_filter($child_goods_list, 'strlen');
            $child_goods_item_list = [];

        } else {
            if ($goods_list_id->id != 36)
                $child_goods_item_list_id = GoodsItemId::where('goods_subject_id', $goods_list_id->id)
                    ->orWhereRaw("(other_goods_subject_id LIKE '%" . $goods_list_id->id . "%')")
                    ->where('deleted', 0)
                    ->orderBy('position', 'asc')
                    ->get();
            else
                $child_goods_item_list_id = GoodsItemId::where('goods_subject_id', $goods_list_id->id)
                    ->orWhereRaw("(other_goods_subject_id LIKE '%" . $goods_list_id->id . "%')")
                    ->where('deleted', 0)
                    ->orderBy('position', 'asc')
                    ->paginate(150);


            $child_goods_item_list = [];
            foreach ($child_goods_item_list_id as $key => $one_goods_elem) {
                $child_goods_item_list[$key] = GoodsItem::where('goods_item_id', $one_goods_elem->id)
                    ->first();
            }

            $child_goods_item_list = array_filter($child_goods_item_list, 'strlen');

            $child_goods_list = [];
        }
//        $inputs = $request->input('destroy_elements');

       /* $color_from_subject = GoodsColors::join('goods_colors_id', 'goods_colors.goods_colors_id', '=', 'goods_colors_id.id')
            ->whereRaw('goods_colors_id IN(SELECT DISTINCT goods_colors_id FROM goods_item_colors WHERE goods_item_id IN(SELECT id FROM goods_item_id WHERE goods_subject_id=' . $goods_list_id->id . '))')
            ->where('p_id', '>', 0)
            ->get();

        $parameter_value_id = GoodsParametrItemRsc::join('goods_parametr_item_id', 'goods_parametr_item_rsc.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
            ->WhereRaw('goods_item_id IN(Select id from goods_item_id where goods_subject_id=' . $goods_list_id->id . ')')
            ->get();*/


//        dd($child_goods_item_list);
        return view($view, get_defined_vars());

    }

    public function createGoodsItem()
    {
        $view = 'admin.goods.create-goods-item';
        $lang_id = $this->lang_id;

        $goods_subject_id = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();

        $goods_subject_protection_id = GoodsSubjectId::where('id', $goods_subject_id->p_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        $goods_parameters = [];

        if (!is_null($goods_subject_id)) {
            $curr_page_id = $goods_subject_id->id;

            $goods_parameters = GoodsParametrId::where('goods_subject_id', $curr_page_id)
                ->where('deleted', 0)
                ->where('active', 1)
                ->join('goods_parametr', 'goods_parametr.goods_parametr_id', '=', 'goods_parametr_id.id')
                ->where('lang_id', $lang_id)
                ->select('*', 'goods_parametr_id.id as id')
                ->orderBy('position', 'asc')
                ->get();

        } else {
            $curr_page_id = null;
            $goods_parameters = [];
        }

        /*if ($goods_subject_protection_id->alias == 'price') {
            $goods_protection_subject = GoodsSubjectId::where('alias', 'tehnologie-de-cultivare')
                ->where('deleted', 0)
                ->where('active', 1)
                ->first();

            $goods_protection_id = GoodsSubjectId::where('p_id', $goods_protection_subject->id)
                ->where('deleted', 0)
                ->where('active', 1)
                ->orderBy('position', 'asc')
                ->get();
            if (!$goods_protection_id->isEmpty()) {
                foreach ($goods_protection_id as $key => $one_goods) {

                    $goods_protection[$key] = GoodsSubject::where('goods_subject_id', $one_goods->id)
                        ->where('lang_id', $lang_id)
                        ->first();
                }
            }

        }*/        

        $brand = BrandId::where('active', 1)
            ->where('deleted', 0)
            ->join('goods_brand', 'goods_brand.goods_brand_id', '=', 'goods_brand_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_brand_id.id as id')
            ->orderBy('position', 'asc')
            ->get();

        /*$colors_list = GoodsColorsId::join('goods_colors', 'goods_colors_id.id', '=', 'goods_colors.goods_colors_id')
            ->select('goods_colors_id.id', 'name', 'img', 'lang_id')
            ->where('lang_id', $lang_id)
            ->where('p_id', '>', 0)
            ->get();*/

        /*$promotions_id = PromotionsId::where('active', 1)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();

        $promotions = [];
        if (!$promotions_id->isEmpty()) {
            foreach ($promotions_id as $key => $one_promotions_id) {
                $promotions[$key] = Promotions::where('promotions_id', $one_promotions_id->id)
                    ->where('lang_id', $lang_id)
                    ->first();
            }

            $promotions = array_filter($promotions);
        }*/

        $all_goods_subjects = [];
        GetEndSubjectsList('goods_subject_id', 0, $lang_id, 1, 0, $all_goods_subjects);

        return view($view, get_defined_vars());
    }

    public function editGoodsItem($id, $lang_id)
    {
        $view = 'admin.goods.edit-goods-item';
        //$lang_id = $this->lang_id;

        $modules_name = $this->menu()['modules_name'];
        $current_url = '/' . $this->lang . '/back/' . $modules_name->modulesId->alias;

        /*$goods_protection_children_id = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();

        $goods_subject_protection_id = GoodsSubjectId::where('id', $goods_protection_children_id->p_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();*/


        $goods_without_lang = GoodsItem::where('goods_item_id', $id)->first();

        if (is_null($goods_without_lang)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $goods_elems = GoodsItem::where('lang_id', $lang_id)
            ->where('goods_item_id', $goods_without_lang->goods_item_id)
            ->first();

        if (!is_null($goods_without_lang)) {
            $goods_item_id = GoodsItemId::where('id', $goods_without_lang->goods_item_id)
                ->first();
        } elseif (!is_null($goods_elems)) {
            $goods_item_id = GoodsItemId::where('id', $goods_elems->goods_item_id)
                ->first();
        }

        $goods_subject_id = GoodsSubjectId::where('id', $goods_item_id->goods_subject_id)->where('alias', request()->segment(4))->first();

        if (is_null($goods_subject_id)) {
            $array_subjects = explode(',', $goods_item_id->other_goods_subject_id);

            $goods_subject_id = GoodsSubjectId::whereIN('id', $array_subjects)->where('alias', request()->segment(4))->first();
        }

        $goods_parameters = [];

        if (!is_null($goods_subject_id)) {
            $goods_subject_id = $goods_subject_id->id;

            $goods_parameters = GoodsParametrId::where('goods_subject_id', $goods_subject_id)
                ->where('deleted', 0)
                ->where('active', 1)
                ->join('goods_parametr', 'goods_parametr.goods_parametr_id', '=', 'goods_parametr_id.id')
                ->where('lang_id', $lang_id)
                ->select('*', 'goods_parametr_id.id as id')
                ->orderBy('position', 'asc')
                ->get();

            /*if (!empty($goods_parameter_id)) {
                $goods_parameter = [];
                foreach ($goods_parameter_id as $key => $one_goods_parametr_id) {
                    $goods_parameter[$key] = GoodsParametr::where('goods_parametr_id', $one_goods_parametr_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();
                }

                $goods_parameter = array_filter($goods_parameter);
            } else {
                $goods_parameter = [];
            }*/

        } else {
            $goods_subject_id = null;
            $goods_parameters = [];
        }

        $brand = BrandId::where('active', 1)
            ->where('deleted', 0)
            ->join('goods_brand', 'goods_brand.goods_brand_id', '=', 'goods_brand_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_brand_id.id as id')
            ->orderBy('position', 'asc')
            ->get();


        $all_goods_subjects = [];
        GetEndSubjectsList('goods_subject_id', 0, $lang_id, 1, 0, $all_goods_subjects);

        $used_subjects = [];
        if (!empty($goods_item_id)) {
            $used_subjects = explode(',', $goods_item_id->other_goods_subject_id);
        }

        $current_subject_id = GoodsSubjectId::where('alias', request()->segment(4))->first();


        return view($view, get_defined_vars());
    }

    public function destroy(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 3, -2);
        // dd(($deleted_elements_id));
        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $brand_item_elems_id = GoodsColors::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$brand_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($brand_item_elems_id as $one_brand_item_elems_id) {

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

                        $del_message .= $one_brand_item_elems_id->name . ', ';

                        GoodsColors::destroy($one_brand_item_elems_id->id);

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

    public function saveItem(Request $request, $id, $lang_id)
    {
        if (is_null($id)) {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:goods_item_id',

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

        if (!empty($request->input('add_date'))) {
            $add_date = date('Y-m-d', strtotime($request->input('add_date')));
        } else {
            $add_date = date('Y-m-d');
        }

        if (checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        if (!is_null($request->input('youtube_id')))
            $youtube_id = $request->input('youtube_id');
        elseif (is_null($request->input('youtube_id')) && !is_null($request->input('youtube_link')))
            $youtube_id = $this->youtubeId($request->input('youtube_link'));
        else
            $youtube_id = null;


        $subjects_array = [];
        if (!empty($request->input('subject'))) {
            foreach ($request->input('subject') as $one_subject)
                array_push($subjects_array, $one_subject);
        }

        $subjects_array = implode(',', $subjects_array);

        if ($id) {
            $currentPosition = GetPosition('goods_item_id', $id);
            $position = $currentPosition;
        } else {
            $maxPosition = GetMaxPosition('goods_item_id');
            $position = $maxPosition + 1;
        }


        //Save and update goods item

        $goods_item_id = GoodsItemId::updateOrCreate(['id' => $id], [
            'goods_subject_id' => $request->input('p_id'),
/*            'brand_id' => $request->input('brand_id'),*/
            'position' => $position,
 /*           'other_goods_subject_id' => $subjects_array,*/
            'alias' => $request->input('alias'),
/*            'price' => $request->input('price'),
            'bonus_plus_item' => $request->input('bonus_plus_item'),
            'price_bonus' => $request->input('price_bonus'),
            'price_old' => $request->input('price_old'),
            'youtube_id' => $youtube_id,
            'youtube_link' => $request->input('youtube_link'),
            'show_on_main' => $request->input('show_on_main') == 'on' ? 1 : 0,
            'popular_element' => $request->input('popular_element') == 'on' ? 1 : 0,
            'new_element' => $request->input('new_element') == 'on' ? 1 : 0,
            'can_buy_by_bonus' => $request->input('can_buy_by_bonus') == 'on' ? 1 : 0,
            'in_stoc' => $request->input('in_stoc') == 'on' ? 1 : 0,*/
            'active' => 1,
            'deleted' => 0,
        ]);

        $goods_item_id->itemByLang()->updateOrCreate([
            'goods_item_id' => $goods_item_id->id,
            'lang_id' => $request->input('lang'),
        ], [
            'name' => $request->input('name'),
            'short_descr' => $request->input('short_descr'),
            'body' => $request->input('body'),
            'page_title' => $request->input('page_title'),
            'h1_title' => $request->input('h1_title'),
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
        ]);

        $goods_item_id->push();

        //Modules
        if (!empty($request->input('module_title')) || !empty($request->input('content'))) {

            foreach ($request->input('content') as $key => $one_content) {
                $maxPositionModule = GetMaxPosition('goods_item_modules_id');

                $goods_item_modules_id = GoodsItemModulesId::updateOrCreate(['id' => $id], [
                    'position' => $maxPositionModule + 1,
                    'goods_item_id' => $goods_item_id->id
                ]);

                $goods_item_modules_id->itemByLang()->updateOrCreate([
                    'goods_item_modules_id' => $goods_item_modules_id->id,
                    'lang_id' => $request->input('lang'),
                ], [
                    'name' => !is_null($request->input('module_title')[$key]) ? $request->input('module_title')[$key] : '',
                    'body' => !is_null($one_content) ? $one_content : '',
                ]);
            }
        }

        $parameter_p_id = $request->input('p_id');

        $goods_parameter_id = GoodsParametrId::where('goods_subject_id', $parameter_p_id)
            ->where('deleted', 0)
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->get();


        //Save and update goods parameters
        if (!empty($goods_parameter_id)) {

            foreach ($goods_parameter_id as $one_parameter_id) {

                $goods_parametr_item_id = GoodsParametrItemId::updateOrCreate([
                    'goods_item_id' => $goods_item_id->id,
                    'goods_parametr_id' => $one_parameter_id->id
                ], []);

                switch ($one_parameter_id->parametr_type) {
                    case 'textarea':

                        GoodsParametrItemSimple::updateOrCreate([
                            'goods_parametr_item_id' => $goods_parametr_item_id->id,
                            'lang_id' => $request->input('lang'),
                        ], [
                            'parametr_value' => $request->input('parametr_' . $one_parameter_id->id)['parametr_value'] ?? ''
                        ]);

                        break;

                    case 'input':
                        switch ($one_parameter_id->measure_type) {
                            case 'no_measure':

                                GoodsParametrItemSimple::updateOrCreate([
                                    'goods_parametr_item_id' => $goods_parametr_item_id->id,
                                    'lang_id' => $request->input('lang'),
                                ], [
                                    'parametr_value' => $request->input('parametr_' . $one_parameter_id->id)['parametr_value'] ?? ''
                                ]);

                                break;

                            case 'with_measure':

                                GoodsParametrItemMeasure::updateOrCreate([
                                    'goods_parametr_item_id' => $goods_parametr_item_id->id,
                                ], [
                                    'goods_measure_id' => 0,
                                    'parametr_value' => $request->input('parametr_' . $one_parameter_id->id)['parametr_value'] ?? ''
                                ]);

                                break;

                            case 'measure_list':

                                GoodsParametrItemMeasure::updateOrCreate([
                                    'goods_parametr_item_id' => $goods_parametr_item_id->id,
                                ], [
                                    'goods_measure_id' => $request->input('parametr_' . $one_parameter_id->id)['goods_measure_id'] ?? '',
                                    'parametr_value' => $request->input('parametr_' . $one_parameter_id->id)['parametr_value'] ?? ''
                                ]);

                                break;

                            default:
                                break;
                        }
                        break;

                    case 'radio':
                    case 'select':

                        GoodsParametrItemRsc::where('goods_parametr_item_id', $goods_parametr_item_id->id)->delete();

                        if (!empty($request->input('parametr_' . $one_parameter_id->id)['goods_parametr_value_id'])) {
                            GoodsParametrItemRsc::updateOrCreate([
                                'goods_parametr_item_id' => $goods_parametr_item_id->id,
                                'goods_parametr_value_id' => $request->input('parametr_' . $one_parameter_id->id)['goods_parametr_value_id'],
                            ], []);
                        }

                        break;

                    case 'checkbox':

                        GoodsParametrItemRsc::where('goods_parametr_item_id', $goods_parametr_item_id->id)->delete();

                        if (!empty($request->input('parametr_' . $one_parameter_id->id)['goods_parametr_value_id'])) {
                            foreach ($request->input('parametr_' . $one_parameter_id->id)['goods_parametr_value_id'] as $pv) {

                                GoodsParametrItemRsc::updateOrCreate([
                                    'goods_parametr_item_id' => $goods_parametr_item_id->id,
                                    'goods_parametr_value_id' => $pv,
                                ], []);
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        if (is_null($id)) {
            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.save', $this->lang)],
                'redirect' => urlForLanguage($this->lang, 'memberslist')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editgoodsitem/' . $id . '/' . $lang_id)
        ]);
    }

    public function goodsItemCart()
    {
        $view = 'admin.goods.item-cart';

        $lang_id = $this->lang_id;

        $deleted_elems_by_alias = GoodsSubjectId::where('alias', request()->segment(4))
            ->first();


        $deleted_item_id_elems = [];

        if (!is_null($deleted_elems_by_alias))
            $deleted_item_id_elems = GoodsItemId::where('deleted', 1)
                ->where('active', 0)
                ->where('goods_subject_id', $deleted_elems_by_alias->id)
                ->get();

        $deleted_item_elems = [];
        if (!empty($deleted_item_id_elems)) {
            foreach ($deleted_item_id_elems as $key => $one_deleted_item_elem) {
                $deleted_item_elems[$key] = GoodsItem::where('goods_item_id', $one_deleted_item_elem->id)
                    ->first();
            }
        }

        $deleted_item_elems = array_filter($deleted_item_elems, 'strlen');

        return view($view, get_defined_vars());
    }

    public function destroyGoodsItemFromCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $goods_item_elems_id = GoodsItemId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {

                    if ($one_goods_item_elems_id->deleted == 1 && $one_goods_item_elems_id->active == 0) {

                        $goods_photo = GoodsPhoto::where('goods_item_id', $one_goods_item_elems_id->id)->get();

                        if (!is_null($goods_photo)) {

                            foreach ($goods_photo as $one_goods_photo) {

                                if (File::exists('upfiles/gallery/s/' . $one_goods_photo->img))
                                    File::delete('upfiles/gallery/s/' . $one_goods_photo->img);

                                if (File::exists('upfiles/gallery/m/' . $one_goods_photo->img))
                                    File::delete('upfiles/gallery/m/' . $one_goods_photo->img);

                                if (File::exists('upfiles/gallery/' . $one_goods_photo->img))
                                    File::delete('upfiles/gallery/' . $one_goods_photo->img);
                            }
                        }

                        $goods_item_elems = GoodsItem::where('goods_item_id', $one_goods_item_elems_id->id)
                            ->first();

                        $del_message .= $goods_item_elems->name . ', ';

                        GoodsItemId::destroy($one_goods_item_elems_id->id);

                        GoodsItem::where('goods_item_id', $one_goods_item_elems_id->id)->delete();
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

    public function destroyGoodsItemToCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);

        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {

            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $goods_item_elems_id = GoodsItemId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {

                    if ($one_goods_item_elems_id->deleted == 0) {

                        $goods_item_elems = GoodsItem::where('goods_item_id', $one_goods_item_elems_id->id)
                            ->where('lang_id', $lang_id)
                            ->first();

                        $cart_message .= $goods_item_elems->name . ', ';

                        GoodsItemId::where('id', $one_goods_item_elems_id->id)
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

    public function restoreGoodsItem(Request $request)
    {
        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {

            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $goods_item_elems_id = GoodsItemId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {

                    $goods_name = GetNameByLang($one_goods_item_elems_id->id, $this->lang_id, 'GoodsItem', 'goods_item_id');

                    if ($one_goods_item_elems_id->restored == 0) {

                        $cart_message .= $goods_name . ', ';

                        GoodsItemId::where('id', $one_goods_item_elems_id->id)
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

    public function itemsPhoto($id)
    {
        $view = 'admin.goods.items-photo';
        $lang = $this->lang;

        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $goods_item = GoodsItem::where('goods_item_id', $id)->first();

        if (is_null($goods_item)) {
            return App::abort(503, 'Unauthorized action.');
        }

        if (!is_null($goods_item)) {
            $goods_item_id = GoodsItemId::where('id', $goods_item->goods_item_id)->first();

            $goods_photo = GoodsPhoto::where('goods_item_id', $goods_item->goods_item_id)
                ->orderBy('position', 'asc')
                ->get();
        }

        return view($view, get_defined_vars());
    }

    public function destroyGoodsPhoto(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);


        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $goods_photo = GoodsPhoto::whereIn('id', $deleted_elements_id_arr)
                ->get();

            if (!$goods_photo->isEmpty()) {
                foreach ($goods_photo as $one_goods_photo) {
                    if (File::exists('upfiles/gallery/s/' . $one_goods_photo->img))
                        File::delete('upfiles/gallery/s/' . $one_goods_photo->img);

                    if (File::exists('upfiles/gallery/m/' . $one_goods_photo->img))
                        File::delete('upfiles/gallery/m/' . $one_goods_photo->img);

                    if (File::exists('upfiles/gallery/' . $one_goods_photo->img))
                        File::delete('upfiles/gallery/' . $one_goods_photo->img);

                    GoodsPhoto::destroy($one_goods_photo->id);
                }

                $del_message = controllerTrans('variables.the_photos', $this->lang) . ' ' . controllerTrans('variables.success_deleted', $this->lang);

                return response()->json([
                    'status' => true,
                    'del_messages' => $del_message,
                    'deleted_elements' => $deleted_elements_id_arr
                ]);
            }

            return response()->json([
                'status' => false
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function goodsParameters($id)
    {
        $view = 'admin.parameters.parameters-list';

        $lang_id = $this->lang_id;
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $goods_subject_id = GoodsSubjectId::find($id);

        if (is_null($goods_subject_id)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $goods_parameter_id = GoodsParametrId::where('goods_subject_id', $id)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();

        if (!empty($goods_parameter_id)) {
            $goods_parameter = [];

            foreach ($goods_parameter_id as $key => $one_goods_parameter_id) {
                $goods_parameter[$key] = GoodsParametr::where('goods_parametr_id', $one_goods_parameter_id->id)
                    ->first();
            }

            $goods_parameter = array_filter($goods_parameter);
        } else {
            $goods_parameter = [];
        }

        return view($view, get_defined_vars());
    }

    public function createGoodsParameter()
    {
        $view = 'admin.parameters.create-parameter';
        $lang_id = $this->lang_id;

        $subject_id = (int)request()->segment(6);
        $goods_subject_id = GoodsSubjectId::findOrFail($subject_id);

        $measure_id = GoodsMeasureId::where('active', 1)
            ->orderBy('position', 'asc')
            ->get();

        $measure = [];
        foreach ($measure_id as $key => $one_measure_id) {
            $measure[$key] = GoodsMeasure::where('goods_measure_id', $one_measure_id->id)
                ->where('lang_id', $lang_id)
                ->first();
        }

        $measure = array_filter($measure);

        return view($view, get_defined_vars());
    }

    public function editGoodsParameter($id, $lang_id)
    {
        $view = 'admin.parameters.edit-parameter';
        //$lang_id = $this->lang_id;
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $goods_parameter_without_lang = GoodsParametr::where('goods_parametr_id', $id)->first();

        if (is_null($goods_parameter_without_lang)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $goods_parametr = GoodsParametr::where('lang_id', $lang_id)
            ->where('goods_parametr_id', $goods_parameter_without_lang->goods_parametr_id)
            ->first();

        if (!is_null($goods_parametr)) {
            $goods_parametr_id = GoodsParametrId::where('id', $goods_parametr->goods_parametr_id)
                ->first();
        } else {
            $goods_parametr_id = GoodsParametrId::where('id', $goods_parameter_without_lang->goods_parametr_id)
                ->first();
        }

        $goods_subject_id = GoodsSubjectId::where('id', $goods_parametr_id->goods_subject_id)->first();

        $measure_id = GoodsMeasureId::where('active', 1)
            ->orderBy('position', 'asc')
            ->get();
        $measure = [];

        foreach ($measure_id as $key => $one_measure_id) {
            $measure[$key] = GoodsMeasure::where('goods_measure_id', $one_measure_id->id)
                ->where('lang_id', $lang_id)
                ->first();
        }

        $measure = array_filter($measure);

        $measure_list = GoodsMeasureList::where('goods_parametr_id', $id)
            ->orderBy('position', 'asc')
            ->get();

        $goods_parameter_value_id = GoodsParametrValueId::where('goods_parametr_id', $id)
            ->orderBy('position', 'asc')
            ->get();

        if (!empty($goods_parameter_value_id)) {
            $goods_parameter_value = [];

            foreach ($goods_parameter_value_id as $key => $one_parameter_value_id) {
                $goods_parameter_value[$key] = GoodsParametrValue::where('goods_parametr_value_id', $one_parameter_value_id->id)
                    ->first();
            }

            $goods_parameter_value = array_filter($goods_parameter_value);
        } else {
            $goods_parameter_value = [];
        }

        return view($view, get_defined_vars());
    }

    public function saveGoodsParameter(Request $request, $id, $lang_id)
    {
        if (is_null($id)) {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required',
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

        $maxPosition = GetMaxPosition('goods_parametr_id');

        $parametr_type_value = $request->input('parametr_type_value');
        $goods_measure_list = $request->input('goods_measure_list');

        if (checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        if ($request->input('measure_type') == 'with_measure') {
            $goods_measure_id = $request->input('goods_measure_id');
        } else {
            $goods_measure_id = null;
        }

        if ($request->input('parametr_type') == 'textarea' || $request->input('parametr_type') == 'select' || $request->input('parametr_type') == 'radio' || $request->input('parametr_type') == 'checkbox') {
            $goods_measure_id = null;
            $measure_type = 'no_measure';
        } else {
            $measure_type = $request->input('measure_type');
        }

        //Save Parameters
        $parameter_id = GoodsParametrId::updateOrCreate(['id' => $id], [
            'goods_subject_id' => $request->input('goods_subject_id'),
            'measure_type' => $measure_type,
            'goods_measure_id' => $goods_measure_id,
            'parametr_type' => $request->input('parametr_type'),
            'alias' => $request->input('alias'),
            'position' => $maxPosition + 1,
            'active' => 1,
            'deleted' => 0,
            'show_in_list' => $request->input('show_in_list') == 'on' ? 1 : 0,
            'font_for_list' => $request->input('font_for_list') ?? null,
            'display_on_list_page' => $request->input('display_on_list_page') == 'on' ? 1 : 0,
            'start_open' => $request->input('start_open') == 'on' ? 1 : 0,
            'display_in_line' => $request->input('display_in_line') == 'on' ? 1 : 0,
            'for_basket' => $request->input('for_basket') == 'on' ? 1 : 0,
            'is_color' => $request->input('is_color') == 'on' ? 1 : 0,
        ]);

        $parameter_id->itemByLang()->updateOrCreate([
            'goods_parametr_id' => $parameter_id->id,
            'lang_id' => $request->input('lang'),
        ], [
            'name' => $request->input('name'),
            'body' => $request->input('body'),
        ]);

        $parameter_id->push();

        //Save Parameter Values
        if ($request->input('parametr_type') == 'select' || $request->input('parametr_type') == 'radio' || $request->input('parametr_type') == 'checkbox') {

            $i = 0;
            foreach ($parametr_type_value as $one_parameter_id => $one_parameter_val) {

                $goods_parameter_value_id = GoodsParametrValueId::updateOrCreate([
                    'id' => $one_parameter_id
                ], [
                    'goods_parametr_id' => $parameter_id->id,
                    'position' => $i,
                    'active' => 1
                ]);

                $i++;

                $goods_parameter_value_id->itemByLang()->updateOrCreate([
                    'goods_parametr_value_id' => $goods_parameter_value_id->id,
                    'lang_id' => $request->input('lang'),
                ], [
                    'name' => $one_parameter_val,
                ]);

                $goods_parameter_value_id->push();
            }
        }

        if ($request->input('measure_type') == 'measure_list') {

            GoodsMeasureList::where('goods_parametr_id', $parameter_id->id)->delete();

            $i = 0;
            foreach ($goods_measure_list as $one_goods_measure_id => $one_goods_measure_val) {
                GoodsMeasureList::updateOrCreate([
                    'id' => $one_goods_measure_id,
                ], [
                    'goods_parametr_id' => $parameter_id->id,
                    'goods_measure_id' => $one_goods_measure_val,
                    'position' => $i
                ]);

                $i++;
            }
        }

        if (is_null($id)) {
            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.save', $this->lang)],
                'redirect' => urlForLanguage($this->lang, 'goodsparameters/' . $request->input('goods_subject_id'))
            ]);
        }

        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editgoodsparameter/' . $id . '/' . $lang_id)
        ]);

    }

    public function goodsParameterCart()
    {
        $view = 'admin.parameters.parameter-cart';

        $lang_id = $this->lang_id;
        $parameter_subject_id = (int)request()->segment(6);

        $deleted_parameters = GoodsParametrId::where('deleted', 1)
            ->where('active', 0)
            ->where('goods_subject_id', $parameter_subject_id)
            ->get();

        return view($view, get_defined_vars());
    }

    public function destroyGoodsParameterFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $parameter_elems_id = GoodsParametrId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$parameter_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($parameter_elems_id as $one_banner_item_elems_id) {

                    $parameter_elems = GoodsParametr::where('goods_parametr_id', $one_banner_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (is_null($parameter_elems)) {
                        $parameter_elems = GoodsParametr::where('goods_parametr_id', $one_banner_item_elems_id->id)
                            ->first();
                    }

                    if ($one_banner_item_elems_id->deleted == 1 && $one_banner_item_elems_id->active == 0) {

                        $del_message .= $parameter_elems->name . ', ';

                        GoodsParametrId::destroy($one_banner_item_elems_id->id);
                        GoodsParametr::where('goods_parametr_id', $one_banner_item_elems_id->id)->delete();
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

    public function destroyGoodsParameterToCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;


        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $parameter_elems_id = GoodsParametrId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$parameter_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($parameter_elems_id as $one_parameter_elems_id) {

                    $parameter_elems = GoodsParametr::where('goods_parametr_id', $one_parameter_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (is_null($parameter_elems)) {
                        $parameter_elems = GoodsParametr::where('goods_parametr_id', $one_parameter_elems_id->id)
                            ->first();
                    }

                    if ($one_parameter_elems_id->deleted == 0) {

                        $cart_message .= $parameter_elems->name . ', ';

                        GoodsParametrId::where('id', $one_parameter_elems_id->id)
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

    public function restoreGoodsParameter(Request $request)
    {
        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {

            $restored_elements_id_arr = explode(',', $restored_elements_id);
            $parameter_item_elems_id = GoodsParametrId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$parameter_item_elems_id->isEmpty()) {

                $cart_message = '';
                foreach ($parameter_item_elems_id as $one_parameter_item_elems_id) {

                    $parameter_name = GetNameByLang($one_parameter_item_elems_id->id, $this->lang_id, 'GoodsParametr', 'goods_parametr_id');
                    if ($one_parameter_item_elems_id->restored == 0) {

                        if (!empty($parameter_name))
                            $cart_message .= $parameter_name . ', ';

                        GoodsParametrId::where('id', $one_parameter_item_elems_id->id)
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
                'status' => false,
                'mes' => 'tut'
            ]);
        }
    }

    public function changeParameterValues(Request $request)
    {
        $neworder = $request->input('neworder');
        $i = 0;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {

            $id = str_replace("tablelistsorter_parametrvalue[]=", "", $v);

            if (!empty($id)) {
                GoodsParametrValueId::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function changeMasuresList(Request $request)
    {
        $neworder = $request->input('neworder');
        $i = 0;
        $neworder = explode("&", $neworder);

        foreach ($neworder as $k => $v) {


            $id = str_replace("tablelistsorter_measure[]=", "", $v);

            if (!empty($id)) {

                GoodsMeasureList::where('id', $id)->update(['position' => $i]);
                $i++;
            }
        }
    }

    public function removeParameter(Request $request)

    {

        $parameter_val_id = $request->input('action');


        $count_goods_parameter_value_id = GoodsParametrValueId::where('goods_parametr_id', $request->input('param_id'))->count();


        if ($count_goods_parameter_value_id > 1) {

            GoodsParametrValue::where('goods_parametr_value_id', $parameter_val_id)->delete();

            GoodsParametrValueId::where('id', $parameter_val_id)->delete();


            return response()->json([

                'status' => true,

                'messages' => controllerTrans('variables.removed', $this->lang),

            ]);

        } else {

            return response()->json([

                'status' => false,

                'messages' => controllerTrans('variables.min_one_elem', $this->lang),

            ]);

        }

    }

    public function removeMeasureList(Request $request)

    {

        $parameter_val_id = $request->input('action');


        $count_goods_parameter_measures = GoodsMeasureList::where('goods_parametr_id', $request->input('param_id'))->count();


        if ($count_goods_parameter_measures > 2) {

            GoodsMeasureList::where('id', $parameter_val_id)->delete();

            return response()->json([

                'status' => true,

                'messages' => controllerTrans('variables.removed', $this->lang),

            ]);

        } else {

            return response()->json([

                'status' => false,

                'messages' => controllerTrans('variables.min_two_elem', $this->lang),

            ]);

        }

    }

    public function searchObjects(Request $request)


    {


        $item = Validator::make($request->all(), [

            'id' => 'numeric|min:0',

        ]);


        if ($item->fails()) {

            return response()->json([

                'status' => false,

                'messages' => $item->messages(),

            ]);

        }


        $ajaxView = 'admin.goods.ajax-search-object';

        $search_key = $request->except('_token', 'goods_subject');

        $child_goods_item_list = [];

        $concrete_search_key = trim($request->input('search-key'));


        $lang = $this->lang;

        $modules_name = $this->menu()['modules_name'];

        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->src;


        $new_url = "";

        if (!empty($search_key)) {

            foreach ($search_key as $key => $one_key) {

                if (!empty($one_key)) {

                    if (is_array($one_key)) {

                        $new_url_arr = '';

                        foreach ($one_key as $val) {

                            $new_url_arr .= $val . ',';

                        }

                        $new_url .= $key . '=[' . substr($new_url_arr, 0, -1) . ']&';

                    } else {

                        $new_url .= $key . "=" . $one_key . '&';

                    }

                }

            }


            $new_url = '?' . substr($new_url, 0, -1);


            if (!empty($concrete_search_key)) {

                $child_goods_item_list = GoodsItem::leftjoin('goods_item_id', 'goods_item_id.id', '=', 'goods_item.goods_item_id')
                    ->where('goods_item.lang_id', $this->lang_id)
                    ->where(function ($q) use ($concrete_search_key) {

                        $q->orWhere('goods_item.name', 'like', '%' . $concrete_search_key . '%');

//                        $q->orWhere('goods_item_id.one_c_code', 'like', '%' . $concrete_search_key . '%');

                    })
                    ->paginate(200);


                $child_goods_item_list->setPath(url($lang, ['back', 'goods']) . '?search-key=' . $concrete_search_key);


                if ($child_goods_item_list->isEmpty()) {

                    $child_goods_item_list = [];

                }


            }


        }


        if (!empty($child_goods_item_list)) {

            return response()->json([

                'status' => true,

                'url' => $new_url,

                'view' => view($ajaxView, compact('child_goods_item_list', 'url_for_active_elem'))->render(),

                'messages' => ''

            ]);

        } else {

            return response()->json([

                'status' => false,

                'url' => $new_url,

                'view' => view($ajaxView, compact('child_goods_item_list', 'url_for_active_elem'))->render(),

                'messages' => ''

            ]);

        }


    }

    public function globalSearchObjects($search_result)

    {

        $ajaxView = 'admin.goods.search-object';

        $child_goods_item_list = [];

        $concrete_search_key = $search_result;


        $lang = $this->lang;

        $modules_name = $this->menu()['modules_name'];

        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->src;


        $goods_subject_id = null;

        if (!is_null(request()->segment(4)))

            $goods_subject_id = GoodsSubjectId::where('alias', request()->segment(4))
                ->first();


        if (!empty($concrete_search_key)) {


            $child_goods_item_list = GoodsItem::leftjoin('goods_item_id', 'goods_item_id.id', '=', 'goods_item.goods_item_id')
                ->where('goods_item.lang_id', $this->lang_id)
                ->where(function ($q) use ($concrete_search_key) {

                    $q->orWhere('goods_item.name', 'like', '%' . $concrete_search_key . '%');

//                        $q->orWhere('goods_item_id.one_c_code', 'like', '%' . $concrete_search_key . '%');

                })
                ->paginate(200);


            $child_goods_item_list->setPath(url($lang, ['back', 'goods']) . '?search-key=' . $concrete_search_key);


            if ($child_goods_item_list->isEmpty()) {

                $child_goods_item_list = [];

            }


        }


        return view($ajaxView, compact('child_goods_item_list', 'url_for_active_elem', 'goods_subject_id'));

    }

    public function removeModule(Request $request)
    {
        $module_id = $request->input('module_id');

        if (!is_null($module_id) || !empty($module_id)) {
            GoodsItemModulesId::destroy($module_id);
            GoodsItemModules::where('goods_item_modules_id', $module_id)->delete();

            return response()->json([
                'status' => true,
                'messages' => ['Module was deleted successful']
            ]);
        }

        return response()->json([
            'status' => false,
            'messages' => ['Ups, can\'t be deleted']
        ]);
    }

    public function youtubeId(Request $request, $youtube_link = null)
    {
        if (is_null($youtube_link))
            $code = $request->input('code');
        else
            $code = $youtube_link;

        if (!empty($code)) {
            if (FindYoutubeImg($code)) {
                $youtube_img = FindYoutubeImg($code);
            } else {
                $youtube_img = '';
            }
        } else {
            $youtube_img = '';
        }

        return $youtube_img;

    }

}

