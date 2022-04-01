<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\CityId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
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
        $view = 'admin.city.cities-list';

        $lang_id = $this->lang_id;

        $city_list_id = CityId::orderBy('position', 'asc')
            ->get();

        $city_list = [];
        foreach($city_list_id as $key => $one_city_id){
            $city_list[$key] = City::where('city_id' ,$one_city_id->id)
                ->first();
        }

        $city_list = array_filter( $city_list);

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
                CityId::where('id', $id)->update(['position' => $i]);
            }
        }
    }


	public function changeActive(Request $request)
	{
		$active = $request->input('active');
		$id = $request->input('id');

		$element_id = CityId::findOrFail($id);

		if(!is_null($element_id))
			$element_name = GetNameByLang($element_id->id, $this->lang_id, 'City', 'city_id');
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

		CityId::where('id', $id)->update(['active' => $change_active]);

		return response()->json([
			'status' => true,
			'type' => 'info',
			'messages' => [$msg]
		]);

	}

    public function createItem()
    {
        $view = 'admin.city.create-city';

        return view($view, get_defined_vars());
    }

    public function editItem($id, $edited_lang_id)
    {
        $view = 'admin.city.edit-city';

	    $lang = $this->lang;
	    $modules_name = $this->menu()['modules_name'];
	    $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $city_without_lang = City::where('city_id', $id)
            ->first();

        $city = City::where('city_id', $city_without_lang->city_id)
            ->where('lang_id', $edited_lang_id)
            ->first();

        return view($view, get_defined_vars());
    }

    public function save(Request $request, $id, $updated_lang_id)
    {
        if(is_null($id)){
            $item = Validator::make($request->all(), [
                'name' => 'required',
                'alias' => 'required|unique:city_id',
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

        $maxPosition = GetMaxPosition('city_id');

//        Check if lang exist
        if(checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);
//        Check if lang exist

        if(is_null($id)){

	        if(checkIfAliasExist($id, $request->input('alias'), 'city_id') == true)
		        return response()->json([
			        'status' => false,
			        'messages' => [controllerTrans('variables.alias_exist', $this->lang)],
		        ]);

            $data = [
                'position' => $maxPosition + 1,
                'active' => 1,
                'alias' => $request->input('alias'),
            ];

            $city_id = CityId::create($data);

            $data = [
                'city_id' => $city_id->id,
                'lang_id' => $request->input('lang'),
                'name' => $request->input('name'),
            ];

            City::create($data);
        }
        else {
            $exist_city = City::where('city_id', $id)->first();

            $exist_alias = CityId::where('alias', $request->input('alias'))->first();

            $exist_city_by_lang = City::where('city_id', $exist_city->city_id)
                                      ->where('lang_id', $updated_lang_id)
                                      ->first();


//            Check if alias exist
            if(!is_null($exist_city) && !is_null($exist_alias) && $exist_alias->id != $exist_city->city_id) {
                return response()->json([
                    'status' => false,
                    'messages' => [controllerTrans('variables.alias_exist', $this->lang)],
                ]);
            }

//            Check if alias exist

            $data = [
                'alias' => $request->input('alias')
            ];

            CityId::where('id', $exist_city->city_id)
                ->update($data);

            $data = [
                'city_id' => $exist_city->city_id,
                'lang_id' => $request->input('lang'),
                'name' => $request->input('name'),
            ];

            if(!is_null($exist_city_by_lang)){
                City::where('city_id', $exist_city->city_id)
                    ->where('lang_id', $updated_lang_id)
                    ->update($data);
            }
            else {
                City::create($data);
            }
        }

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
            'redirect' => urlForLanguage($this->lang, 'edititem/'.$id.'/'.$updated_lang_id)
        ]);

    }

    public function destroyCityFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $city_elems_id = CityId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$city_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($city_elems_id as $one_city_elems_id) {
                    $city = City::where('city_id', $one_city_elems_id->id)
                        ->where('lang_id', $this->lang_id)
                        ->first();

                    $del_message .= $city->name . ', ';

                    City::where('city_id', $one_city_elems_id->id)->delete();
                    CityId::destroy($one_city_elems_id->id);
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