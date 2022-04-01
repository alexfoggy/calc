<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Labels;
use App\Models\LabelsId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class LabelsController extends Controller
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
        $view = 'admin.labels.labels-list';

        $lang_id = $this->lang_id;

        $labels_list_id = LabelsId::orderBy('id', 'desc')
            ->paginate(200);

        $labels_list = [];
        foreach($labels_list_id as $key => $one_label_id){
            $labels_list[$key] = Labels::where('labels_id' ,$one_label_id->id)
                ->first();
        }
        //Remove all null values --start
        $labels_list = array_filter( $labels_list, 'strlen' );
        //Remove all null values --end

        return view($view, get_defined_vars());
    }

    public function createItem()
    {
        $view = 'admin.labels.create-label';

        return view($view, get_defined_vars());
    }

    public function editItem($id, $lang_id)
    {
        $view = 'admin.labels.edit-label';

        $labels_without_lang = Labels::where('labels_id', $id)
            ->first();

        if(is_null($labels_without_lang)){
            return App::abort(503, 'Unauthorized action.');
        }

        $labels = Labels::where('labels_id', $labels_without_lang->labels_id)
            ->where('lang_id', $lang_id)
            ->first();

        return view($view, get_defined_vars());
    }

    public function save(Request $request,$id, $lang_id)
    {
        $item = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($item->fails()){
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        }

        //Check if lang exist
        if(checkIfLangExist($request->input('lang')) == false)
            return response()->json([
                'status' => false,
                'messages' => [controllerTrans('variables.lang_not_exist', $this->lang)],
            ]);

        $labels_id = LabelsId::updateOrCreate([
            'id'=> $id
        ],[]);

        $labels_id->itemByLang()->updateOrCreate([
        'labels_id' => $labels_id->id,
        'lang_id' => $request->input('lang'),
        ],[
        'name' => $request->input('name'),
        ]);

        $labels_id->push();

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

    public function destroyLabelFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);
        $lang_id = $this->lang_id;

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $label_elems_id = LabelsId::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$label_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($label_elems_id as $one_label_elems_id) {

                    $label_elems = Labels::where('labels_id', $one_label_elems_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if(is_null($label_elems)){
                        $label_elems = Labels::where('labels_id', $one_label_elems_id->id)
                            ->first();
                    }

                    $del_message .= $label_elems->name . ', ';

                    LabelsId::destroy($one_label_elems_id->id);
                    Labels::where('labels_id', $one_label_elems_id->id)->delete();


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
