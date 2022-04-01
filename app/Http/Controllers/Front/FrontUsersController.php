<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use App\Models\TableId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class FrontUsersController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

   public function registerPage()
    {
        $view = 'front.pages.register-page';

        if (Session::get('session-front-user'))
            return redirect(url($this->lang));

        return view($view, get_defined_vars());
    }
    public function userPage(Request $request,$lang,$id)
    {
        $user = '';
        $view = 'front.pages.user-page';

            $user = FrontUser::where('active',1)
                ->where('id',$id)
                ->first();

            $tablesRelated = TableId::where('front_user_id',$id)->with('getUser')->with('getBody')->paginate(20);

            if(!$user){return redirect(url($this->lang));}
            //
//        if (Session::get('session-front-user'))
//            return redirect(url($this->lang));

        return view($view, get_defined_vars());
    }

        public function loginPage()
        {
            $view = 'front.pages.login-page';

            if (Session::get('session-front-user'))
                return redirect(url($this->lang));

            return view($view, get_defined_vars());
        }

    public function registerNewFrontUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:front_user,email|max:255|',
//            'phone' => 'required|min:6',
//            'sec_name' => 'required',
            'name' => 'required',
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/',
           'password_confirmation' => 'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);

        $user = new FrontUser();
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->name = $request->input('name');
        $user->last_name = $request->input('sec_name');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        if (empty($user))
            return response()->json([
                'status' => false,
                'text' => trans('variables.something_wrong'),
            ]);

        Session::put('session-front-user', $user->id);

        Session::put('if-register-success', 1);

        return response()->json([
            'status' => true,
            //'text' => showLabelById(84, $this->lang_id),
            'redirect' => url($this->lang, 'register-success'),
            //'redirect' => $this->lang . '/cabinet/orders'
        ]);
    }

    public function frontUserLogin(Request $request)
    {
        if (ifUserLogIn())
            return redirect('/');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);

        $user = FrontUser::where('email',   $request->input('email'))->first();



        if ($user && Hash::check($request->input('password'), $user->password) == false) {
            $user = null;
        }


        if (is_null($user))
            return response()->json([
                'status' => false,
                'message' => showLabelById(166, $this->lang_id)
            ]);

        if (!empty($user)) {
            Session::put('session-front-user', $user->id);
        }
        if ($request->input('remember') == 'on') {
            Cookie::queue('front-user-remember', $user->id, env('COOKIE_USER_REMEMBER_TIME'));
        }


        return response()->json([
            'status' => true,
            /*'message' => showLabelById(35, $this->lang_id),*/
            'redirect' => url($this->lang),
        ]);
    }

    public function FrontUserLogout()
    {
        Session::forget('session-front-user');
        Cookie::queue(Cookie::forget('front-user-remember'));
        return back();
    }

    public function restorePasswordIndex()
    {
        $view = 'front.pages.restore-password-page';


        $meta_static = '';
        $meta_static = ShowLabelById(186, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());
    }

    public function userRestorePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);

        /*$captcha = Input::get('g-recaptcha-response');

        if (reCaptcha($captcha, 'show') == false)
            return response()->json([
                'status' => false
            ]);*/

        $user = FrontUser::where('email', $request->input('email'))->first();

        if (is_null($user))
            return response()->json([
                'status' => false,
                'text' => ShowLabelById(115, $this->lang_id)
            ]);

        $hash_locale = sha1($request->input('email') . time());

        FrontUser::where('email', $request->input('email'))->update(['recovery_hash' => $hash_locale]);

        $my_email = $user->email;

        $subject = ShowLabelById(183,$this->lang_id);

        Mail::send('front.email.emailForgetPassword', ['hash_locale' => $hash_locale], function ($message) use ($my_email, $subject) {
            $message->from(showSettingBodyByAlias('send-email-from', $this->lang_id), ShowLabelById(67, $this->lang_id));
            $message->to($my_email);
            $message->subject($subject);
        });

        return response()->json([
            'status' => true,
            'message' => ShowLabelById(161,$this->lang_id)
        ]);
    }

    public function newPasswordIndex()
    {
        $view = 'front.pages.new-password-page';

        $recovery_user = null;
        $hash = null;

        $hash = request()->input('h');
        if (!empty($hash))
            $recovery_user = FrontUser::where('recovery_hash', $hash)->first();
        if(!$recovery_user)return redirect($this->lang .'/');
        else
            return redirect($this->lang .'/');

        return view($view, get_defined_vars());
    }

    public function newPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/|min:6',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);

        if (empty($request->input('hash')))
            return response()->json([
                'status' => false,
                'text' => trans('variables.something_wrong'),
            ]);

        $user = FrontUser::where('recovery_hash', $request->input('hash'))->first();

        if (is_null($user))
            return response()->json([
                'status' => false,
                'text' => trans('variables.something_wrong')
            ]);

        FrontUser::where('id', $user->id)->update(['password' => bcrypt($request->input('password')), 'recovery_hash' => null]);

        return response()->json([
            'status' => true,
            'message' => ShowLabelById(17,$this->lang_id),
            'redirect' => url($this->lang)
        ]);
    }

    public function registerSuccess()
    {
        if (Session::get('if-register-success') == 1) {
            $view = 'front.pages.register-success-page';
            Session::forget('if-register-success');
        } else {
            return redirect(url($this->lang));
        }
        $meta_static = '';
        $meta_static = ShowLabelById(158, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());

    }
}

