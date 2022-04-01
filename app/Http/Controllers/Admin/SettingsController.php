<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SettingsId;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class SettingsController extends Controller {
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index()
    {
        $view = 'admin.settings.settings-list';
        $lang_id = $this->lang_id;
        $settings_list_id = SettingsId::orderBy('id','asc')->paginate(20);
        $settings_list = [];
        foreach($settings_list_id as $key => $one_settings_list_id){

            $settings_list[$key] = Settings::where('settings_id',$one_settings_list_id->id)->first();
        }
        //Remove all null values --start
        $settings_list = array_filter($settings_list,'strlen');
        //Remove all null values --end
        return view($view,get_defined_vars());
    }

    public function createItem()
    {
        $view = 'admin.settings.create-setting';
        return view($view,get_defined_vars());
    }

    public function editItem($id,$lang_id)
    {
        $view = 'admin.settings.edit-setting';
        $settings_without_lang = Settings::where('settings_id',$id)->first();
        if(is_null($settings_without_lang)){

            return App::abort(503,'Unauthorized action.');
        }
        $settings = Settings::where('settings_id',$settings_without_lang->settings_id)
            ->where('lang_id',$lang_id)
            ->first();

        if(!is_null($settings_without_lang)){

            $settings_id = SettingsId::where('id',$settings_without_lang->settings_id)->first();
        }elseif(!is_null($settings)){

            $settings_id = SettingsId::where('id',$settings->settings_id)->first();
        }
        return view($view,get_defined_vars());
    }

    public function save(Request $request,$id, $lang_id)
    {
        if(is_null($id)){
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:settings_id'
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

        $data = array_filter([
            'alias' => $request->input('alias'),
            'set_type' => $request->input('set_type')
        ]);

//        Check if lang exist
        if(checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        $lang_id = $request->input('lang');

        $settings_id = SettingsId::updateOrCreate([
            'id'=> $id,
        ],[
            'alias' => $request->input('alias'),
            'set_type' => $request->input('set_type')
        ]);

        $body = '';

        /** *Setting body type */
        if($request->input('body')){
            $body = $request->input('body');
        }
        if($request->input('textarea'))
            $body = $request->input('textarea');
        if($request->input('input'))
            $body = $request->input('input');
        /** *Setting body type */


        $settings_id->itemByLang()->updateOrCreate([
            'settings_id' => $settings_id->id,
            'lang_id' => $lang_id,
        ],[
            'name' => $request->input('name'),
            'body' => $body,
        ]);
        $settings_id->push();

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

    public function destroySettingFromCart(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {

            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $setting_item_elems_id = SettingsId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$setting_item_elems_id->isEmpty()) {
                $del_message = '';

                foreach ($setting_item_elems_id as $one_setting_item_elems_id) {

                    $setting_item_elems = Settings::where('settings_id', $one_setting_item_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($setting_item_elems)){
                        $setting_item_elems = Settings::where('settings_id', $one_setting_item_elems_id->id)
                            ->first();
                    }

                    $del_message .= $setting_item_elems->name . ', ';

                    SettingsId::destroy($one_setting_item_elems_id->id);
                    Settings::where('settings_id', $one_setting_item_elems_id->id)->delete();

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

    /**
     * return to another url, if method membersList does not exist
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function membersList()
    {
        return redirect(urlForFunctionLanguage($this->lang, ''));
    }
}
