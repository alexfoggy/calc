<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Modules;
use App\Models\ModulesId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ModulesController extends Controller
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

        $view = 'admin.modules.elements-list';

        $lang_id = $this->lang_id;

        $modules_id_elements = ModulesId::where('level', 1)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();

        $module_elements = [];
        foreach ($modules_id_elements as $key => $modules_id_element) {
            $module_elements[] = Modules::where('modules_id', $modules_id_element->id)
                ->first();

        }

        //Remove all null values --start
        $module_elements = array_filter($module_elements);
        //Remove all null values --end

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
                ModulesId::where('id', $id)->update(['position' => $i]);
            }
        }
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = ModulesId::find($id);

        if($element_id->root == 0) {

            if (!is_null($element_id))
                $element_name = GetNameByLang($element_id->id, $this->lang_id, 'Modules', 'modules_id');
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

            ModulesId::where('id', $id)->update(['active' => $change_active]);

            return response()->json([
                'status' => true,
                'type' => 'info',
                'messages' => [$msg]
            ]);
        }

        return response()->json([
            'status' => false
        ]);

    }

    public function createModules()
    {
        $view = 'admin.modules.create-module';

        $curr_page_id = ModulesId::where('alias', request()->segment(4))
            ->first();

        if (!is_null($curr_page_id)) {
            $curr_page_id = $curr_page_id->id;
        } else {
            $curr_page_id = null;
        }

        return view($view, get_defined_vars());
    }

    public function editModules($id, $lang_id)
    {
        $view = 'admin.modules.edit-module';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $modules_without_lang = Modules::where('modules_id', $id)->first();

        if (is_null($modules_without_lang)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $modules_elems = Modules::where('lang_id', $lang_id)
            ->where('modules_id', $modules_without_lang->modules_id)
            ->first();

        if (!is_null($modules_without_lang)) {
            $modules_id = ModulesId::where('id', $modules_without_lang->modules_id)
                ->first();
        } elseif (!is_null($modules_elems)) {
            $modules_id = ModulesId::where('id', $modules_elems->modules_id)
                ->first();
        }

        return view($view, get_defined_vars());
    }

    public function saveModules(Request $request, $id, $lang_id)
    {

        if (is_null($id)) {

            $rules = [
                'name' => 'required',
                'alias' => 'required|unique:modules_id',
                'controller' => 'required|not_in:controller|not_in:Controller|min:10|unique:modules_id,controller',
                'view_folder' => 'not_in:view|not_in:View|unique:modules_id,view',
            ];

            foreach ($request->input('models') as $key => $val) {
                $rules['models.' . $key] = 'not_in:model|not_in:Model';
            }

            $item = Validator::make($request->all(), $rules);
        } else {

            $rules = [
                'name' => 'required',
                'alias' => 'required',
                'controller' => 'required|not_in:controller|not_in:Controller|min:10',
                'view_folder' => 'not_in:view|not_in:View',
            ];

            foreach ($request->input('models') as $key => $val) {
                $rules['models.' . $key] = 'not_in:model|not_in:Model';
            }

            $item = Validator::make($request->all(), $rules);
        }

        if ($item->fails()) {
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        $maxPosition = GetMaxPosition('modules_id');

        $level = GetLevel($request->input('p_id'), 'modules_id');

//        Check if lang exist
        if (checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        $models = '';
        if (!empty(array_filter($request->input('models')))) {
            $models = implode(';', $request->input('models'));
        }

        if (is_null($id)) {

//            Check if models exist
            if (checkIfItemExist($models, 'modules_id', 'models') == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.models_exist', $this->lang)],
                ]);
            }
//            Check if models exist

            if (!File::exists(app_path('Http/Controllers/Admin/' . $request->input('controller') . '(new).php'))) {
                Artisan::call('make:controller', ['name' => 'Admin\\' . $request->input('controller') . '(new)']);
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'messages' => [controllerTrans('variables.controllerExist', $this->lang, ['name' => $request->input('controller')])]
                ]);
            }

            if (!empty(array_filter($request->input('models')))) {
                foreach ($request->input('models') as $one_model) {
                    if (!File::exists(app_path('Models/' . $one_model . '.php'))) {
                        Artisan::call('make:model', ['name' => 'Models\\' . $one_model]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'type' => 'error',
                            'messages' => [controllerTrans('variables.modelExist', $this->lang, ['name' => $one_model])]
                        ]);
                    }
                }
            }

            if (!empty($request->input('view_folder'))) {
                if (!File::exists(base_path('resources/views/admin/' . $request->input('view_folder')))) {
                    File::makeDirectory(base_path('resources/views/admin/' . $request->input('view_folder')));
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'error',
                        'messages' => [controllerTrans('variables.viewFolderExist', $this->lang, ['name' => $request->input('view_folder')])]
                    ]);
                }
            }

            $modules_id = new ModulesId();
            $modules_id->p_id = $request->input('p_id');
            $modules_id->level = $level + 1;
            $modules_id->alias = $request->input('alias');
            $modules_id->position = $maxPosition + 1;
            $modules_id->active = 1;
            $modules_id->deleted = 0;
            $modules_id->controller = $request->input('controller');
            $modules_id->models = $models;
            $modules_id->view = $request->input('view_folder');
            $modules_id->root = $request->input('root') == 'on' ? 1 : 0;
            $modules_id->save();

            $modules = new Modules();
            $modules->modules_id = $modules_id->id;
            $modules->lang_id = $request->input('lang');
            $modules->name = $request->input('name');
            $modules->body = $request->input('body');
            $modules->save();

        } else {
            $exist_module = Modules::where('modules_id', $id)->first();

            if (is_null($exist_module)) {
                return App::abort(503, 'Unauthorized action.');
            }

//            Check if alias exist
            if (checkIfAliasExist($exist_module->modules_id, $request->input('alias'), 'modules_id') == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.alias_exist', $this->lang)],
                ]);
            }
