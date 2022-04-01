<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsItemId;
use App\Models\GoodsSubjectId;
use App\Models\Menu;
use App\Models\MenuId;
use App\Models\MenuImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class MenuController extends Controller
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
        $view = 'admin.menu.elements-list';

        $lang_id = $this->lang_id;

        $menu_id_elements = MenuId::where('level', 1)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();

        $menu_elements = [];
        foreach($menu_id_elements as $key => $menu_id_element){
            $menu_elements[$key] = Menu::where('menu_id', $menu_id_element->id)
                ->first();
        }

        //Remove all null values --start
        $menu_elements = array_filter( $menu_elements, 'strlen' );
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
                MenuId::where('id', $id)->update(['position' => $i]);
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
                MenuImages::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = MenuId::findOrFail($id);

        if(!is_null($element_id))
            $element_name = GetNameByLang($element_id->id, $this->lang_id, 'Menu', 'menu_id');
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

        MenuId::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);

    }

    // ajax response for active
    public function changeTopMenu(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = MenuId::findOrFail($id);

        if(!is_null($element_id))
            $element_name = GetNameByLang($element_id->id, $this->lang_id, 'Menu', 'menu_id');
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

        MenuId::where('id', $id)->update(['top_menu' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);

    }

    // ajax response for active
    public function changeFooterMenu(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = MenuId::findOrFail($id);

        if(!is_null($element_id))
            $element_name = GetNameByLang($element_id->id, $this->lang_id, 'Menu', 'menu_id');
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

        MenuId::where('id', $id)->update(['footer_menu' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);
    }

    public function createMenu()
    {
        $view = 'admin.menu.create-menu';

        $modules_name = $this->menu()['modules_name'];
        $curr_page_menu_id = MenuId::where('alias', request()->segment(4))
            ->first();

        if(!is_null($curr_page_menu_id)){
            $curr_page_id = $curr_page_menu_id->id;
        }
        else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    public function editMenu($id, $lang_id)
    {
        $view = 'admin.menu.edit-menu';
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $menu_without_lang = Menu::where('menu_id', $id)->first();


//
//        $works = GoodsSubjectId::where('alias','projects')
//            ->with(['goodsItemId' => function ($q) {
//               $q->where('active', 1)
//                    ->where('deleted', 0)
//                   ->has('itemByLang')
//                   ->with('itemByLang')
//                   ->orderBy('position', 'asc');
//           }])
//            ->first();

        $parent_menu_id = MenuId::whereRaw('id IN(SELECT p_id FROM menu_id WHERE id = '.$id.')')
                                ->first();

        if(is_null($menu_without_lang)){
            return App::abort(503, 'Unauthorized action.');
        }

        $menu_elems = Menu::where('lang_id', $lang_id)
            ->where('menu_id', $menu_without_lang->menu_id)
            ->first();

        if(!is_null($menu_without_lang)){
            $menu_id = MenuId::where('id', $menu_without_lang->menu_id)
                ->first();
        }
        elseif(!is_null($menu_elems)){
            $menu_id = MenuId::where('id', $menu_elems->menu_id)
                ->first();
        }

        $images = MenuImages::where('menu_id',$id)
                            ->orderBy('position','asc')
                            ->get();

        return view($view, get_defined_vars());
    }

    public function saveMenu ($id, $lang_id)
    {
        if(is_null($id)){
            $item = Validator::make(request()->all(), [
                'name' => 'required',
                'alias' => 'required|unique:menu_id',
                'controller' => 'not_in:controller|not_in:Controller|min:10'
            ]);
        }
        else {
            $item = Validator::make(request()->all(), [
                'name' => 'required',
                'alias' => 'required',
                'controller' => 'not_in:controller|not_in:Controller|min:10'
            ]);
        }

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('menu_id');
        $level = GetLevel(request()->input('p_id'), 'menu_id');

        if($id){
            $currentPosition = GetPosition('menu_id', $id);
            $position = $currentPosition;
        }else{
            $position = $maxPosition + 1 ;
        }

//        Check if lang exist
        if(checkIfLangExist(request()->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        $menu_id = MenuId::updateOrCreate(['id'=> $id],[
        'p_id' => request()->input('p_id'),
        'level' => $level + 1,
        'alias' => request()->input('alias'),
        'page_type' => request()->input('page_type'),
        'position' => $position,
        //'img' => ' ',
        'active' => 1,
        //'top_menu' => 1,
        //'footer_menu' => 1,
        'deleted' => 0,
        ]);

        $menu_id->itemByLang()->updateOrCreate([
            'menu_id' => $menu_id->id,
            'lang_id' => request()->input('lang'),
            ],[
        'name' => request()->input('name'),
        'body' => request()->input('body'),
        'link' => request()->input('link'),
        'page_title' => request()->input('title'),
        'h1_title' => request()->input('h1_title'),
        'meta_title' => request()->input('meta_title'),
        'meta_keywords' => request()->input('meta_keywords'),
        'meta_description' => request()->input('meta_description'),
        ]);
        $menu_id->push();

        uploadAdminImages(request()->file('files'),request()->input('uploaded_images'),'menu',$menu_id);

        if(is_null($id)){
            if($menu_id->level == 1){
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, '')
                ]);
            }
            else {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, GetParentAlias($menu_id->id, 'menu_id').'/memberslist')
                ]);
            }

        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editmenu/'.$id.'/'.$lang_id)
        ]);
    }

    public function membersList()
    {
        $view = 'admin.menu.child-list';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $menu_list_id = MenuId::where('alias', request()->segment(4))
            ->first();

        if(is_null($menu_list_id)){
            return App::abort(503, 'Unauthorized action.');
        }

        $child_menu_list_id = MenuId::where('p_id', $menu_list_id->id)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();


        $child_menu_list = [];
        foreach($child_menu_list_id as $key => $one_menu_elem){
            $child_menu_list[$key] = Menu::where('menu_id', $one_menu_elem->id)
                ->first();
        }

        //Remove all null values --start
        $child_menu_list = array_filter( $child_menu_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function menuCart()
    {
        $view = 'admin.menu.menu-cart';

        $lang_id = $this->lang_id;

        $deleted_elems_by_alias = MenuId::where('alias', request()->segment(4))
            ->first();

        if(is_null($deleted_elems_by_alias)){
            $deleted_menu_id_elems = MenuId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', 0)
                ->get();
        }
        else{
            $deleted_menu_id_elems = MenuId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', $deleted_elems_by_alias->id)
                ->get();
        }

        $deleted_menu_elems = [];

        foreach($deleted_menu_id_elems as $key => $one_deleted_menu_elem){
            $deleted_menu_elems[$key] = Menu::where('menu_id', $one_deleted_menu_elem->id)
                ->first();
        }

        $deleted_menu_elems = array_filter( $deleted_menu_elems, 'strlen' );

        return view($view, get_defined_vars());
    }

    public function destroyMenuFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $menu_item_elems_id = MenuId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$menu_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($menu_item_elems_id as $one_menu_item_elems_id) {

                    $menu_item_elems = Menu::where('menu_id', $one_menu_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($menu_item_elems)){
                        $menu_item_elems = Menu::where('menu_id', $one_menu_item_elems_id->id)
                            ->first();
                    }

                    if ($one_menu_item_elems_id->deleted == 1 && $one_menu_item_elems_id->active == 0) {

                        $menu_images = $one_menu_item_elems_id->moduleMultipleImg;

                        if(!is_null($menu_images) && !$menu_images->isEmpty()) {
                            foreach ($menu_images as $menu_image) {
                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/s/'.$menu_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/s/'.$menu_image->img);

                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/m/'.$menu_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/m/'.$menu_image->img);

                                if(File::exists('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/'.$menu_image->img))
                                    File::delete('upfiles/'.$this->menu()['modules_name']->modulesId->alias.'/'.$menu_image->img);
                            }
                        }

                        $del_message .= $menu_item_elems->name . ', ';

                        MenuId::destroy($one_menu_item_elems_id->id);
                        Menu::where('menu_id', $one_menu_item_elems_id->id)->delete();

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

    public function destroyMenuToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $menu_item_elems_id = MenuId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$menu_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($menu_item_elems_id as $one_menu_item_elems_id) {

                    $menu_item_elems = Menu::where('menu_id', $one_menu_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($menu_item_elems)){
                        $menu_item_elems = Menu::where('menu_id', $one_menu_item_elems_id->id)
                            ->first();
                    }


                    if ($one_menu_item_elems_id->deleted == 0) {

                        $cart_message .= $menu_item_elems->name . ', ';

                        MenuId::where('id', $one_menu_item_elems_id->id)
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

    public function restoreMenu(Request $request)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $promotion_item_elems_id = MenuId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$promotion_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($promotion_item_elems_id as $one_promotion_item_elems_id) {

                    $promotion_name = GetNameByLang($one_promotion_item_elems_id->id, $this->lang_id, 'Menu', 'menu_id');

                    if ($one_promotion_item_elems_id->restored == 0) {

                        $cart_message .= $promotion_name . ', ';

                        MenuId::where('id', $one_promotion_item_elems_id->id)
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
