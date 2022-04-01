<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\CityId;
use App\Models\Shops;
use App\Models\ShopsId;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ShopsController extends Controller
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
        $view = 'admin.shops.shops-list';

        $lang_id = $this->lang_id;

        $shops_list_id = ShopsId::orderBy('position', 'asc')
            ->get();

        $shops_list = [];
        foreach ($shops_list_id as $key => $one_shops_id) {
            $shops_list[$key] = Shops::where('shops_id', $one_shops_id->id)
                ->first();
        }

        $shops_list = array_filter($shops_list);

        return view($view, get_defined_vars());
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = ShopsId::findOrFail($id);

        if (!is_null($element_id))
            $element_name = GetNameByLang($element_id->id, $this->lang_id, 'Shops', 'shops_id');
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

        ShopsId::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);

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
                ShopsId::where('id', $id)->update(['position' => $i]);
            }
        }
    }


    public function createItem()
    {
        $view = 'admin.shops.create-shops';

        $modules_name = $this->menu()['modules_name'];

        $city_id = CityId::where('active', 1)
            ->orderBy('position', 'asc')
            ->get();

        $city = [];

        if (!$city_id->isEmpty()) {
            foreach ($city_id as $one_city_id) {
                $city[] = City::where('city_id', $one_city_id->id)
                    ->where('lang_id', $this->lang_id)
                    ->first();
            }

            $city = array_filter($city);
        }

        return view($view, get_defined_vars());
    }

    public function editItem($id, $edited_lang_id)
    {
        $view = 'admin.shops.edit-shops';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $shops_id = ShopsId::where('id', $id)
            ->first();

        $shops_without_lang = Shops::where('shops_id', $id)
            ->first();

        $shops = Shops::where('shops_id', $shops_without_lang->shops_id)
            ->where('lang_id', $edited_lang_id)
            ->first();

        $city_id = CityId::where('active', 1)
            ->orderBy('position', 'asc')
            ->get();

        $city = [];

        if (!$city_id->isEmpty()) {
            foreach ($city_id as $one_city_id) {
                $city[] = City::where('city_id', $one_city_id->id)
                    ->where('lang_id', $this->lang_id)
                    ->first();
            }

            $city = array_filter($city);
        }

        return view($view, get_defined_vars());
    }

    public function save(Request $request, $id, $updated_lang_id)
    {
        if (is_null($id)) {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'alias' => 'required|unique:shops_id',
                'phone' => 'required|regex:/^[0-9]{9}$/',
            ],
                [
                    'phone.regex' => controllerTrans('variables.__validate_phone', $this->lang) . ' 0********',
                ]);
        } else {
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'alias' => 'required',
                'phone' => 'required|regex:/^[0-9]{9}$/',
            ],
                [
                    'phone.regex' => controllerTrans('variables.__validate_phone', $this->lang) . ' 0********',
                ]);
        }

        if ($item->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

//        Check if lang exist
        if (checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        $img = null;
        if (!is_null($request->input('file')) && !empty($request->input('file'))) {
            foreach ($request->input('file') as $item) {
                if (!is_null($item))
                    $img = basename($item);
            }
        }

        if (empty($request->input('file')))
            $img = basename($request->input('file'));

        $position = GetMaxPosition('shops_id');

        if (is_null($id)) {

            $shops_id = new ShopsId();
            $shops_id->active = 1;
            $shops_id->alias = $request->input('alias');
            $shops_id->city_id = $request->input('city_id');
            $shops_id->phone = $request->input('phone');
            $shops_id->latitude = $request->input('latitude');
            $shops_id->longitude = $request->input('longitude');
            $shops_id->img = !is_null($img) ? $img : null;
            $shops_id->position = $position + 1;
            $shops_id->save();

            $save_shop = new Shops();
            $save_shop->shops_id = $shops_id->id;
            $save_shop->lang_id = $request->input('lang');
            $save_shop->name = $request->input('name');
            $save_shop->type = $request->input('type');
            $save_shop->distr = $request->input('distr');
            $save_shop->cafe = $request->input('cafe');
            $save_shop->address = $request->input('address');
            $save_shop->schedule = $request->input('schedule');
            $save_shop->save();

        } else {
            $exist_shops = Shops::where('shops_id', $id)->first();

            $exist_shops_by_lang = Shops::where('shops_id', $exist_shops->shops_id)
                ->where('lang_id', $updated_lang_id)
                ->first();

//            Check if alias exist
            if (checkIfAliasExist($exist_shops->shops_id, $request->input('alias'), 'shops_id') == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.alias_exist', $this->lang)],
                ]);
            }
//            Check if alias exist

            $update_shop_id = ShopsId::where('id', $exist_shops->shops_id)->first();
            $update_shop_id->alias = $request->input('alias');
            $update_shop_id->city_id = $request->input('city_id');
            $update_shop_id->phone = $request->input('phone');
            $update_shop_id->latitude = $request->input('latitude');
            $update_shop_id->longitude = $request->input('longitude');
            $update_shop_id->img = !is_null($img) ? $img : null;
            $update_shop_id->position = $position + 1;
            $update_shop_id->update();

            if (!is_null($exist_shops_by_lang)) {
                $update_shop = Shops::where('shops_id', $exist_shops->shops_id)
                    ->where('lang_id', $updated_lang_id)
                    ->first();

                $update_shop->shops_id = $exist_shops->shops_id;
                $update_shop->lang_id = $request->input('lang');
                $update_shop->name = $request->input('name');
                $update_shop->type = $request->input('type');
                $update_shop->distr = $request->input('distr');
                $update_shop->cafe = $request->input('cafe');
                $update_shop->address = $request->input('address');
                $update_shop->schedule = $request->input('schedule');
                $update_shop->update();

            } else {
                $save_shop = new Shops();
                $save_shop->shops_id = $exist_shops->shops_id;
                $save_shop->lang_id = $request->input('lang');
                $save_shop->name = $request->input('name');
                $save_shop->type = $request->input('type');
                $save_shop->distr = $request->input('distr');
                $save_shop->cafe = $request->input('cafe');
                $save_shop->address = $request->input('address');
                $save_shop->schedule = $request->input('schedule');
                $save_shop->save();
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
            'redirect' => urlForLanguage($this->lang, 'edititem/' . $id . '/' . $updated_lang_id)
        ]);

    }


    public function destroyShopsFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $shops_item_elems_id = ShopsId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$shops_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($shops_item_elems_id as $one_shops_item_elems_id) {

                    $shops_item_elems = Shops::where('shops_id', $one_shops_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (is_null($shops_item_elems)) {
                        $shops_item_elems = Shops::where('shops_id', $one_shops_item_elems_id->id)
                            ->first();
                    }

                    if (!empty($one_shops_item_elems_id->img)) {
                        if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $one_shops_item_elems_id->img))
                            File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/s/' . $one_shops_item_elems_id->img);

                        if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $one_shops_item_elems_id->img))
                            File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/m/' . $one_shops_item_elems_id->img);

                        if (File::exists('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $one_shops_item_elems_id->img))
                            File::delete('upfiles/' . $this->menu()['modules_name']->modulesId->alias . '/' . $one_shops_item_elems_id->img);

                    }

                    $del_message .= $shops_item_elems->name . ', ';

                    ShopsId::destroy($one_shops_item_elems_id->id);
                    Shops::where('shops_id', $one_shops_item_elems_id->id)->delete();

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

}