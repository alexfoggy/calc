<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FeedForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cookie;

class CustomAuthController extends Controller
{

    private $lang;

    public function __construct()
    {
        $this->lang = $this->lang()['lang'];
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post'))
            return $this->checkLogin(Request);

        if(Auth::user()){
            return redirect($this->lang()['lang'].'/back');
        }

        $view = 'auth.login';

        session()->put('backUrl', $this->lang.'/back');

        return view($view, get_defined_vars());
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post'))
            return $this->checkRegister();

        $admin_user = User::get();

        if(count($admin_user) != 0){
            return redirect($this->lang()['lang'].'/back/auth/login');
        }


        $view = 'auth.register';


        return view($view, get_defined_vars());
    }



    public function checkLogin(Request $request)
    {
        $messages = array();
        $item = Validator::make($request->all(), [
            'login' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        if ($item->fails())
            $messages = $item->messages();

        if (count($messages))
            return response()->json([
                'status' => false,
                'messages' => $messages,
            ]);

        //checkAuthFunction(true, Input::get('login'), Input::get('password'), Input::get('_token'));
        if (!Auth::attempt(array('login' => $request->input('login'), 'password' => $request->input('password'))))
            return response()->json([
                'status' => false,
                'messages' => [Lang::get('variables.user_not_exist', array(), $this->lang()['lang'])],
            ]);

        //Feedform
        $feedform = FeedForm::where('seen', 0)->count();
        if($feedform > 0)
            Session::flash('feedform', controllerTrans('variables.feedform_count', $this->lang, ['digits' => '<span>' . $feedform . '</span>']));
//            Session::flash('feedform', 'You have ' . $feedform . ' new feedforms!');
        //Feedform


        //Comments
        /*$comments = GoodsItemComments::where('seen', 0)->count();
        if($comments > 0)
            Session::flash('comment', controllerTrans('variables.comments_count', $this->lang, ['digits' => '<span>' . $comments . '</span>']));*/
//            Session::flash('comment', 'You have ' . $comments . ' new comments!');
        //Comments

        setcookie('__amc', md5(md5('File man cookie security!') . date('Y.m.d') ), time() + (86400 * 30), "/");

//        Log::useFiles(storage_path().'/logs/authenticates.log');
        Log::info('Name: ' . Auth::user()->name);

        if(Session::has('backUrl')){
            return response()->json([
                'status' => true,
                'messages' => [Lang::get('variables.login_success_full', array(), $this->lang()['lang'])],
                'redirect' => url(Session::get('backUrl')),
            ]);
        }

        return response()->json([
            'status' => true,
            'messages' => [trans('variables.login_success_full')],
            'redirect' => url($this->lang()['lang'].'/back'),
        ]);

    }


    public function checkRegister()
    {

        $messages = array();
        $item = Validator::make(Input::all(), [
            'name' => 'required',
            'login' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:4',
            'repeat_password' => 'required|same:password',
        ]);

        if ($item->fails())
            $messages = $item->messages();

        if (count($messages))
            return response()->json([
                'status' => false,
                'messages' => $messages,
            ]);

        $find_user = User::where('email', '=', Input::get('email'))->first();

        if ($find_user)
            return response()->json([
                'status' => false,
                'messages' => [Lang::get('variables.user_exist', array(), $this->lang()['lang'])],
            ]);

        User::create([
            'name' => Input::get('name'),
            'login' => Input::get('login'),
            'email' => Input::get('email'),
            'password' => bcrypt(Input::get('password')),
            'remember_token' => Input::get('_token'),
            'admin_user_group_id' => 1,

        ]);


        return response()->json([
            'status' => true,
            'messages' => [Lang::get('variables.register_success_full', array(), $this->lang()['lang'])],
            'redirect' => url($this->lang()['lang'].'/back/auth/login'),
        ]);
    }

    public function logout()
    {
        Auth::logout();

        //checkAuthFunction(false);

        Cookie::queue(Cookie::forget('__amc'));

        return redirect($this->lang()['lang'].'/back/auth/login');
    }
}