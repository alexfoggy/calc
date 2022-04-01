<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedForm;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FeedFormController extends Controller
{

    private $lang;

    public function __construct()
    {
        $this->lang = $this->lang()['lang'];
    }

    public function index()
    {
        $view = 'admin.feedform.feedform-list';
        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $feedform = FeedForm::orderBy('created_at', 'desc')
            ->paginate(40);

        $new_feedform = FeedForm::where('seen', 0)->count();
        if($new_feedform > 0) {
            FeedForm::where('seen', 0)->update(['seen' => 1]);
        }

        return view($view, get_defined_vars());
    }

    // ajax response for active
    public function changeActive(Request $request)
    {
        $active = $request->input('active');
        $id = $request->input('id');

        $element_id = FeedForm::findOrFail($id);

        if(!is_null($element_id))
            $element_name = FeedForm::where('id', $id)->first()->name;
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

        FeedForm::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);

    }

    public function destroyFeedFormFromCart(Request $request)
    {

        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $goods_item_elems_id = FeedForm::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {
                    $del_message .= $one_goods_item_elems_id->name . ', ';

                    FeedForm::destroy($one_goods_item_elems_id->id);
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

    public function editItem($id)
    {
        $view = 'admin.feedform.edit-feedform';

        $lang = $this->lang;
        $modules_name = $this->menu()['modules_name'];
        $url_for_active_elem = '/'.$lang.'/back/'.$modules_name->modulesId->alias;

        $feedform = FeedForm::where('id', $id)->first();

        if(is_null($feedform)){
            return App::abort(503, 'Unauthorized action.');
        }

        return view($view, get_defined_vars());
    }

    public function save(Request $request,$id)
    {

        $item = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if ($item->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'messages' => $item->messages(),
            ]);
        }

        if (is_null($id))
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)],
            ]);

        $feedform = FeedForm::where('id', $id)->first();

        if (is_null($feedform))
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)],
            ]);

        $data = [
            'comment' => !is_null($request->input('comment')) ? $request->input('comment') : ''
        ];

        FeedForm::where('id', $feedform->id)->update($data);

        return response()->json([
            'status' => true,
            'type' => 'success',
            'messages' => [controllerTrans('variables.updated_text', $this->lang)],
            'redirect' => urlForLanguage($this->lang, 'edititem/'.$id)
        ]);
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
