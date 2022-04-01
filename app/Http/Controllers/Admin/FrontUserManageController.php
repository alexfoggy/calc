<?php

namespace App\Http\Controllers\Admin;

use App\Models\FrontUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FrontUserManageController extends Controller
{
    private $lang;
    private $lang_id;

    public function __construct()
    {
        $this->lang = $this->lang()['lang'];
        $this->lang_id = $this->lang()['lang_id'];
    }

    public function index()
    {
        $view = 'admin.front_user_manage.usersList';

        $users = FrontUser::get();
//      dd($users);
        return view($view, get_defined_vars());
    }

    public function createitem()
    {
        $view = 'admin.front_user_manage.create-users';

        return view($view, get_defined_vars());
    }

    public function editUser(Request $request, $id)
    {

        $view = 'admin.front_user_manage.edit-user';
        $user = FrontUser::where('id', $id)->first();

        return view($view, get_defined_vars());
    }

    public function save(Request $request, $id)
    {
        if (is_null($id)) {

            if (!empty($request->input('password'))) {
                $item = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'min:4',
                    'repeat_password' => 'same:password',
                ]);
            } else {
                $item = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                ]);
            }

            $data = array_filter([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'discount' => $request->input('discount'),
                'password' => bcrypt($request->input('password')),
                'remember_token' => $request->input('_token')
            ]);
            $create_user = FrontUser::create($data);

            if ($create_user) {
                return response()->json([
                    'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                    'redirect' => urlForLanguage($this->lang, '/')
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'messages' => $item,
                ]);
            }

        } else {
            if (!empty($request->input('password'))) {
                $data = array_filter([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'discount' => $request->input('discount'),
                    'password' => bcrypt($request->input('password')),
                    'remember_token' => $request->input('_token')

                ]);
            } else {
                $data = array_filter([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'discount' => $request->input('discount'),
                    'remember_token' => $request->input('_token')

                ]);
            }

            FrontUser::where('id', $id)->update($data);

            return response()->json([
                'status' => true,
                'messages' => [controllerTrans('variables.updated_text', $this->lang)],
                'redirect' => urlForLanguage($this->lang, 'edituser/' . $id)
            ]);


        }

    }

    public function destroyFrontUser(Request $request)
    {
        $deleted_elements_id = substr($request->input('id'), 1, -1);

        if (!empty($deleted_elements_id)) {
            $deleted_elements_id_arr = explode(',', $deleted_elements_id);

            $front_users_ids = FrontUser::whereIn('id', $deleted_elements_id_arr)->get();

            if (!$front_users_ids->isEmpty()) {

                $del_message = '';

                foreach ($front_users_ids as $one_front_user) {

                    $del_message .= $one_front_user->email . ', ';

                    FrontUser::destroy($one_front_user->id);
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