//            Check if alias exist

//            Check if controller exist
            if (checkIfControllerExist($exist_module->modules_id, $request->input('controller'), 'modules_id') == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.controller_exist', $this->lang)],
                ]);
            }
//            Check if controller exist

//            Check if models exist
            if (checkIfItemExist($models, 'modules_id', 'models', $exist_module->modules_id) == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.models_exist', $this->lang)],
                ]);
            }
//            Check if models exist

//            Check if view exist
            if (checkIfItemExist($request->input('view_folder'), 'modules_id', 'view', $exist_module->modules_id) == true) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.view_folder_exist', $this->lang)],
                ]);
            }
//            Check if view exist


            $exist_module_by_lang = Modules::where('modules_id', $exist_module->modules_id)
                ->where('lang_id', $lang_id)
                ->first();

            $modules_id = ModulesId::where('id', $exist_module->modules_id)->first();

            $modules_id->p_id = $request->input('p_id');
            $modules_id->level = $level + 1;
            $modules_id->alias = $request->input('alias');
            $modules_id->controller = $request->input('controller');
            $modules_id->models = $models;
            $modules_id->view = $request->input('view_folder');
            $modules_id->root = $request->input('root') == 'on' ? 1 : 0;
            $modules_id->update();

            $modules = Modules::where('modules_id', $exist_module->modules_id)
                ->where('lang_id', $lang_id)->first();

            if(!$modules)
                $modules = new Modules();

            $modules->modules_id = $exist_module->modules_id;
            $modules->lang_id = $request->input('lang');
            $modules->name = $request->input('name');
            $modules->body = $request->input('body');
            $modules->save();

        }

        if (is_null($id)) {
            if ($modules_id->level == 1) {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, '')
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForFunctionLanguage($this->lang, GetParentAlias($modules_id->id, 'modules_id') . '/memberslist')
                ]);
            }

        }
        return response()->json([
            'status' => true,
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'editmodules/' . $id . '/' . $lang_id)
        ]);
    }

    public function membersList()
    {
        $view = 'admin.modules.child-list';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/' . $lang . '/back/' . $modules_name->modulesId->alias;

        $modules_list_id = ModulesId::where('alias', request()->segment(4))
            ->first();

        if (is_null($modules_list_id)) {
            return App::abort(503, 'Unauthorized action.');
        }

        $child_modules_list_id = ModulesId::where('p_id', $modules_list_id->id)
            ->where('deleted', 0)
            ->orderBy('position', 'asc')
            ->get();


        $child_modules_list = [];
        foreach ($child_modules_list_id as $key => $one_module_elem) {
            $child_modules_list[$key] = Modules::where('modules_id', $one_module_elem->id)
                ->first();
        }

        //Remove all null values --start
        $child_modules_list = array_filter($child_modules_list, 'strlen');
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function modulesCart()
    {
        $view = 'admin.modules.module-cart';

        $lang_id = $this->lang_id;

        $deleted_elems_by_alias = ModulesId::where('alias', request()->segment(4))
            ->first();

        if (is_null($deleted_elems_by_alias)) {
            $deleted_modules_id_elems = ModulesId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', 0)
                ->get();
        } else {
            $deleted_modules_id_elems = ModulesId::where('deleted', 1)
                ->where('active', 0)
                ->where('p_id', $deleted_elems_by_alias->id)
                ->get();
        }

        $deleted_modules_elems = [];

        foreach ($deleted_modules_id_elems as $key => $one_deleted_module_elem) {
            $deleted_modules_elems[$key] = Modules::where('modules_id', $one_deleted_module_elem->id)
                ->first();
        }

        $deleted_modules_elems = array_filter($deleted_modules_elems, 'strlen');

        return view($view, get_defined_vars());
    }

    public function destroyModulesFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $module_item_elems_id = ModulesId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$module_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($module_item_elems_id as $one_module_item_elems_id) {

                    if ($one_module_item_elems_id->root == 0) {

                        $module_item_elems = Modules::where('modules_id', $one_module_item_elems_id->id)
                            ->where('lang_id', $lang_id)
                            ->first();

                        if (is_null($module_item_elems)) {
                            $module_item_elems = Modules::where('modules_id', $one_module_item_elems_id->id)
                                ->first();
                        }

                        if ($one_module_item_elems_id->deleted == 1 && $one_module_item_elems_id->active == 0) {

                            $del_message .= $module_item_elems->name . ', ';

                            ModulesId::destroy($one_module_item_elems_id->id);
                            Modules::where('modules_id', $one_module_item_elems_id->id)->delete();

                        }

                    } else
                        return response()->json([
                            'status' => false
                        ]);
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

    public function destroyModulesToCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $module_item_elems_id = ModulesId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$module_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($module_item_elems_id as $one_module_item_elems_id) {

                    if ($one_module_item_elems_id->root == 0) {

                        $module_item_elems = Modules::where('modules_id', $one_module_item_elems_id->id)
                            ->where('lang_id', $lang_id)
                            ->first();

                        if (is_null($module_item_elems)) {
                            $module_item_elems = Modules::where('modules_id', $one_module_item_elems_id->id)
                                ->first();
                        }


                        if ($one_module_item_elems_id->deleted == 0) {

                            $cart_message .= $module_item_elems->name . ', ';

                            ModulesId::where('id', $one_module_item_elems_id->id)
                                ->update(['active' => 0, 'deleted' => 1]);
                        }

                    } else
                        return response()->json([
                            'status' => false
                        ]);
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

    public function restoreModules(Request $request, $id)
    {

        $restored_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($restored_elements_id)) {
            $restored_elements_id_arr = explode(',', $restored_elements_id);

            $modules_item_elems_id = ModulesId::whereIn('id', $restored_elements_id_arr)->get();

            if (!$modules_item_elems_id->isEmpty()) {

                $cart_message = '';

                foreach ($modules_item_elems_id as $one_modules_item_elems_id) {

                    $modules_name = GetNameByLang($one_modules_item_elems_id->id, $this->lang_id, 'Modules', 'modules_id');

                    if ($one_modules_item_elems_id->restored == 0) {

                        $cart_message .= $modules_name . ', ';

                        ModulesId::where('id', $one_modules_item_elems_id->id)
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

    public function removeClone(Request $request)
    {

        $curr_id = $request->input('curr_id');
        $curr_element = $request->input('curr_element');

        $modules_id = ModulesId::find($curr_id);

        if (is_null($modules_id))
            return response()->json([
                'status' => false,
                'type' => 'error',
                'messages' => controllerTrans('variables.item_error', $this->lang)
            ]);

        $models_arr = explode(';', $modules_id->models);

        if (array_key_exists($curr_element, $models_arr))
            unset($models_arr[$curr_element]);

        if (count($models_arr) > 0) {
            $models = implode(';', $models_arr);

            ModulesId::where('id', $modules_id->id)
                ->update(['models' => $models]);
        } else {
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'messages' => controllerTrans('variables.min_one_input', $this->lang)
            ]);
        }

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => controllerTrans('variables.item_deleted', $this->lang)
        ]);

    }

}
