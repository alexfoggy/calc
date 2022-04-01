<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsItemComments;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
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
        $view = 'admin.comments.comments-list';
        $lang_id = $this->lang_id;

        $comments = GoodsItemComments::orderBy('created_at', 'desc')
            ->paginate(40);

        $new_feedform = GoodsItemComments::where('seen', 0)->count();
        if($new_feedform > 0) {
            GoodsItemComments::where('seen', 0)->update(['seen' => 1]);
        }

        return view($view, get_defined_vars());
    }

    public function destroyCommentFromCart()
    {

        $deleted_elements_id = substr(Input::get('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $goods_item_elems_id = GoodsItemComments::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$goods_item_elems_id->isEmpty()) {

                $del_message = '';

                foreach ($goods_item_elems_id as $one_goods_item_elems_id) {
                    $del_message .= $one_goods_item_elems_id->name . ', ';

                    GoodsItemComments::where('id', $one_goods_item_elems_id->id)->delete();
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

    // ajax response for active
    public function changeActive()
    {
        $active = Input::get('active');
        $id = Input::get('id');

        $element_id = GoodsItemComments::findOrFail($id);

        if(!is_null($element_id))
            $element_name = GoodsItemComments::where('id', $id)->first()->name;
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

        GoodsItemComments::where('id', $id)->update(['active' => $change_active]);

        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [$msg]
        ]);


    }

    public function editItem($id)
    {
        $view = 'admin.comments.edit-comment';

        $comment = GoodsItemComments::where('id', $id)->first();

        if(is_null($comment)){
            return App::abort(503, 'Unauthorized action.');
        }

        return view($view, get_defined_vars());
    }

    public function save($id)
    {

        $item = Validator::make(Input::all(), [
            'body' => 'required',
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

        $comment = GoodsItemComments::where('id', $id)->first();

        if (is_null($comment))
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'messages' => [controllerTrans('variables.something_wrong', $this->lang)],
            ]);

        $data = [
            'body' => !is_null(Input::get('body')) ? Input::get('body') : '',
            'active' => Input::get('active') == 'on' ? 1 : 0
        ];

        GoodsItemComments::where('id', $comment->id)->update($data);

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