<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfoItem;
use App\Models\InfoLineImages;
use App\Models\InfoLine;
use App\Models\InfoLineId;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\InfoItemId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class InfoLineController extends Controller
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
        $view = 'admin.infoline.infoline-list';

        $lang_id = $this->lang_id;

        $info_line_id = InfoLineId::where('deleted', 0)
            ->orderBy('position', 'asc')
            ->paginate(20);

        $info_line = [];
        foreach($info_line_id as $key => $one_info_line_id){
            $info_line[$key] = InfoLine::where('info_line_id', $one_info_line_id->id)
                ->first();
        }

        //Remove all null values --start
        $info_line = array_filter( $info_line, 'strlen' );
        //Remove all null values --end

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
                InfoLineId::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for img position
    public function changeImgPosition(Request $request)
    {
        $newOrder = $request->input('newOrder');

        $i = 0;
        foreach ($newOrder as $k=>$v) {
            $id = $v;
            $i++;

            if(!empty($id)){
                InfoLineImages::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');
        $action = $request->input('action');

        if($action != '')
            $element_id = InfoItemId::findOrFail($id);
        else
            $element_id = InfoLineId::findOrFail($id);

        if(!is_null($element_id)) {
            if($action != '')
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'InfoItem', 'info_item_id');
            else
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'InfoLine', 'info_line_id');
        }
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

        if($action != ''){
            InfoItemId::where('id', $id)->update(['active' => $change_active]);
        }
        else{
            InfoLineId::where('id', $id)->update(['active' => $change_active]);
        }

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);
    }

    public function createInfoLine()
    {
        $view = 'admin.infoline.create-infoline';

        return view($view, get_defined_vars());
    }

    public function editInfoLine($id, $lang_id)
    {
        $view = 'admin.infoline.edit-infoline';

        $info_line_without_lang = InfoLine::where('info_line_id', $id)->first();

        if(is_null($info_line_without_lang)){
            return App::abort(503, 'Unauthorized action.');
        }

        $info_line = InfoLine::where('lang_id', $lang_id)
            ->where('info_line_id', $info_line_without_lang->info_line_id)
            ->first();

        if(!is_null($info_line_without_lang)){
            $info_line_id = InfoLineId::where('id', $info_line_without_lang->info_line_id)
                ->first();
        }
        elseif(!is_null($info_line)){
            $info_line_id = InfoLineId::where('id', $info_line->info_line_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function infoLineCart()
    {
        $view = 'admin.infoline.infoline-cart';

        $lang_id = $this->lang_id;

        $deleted_info_line_id = InfoLineId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_info_line = [];

        foreach($deleted_info_line_id as $key => $one_deleted_info_line_id){
            $deleted_info_line[$key] = InfoLine::where('info_line_id', $one_deleted_info_line_id->id)
                ->first();
        }

        $deleted_info_line = array_filter( $deleted_info_line, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function saveInfoLine(Request $request, $id, $lang_id)
    {
        if(is_null($id)){
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:info_line_id',
            ]);
        }
        else {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required'
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('info_line_id');
        if($id){
            $currentPosition = GetPosition('info_line_id', $id);
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

        $info_line_id = InfoLineId::updateOrCreate(['id'=> $id],[
        'position' => $position,
        'active' => 1,
        'deleted' => 0,
        'alias' => $request->input('alias'),
        ]);

        $info_line_id->itemByLang()->updateOrCreate([
        'info_line_id' => $info_line_id->id,
        'lang_id' => $request->input('lang'),
        ],[
        'name' => $request->input('name'),
        'descr' => $request->input('body'),
        'page_title' => $request->input('title'),
        'h1_title' => $request->input('h1_title'),
        'meta_title' => $request->input('meta_title'),
        'meta_keywords' => $request->input('meta_keywords'),
        'meta_description' => $request->input('meta_description'),
        ]);

        $info_line_id->push();

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
            'redirect' => urlForLanguage($this->lang, 'editinfoline/'.$id.'/'.$lang_id)
        ]);

    }

    public function destroyInfoLineFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $info_line_elems_id = InfoLineId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$info_line_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($info_line_elems_id as $one_info_line_elems_id) {

                    $info_line_elems = InfoLine::where('info_line_id', $one_info_line_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($info_line_elems)){
                        $info_line_elems = InfoLine::where('info_line_id', $one_info_line_elems_id->id)
                            ->first();
                    }

                    if ($one_info_line_elems_id->deleted == 1 && $one_info_line_elems_id->active == 0) {

                        $del_message .= $info_line_elems->name . ', ';

                        InfoLineId::destroy($one_info_line_elems_id->id);
                        InfoLine::where('info_line_id', $one_info_line_elems_id->id)->delete();

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

    public function destroyInfoLineToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $info_line_elems_id = InfoLineId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$info_line_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($info_line_elems_id as $one_info_line_elems_id) {

                    $info_line_elems = InfoLine::where('info_line_id', $one_info_line_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($info_line_elems)){
                        $info_line_elems = InfoLine::where('info_line_id', $one_info_line_elems_id->id)
                            ->first();
                    }


                    if ($one_info_line_elems_id->deleted == 0) {

                        $cart_message .= $info_line_elems->name . ', ';

                        InfoLineId::where('id', $one_info_line_elems_id->id)
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

    public function restoreInfoLine(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $info_item_elems_id = InfoLineId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$info_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($info_item_elems_id as $one_info_item_elems_id) {

                    $info_name = GetNameByLang($one_info_item_elems_id->id, $this->lang_id, 'InfoLine', 'info_line_id');

                    if ($one_info_item_elems_id->restored == 0) {

                        $cart_message .= $info_name . ', ';

                        InfoLineId::where('id', $one_info_item_elems_id->id)
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

    public function membersList()
    {
        $view = 'admin.infoline.infoitems-list';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;


        $lang_id = $this->lang_id;

        $info_line_id = InfoLineId::where('alias', request()->segment(4))
            ->first();

        if(is_null($info_line_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $info_items_list = InfoItemId::where('deleted', 0)
            ->where('info_line_id', $info_line_id->id)
            ->orderBy('add_date', 'desc')
            ->paginate(40);

        $info_item = [];
        foreach($info_items_list as $key => $one_info_tem){
            $info_item[$key] = InfoItem::where('info_item_id', $one_info_tem->id)
                ->first();
        }

        //Remove all null values --start
        $info_item = array_filter( $info_item, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function createInfoItem()
    {
        $view = 'admin.infoline.create-infoitem';

        $modules_name = $this->menu()['modules_name'];
        $category_list = showSettingBodyByAlias('articles-categories', $this->lang_id);

        if(!empty($category_list))
            $category_list = explode(';', $category_list);
        else
            $category_list = '';

        return view($view, get_defined_vars());
    }

    public function editInfoItem($id, $lang_id)
    {
        $view = 'admin.infoline.edit-infoitem';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $info_item_without_lang = InfoItem::where('info_item_id', $id)->first();

        if(is_null($info_item_without_lang)){
            return App::abort(503, 'Unauthorized action.');
        }

        $info_item = InfoItem::where('lang_id', $lang_id)
            ->where('info_item_id', $info_item_without_lang->info_item_id)
            ->first();


        if(!is_null($info_item_without_lang)){
            $info_item_id = InfoItemId::where('id', $info_item_without_lang->info_item_id)
                ->first();
            $info_line_id = InfoLineId::where('id', $info_item_id->info_line_id)->first();
        }
        elseif(!is_null($info_item)){
            $info_item_id = InfoItemId::where('id', $info_item->info_item_id)
                ->first();
            $info_line_id = InfoLineId::where('id', $info_item_id->info_line_id)->first();
        }

        $category_list = showSettingBodyByAlias('articles-categories', $this->lang_id);

        if(!empty($category_list))
            $category_list = explode(';', $category_list);
        else
            $category_list = '';

        return view($view, get_defined_vars());
    }

    public function infoItemsCart()
    {
        $view = 'admin.infoline.infoitems-cart';

        $lang_id = $this->lang_id;

        $deleted_info_item_id = InfoItemId::where('deleted', 1)
            ->where('active', 0)
            ->get();

        $deleted_info_item = [];

        foreach($deleted_info_item_id as $key => $one_deleted_info_item_id){
            $deleted_info_item[$key] = InfoItem::where('info_item_id', $one_deleted_info_item_id->id)
                ->first();
        }

        $deleted_info_item = array_filter( $deleted_info_item, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function saveInfoItem(Request $request, $id, $lang_id)
    {
        if(is_null($id)){
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:info_item_id'
            ]);
        }
        else {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required'
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $info_line_id = InfoLineId::where('alias', request()->segment(4))->first()->id;

        if(is_null($info_line_id))
            return App::abort(503, 'Unauthorized action.');


        $array_img = [];
        if(!is_null($request->input('file')) && !empty($request->input('file'))) {
            foreach ($request->input('file') as $item) {
                if(!is_null($item))
                    $array_img[] = basename($item);
            }
        }

        if(!empty($request->input('add_date'))){
            $add_date = date('Y-m-d', strtotime($request->input('add_date')));
        }
        else{
            $add_date = '';
        }

//        Check if lang exist
        if(checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        $info_item_id = InfoItemId::updateOrCreate([
            'id'=> $id,
            'info_line_id'=> $info_line_id
        ],[
        'alias' => $request->input('alias'),
        'is_public' => $request->input('is_public') == 'on' ? 1 : 0,
        'active' => 1,
        'deleted' => 0,
        'add_date' => $add_date,
        'show_img' => $request->input('show_img') == 'on' ? 1 : 0,
        ]);

        $info_item_id->itemByLang()->updateOrCreate([
        'info_item_id' => $info_item_id->id,
        'lang_id' => $request->input('lang'),
        ],[
        'name' => $request->input('name'),
        'body' => $request->input('body'),
        'descr' => $request->input('descr'),
        'author' => $request->input('author'),
        'page_title' => $request->input('title'),
        'h1_title' => $request->input('h1_title'),
        'meta_title' => $request->input('meta_title'),
        'meta_keywords' => $request->input('meta_keywords'),
        'meta_description' => $request->input('meta_description'),
        ]);

        $info_item_id->push();

        $exist_menu_images = InfoLineImages::where('info_item_id', $info_item_id->id)
            ->whereIn('img', $array_img)
            ->pluck('img')
            ->toArray();

        if(count($array_img) >= count($exist_menu_images)) {
            foreach ($exist_menu_images as $exist_menu_image) {
                $pos = array_search($exist_menu_image, $array_img);
                unset($array_img[$pos]);
            }
        }

//            Upload images for current menu
            if(!is_null($request->input('file')) && !empty($request->input('file'))) {
                foreach (array_reverse($request->input('file')) as $item) {
                    $maxImgPosition = GetMaxPosition('info_line_images');
                    $img = basename($item);

                    $info_item_id->moduleMultipleImg()->updateOrCreate([
                    'info_item_id' => $info_item_id->id,
                    'img' => $img,
                    ],[
                    'active' => 1,
                    'position' => $maxImgPosition + 1,
                    ]);
                }
            }
//            Upload images for current menu


        if(is_null($id)){
            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.save', $this->lang)],
                'redirect' => urlForLanguage($this->lang, 'memberslist')
            ]);
        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editinfoitem/'.$id.'/'.$lang_id)
        ]);
    }

    public function destroyInfoItemFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $info_item_elems_id = InfoItemId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$info_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($info_item_elems_id as $one_info_item_elems_id) {

                    $info_item_elems = InfoItem::where('info_item_id', $one_info_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($info_item_elems)){
                        $info_item_elems = InfoItem::where('info_item_id', $one_info_item_elems_id->id)
                            ->first();
                    }

                    if ($one_info_item_elems_id->deleted == 1 && $one_info_item_elems_id->active == 0) {

                        $info_item_images = $one_info_item_elems_id->moduleMultipleImg;

                        if(!is_null($info_item_images) && !$info_item_images->isEmpty()) {
                            foreach ($info_item_images as $info_item_image) {
                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/s/'.$info_item_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/s/'.$info_item_image->img);

                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/m/'.$info_item_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/m/'.$info_item_image->img);

                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/'.$info_item_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/'.$info_item_image->img);
                            }
                        }

                        $del_message .= $info_item_elems->name . ', ';

                        InfoItemId::destroy($one_info_item_elems_id->id);
                        InfoItem::where('info_item_id', $one_info_item_elems_id->id)->delete();

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

    public function destroyInfoItemToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $info_item_elems_id = InfoItemId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$info_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($info_item_elems_id as $one_info_item_elems_id) {

                    $info_item_elems = InfoItem::where('info_item_id', $one_info_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($info_item_elems)){
                        $info_item_elems = InfoItem::where('info_item_id', $one_info_item_elems_id->id)
                            ->first();
                    }


                    if ($one_info_item_elems_id->deleted == 0) {

                        $cart_message .= $info_item_elems->name . ', ';

                        InfoItemId::where('id', $one_info_item_elems_id->id)
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

    public function restoreInfoItem(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $info_item_elems_id = InfoItemId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$info_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($info_item_elems_id as $one_info_item_elems_id) {

                    $info_name = GetNameByLang($one_info_item_elems_id->id, $this->lang_id, 'InfoItem', 'info_item_id');

                    if ($one_info_item_elems_id->restored == 0) {

                        $cart_message .= $info_name . ', ';

                        InfoItemId::where('id', $one_info_item_elems_id->id)
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

}
