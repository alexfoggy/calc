<?php


namespace App\Http\Controllers\Front;


use App\Http\Controllers\Controller;

use App\Models\FrontUser;
use App\Models\TrackNumber;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Session;


class DefaultControllerApi extends Controller
{

    private $lang_id;

    private $lang;

    public function __construct()

    {

        $this->lang_id = $this->lang()['lang_id'];

        $this->lang = $this->lang()['lang'];

    }

    public function index(Request $request, $parent = null, $children = null)
    {

        switch ($parent) {

            case 'user':
                switch ($children) {
                    case 'register':
                        $validator = Validator::make($request->all(), [
                            'firstName' => 'required|min:2|max:50',
                            'lastName' => 'required|min:2|max:50',
                            'userName' => 'required|unique:front_user,userName|min:2|max:50',
                            'email' => 'required|email|unique:front_user,email|max:255|',
                            'password' => 'required',


                        ]);

                        if ($validator->fails())
                            return response()->json([
                                'status' => false,
                                'messages' => $validator->messages(),
                            ]);

                        $user = new FrontUser();

                        $user->first_name = $request->input('firstName');
                        $user->email = $request->input('email');
                        $user->userName = $request->input('userName');
                        $user->last_name = $request->input('lastName');
                        $user->password = bcrypt($request->input('password'));
                        $user->save();

                        return response()->json([
                            'status' => true,
                            'message' => 'done',
                        ]);

                        break;
                    case 'login':
                        $validator = Validator::make($request->all(), [

                            'userName' => 'required',

                            'password' => 'required',

                        ]);
                        if ($validator->fails())
                            return response()->json([
                                'status' => false,
                                'messages' => $validator->messages(),
                            ]);

                        $user = FrontUser::where('userName', $request->input('userName'))->first();

                        if ($user && Hash::check($request->input('password'), $user->password) == false)
                            $user = null;

                        if (is_null($user))
                            return response()->json([
                                'status' => false,
                                'info' => $user,
                                'info2' => $request->input('password'),
                                'info3' => $request->input('userName'),
                                'message' => 'Incorrect Login or Password'
                            ]);
                        return response()->json([
                            'status' => true,
                            'data' => $user,
                        ]);
                        break;
                    default:
                        dd('no-children');
                        break;
                }
                break;
            case 'track_number':
                switch ($children) {
                    case 'create':
                        $validator = Validator::make($request->all(), [
                            'trackNumber' => 'required|min:2|max:50',
                            'userId' => 'required'
                        ]);

                        if ($validator->fails()) {
                            return response()->json([
                                'status' => false,
                                'messages' => $validator->messages(),
                            ]);
                        }
                        $track = new TrackNumber();
                        $track->track_number = $request->input('trackNumber');
                        $track->user_id = $request->input('userId');
                        $track->is_active = 1;
                        $track->save();
                        return response()->json([
                            'status' => true,
                            'item' => $track,
                            'message' => 'done',
                        ]);

                        break;
                    case 'get':
                        $trackNumbers = TrackNumber::where('user_id', $request->input('userId'))->get();
                        return response()->json([
                            'status' => true,
                            'data' => $trackNumbers,
                        ]);
                        break;
                    default:
                        dd('no-children');
                        break;
                }
                break;
            default:
                dd('no api');
        }

        return response()->json([
            'status' => true,
            'message' => ShowLabelById(112, $this->lang_id),
            'redirect' => url('login-' . $this->lang)

        ]);

    }

    /*public function loginIndex()

    {

        $view = 'front.pages.user-auth.login-page';



        if (Session::get('session-front-user'))

            return redirect(url($this->lang));



        return view($view, get_defined_vars());

    }*/

    public function registerNewFrontUser(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|min:2|max:50',

            'email' => 'required|email|unique:front_user,email|max:255|',

            'phone' => 'required|min:6|max:15',

            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/',


        ]);


        if ($validator->fails())

            return response()->json([

                'status' => false,

                'messages' => $validator->messages(),

            ]);


        $user = new FrontUser();

        $user->name = $request->input('name');

        $user->email = $request->input('email');

        $user->phone = $request->input('phone');

        $user->password = bcrypt($request->input('password'));

        $user->save();


        if (empty($user))

            return response()->json([

                'status' => false,

                'text' => trans('variables.something_wrong'),

            ]);


        Session::put('session-front-user', $user->id);


        return response()->json([

            'status' => true,

            'message' => showLabelById(106, $this->lang_id),

            //'redirect' => url($this->lang, 'register-success'),

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


        $user = FrontUser::where('email', $request->input('email'))->first();


        if ($user && Hash::check($request->input('password'), $user->password) == false) {

            $user = null;

        }


        if (is_null($user))

            return response()->json([

                'status' => false,

                'message' => showLabelById(110, $this->lang_id)

            ]);


        if (!empty($user)) {

            Session::put('session-front-user', $user->id);

        }


        if ($request->input('remember') == 'on') {

            Cookie::queue('front-user-remember', $user->id, env('COOKIE_USER_REMEMBER_TIME'));

        }


        return response()->json([

            'status' => true,

            'message' => showLabelById(111, $this->lang_id),

            'redirect' => url('/'),

        ]);

    }


    public function FrontUserLogout()

    {

        Session::forget('session-front-user');

        Cookie::queue(Cookie::forget('front-user-remember'));

        return back();

    }


    /*public function restorePasswordIndex()

    {

        $view = 'front.pages.user-auth.restore-password-page';



        return view($view, get_defined_vars());

    }*/


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


        if (reCaptchaVersionThree($request->input('g-recaptcha-response')) == false)

            return response()->json([

                'status' => false,

                'messages' => ['Spam'],

            ]);


        $user = FrontUser::where('email', $request->input('email'))->first();


        if (is_null($user))

            return response()->json([

                'status' => false,

                'message' => ShowLabelById(115, $this->lang_id)

            ]);


        $hash_locale = sha1($request->input('email') . time());


        FrontUser::where('email', $request->input('email'))->update(['recovery_hash' => $hash_locale]);


        $my_email = $user->email;


        $subject = ShowLabelById(114, $this->lang_id);


        Mail::send('front.email.emailForgetPassword', ['hash_locale' => $hash_locale], function ($message) use ($my_email, $subject) {

            $message->from(showSettingBodyByAlias('send-email-from', $this->lang_id), ShowLabelById(76, $this->lang_id));

            $message->to($my_email);

            $message->subject($subject);

        });


        return response()->json([

            'status' => true,

            'message' => ShowLabelById(113, $this->lang_id)

        ]);

    }


    /*public function newPasswordIndex()

    {

        $view = 'front.pages.user-auth.new-password-page';



        $recovery_user = null;

        $hash = null;



        $hash = request()->input('h');

        if (!empty($hash))

            $recovery_user = FrontUser::where('recovery_hash', $hash)->first();

        else

            return redirect($this->lang .'/');



        return view($view, get_defined_vars());

    }*/


    public function newPassword(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/',

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

            'message' => ShowLabelById(112, $this->lang_id),

            'redirect' => url('login-' . $this->lang)

        ]);

    }

}

