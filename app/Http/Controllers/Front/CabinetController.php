<?php

namespace App\Http\Controllers\Front;

use App\Models\Basket;
use App\Models\BasketId;
use App\Models\GoodsItemId;
use App\Models\Orders;
use App\Models\ReviewsGoods;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\ExpressCheckout;

class CabinetController extends Controller
{
    protected $provider;
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function userCabinet()
    {
        $view = 'front.pages.cabinet';

        $user = null;

        if (!empty(Session::get('session-front-user')) && is_int(Session::get('session-front-user'))) {
            $user = FrontUser::where('id', Session::get('session-front-user'))
                ->where('active', 1)
                ->first();
        }

        if (is_null($user))
            return redirect($this->lang);

        $front_user_orders = Orders::where('active', 1)
            ->where('deleted', 0)
            ->where('front_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $order_items = [];
        if (!empty($front_user_orders)) {
            foreach ($front_user_orders as $one_user_order) {
                $order_items[$one_user_order->id] = Basket::where('basket_id', $one_user_order->basket_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        $meta_static = '';
        $meta_static = ShowLabelById(11, $this->lang_id).' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());
    }

    public function showUserOrders($request,$order = null)
    {
//        $order_id = $request->input('order_id');
        if($order == null) {

            if (Session::get('session-front-user')) {
                $front_user_orders = Orders::where('active', 1)
                    ->where('deleted', 0)
                    ->with('ordersData')
                    ->with('basket')
                    ->where('front_user_id', Session::get('session-front-user'))
                    ->orderBy('created_at', 'desc')
                    ->get();

                $meta_static = '';
                $meta_static = ShowLabelById(7, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');

                $view = 'front.pages.orders';
            } else {
                return 0;
            }

        }else {
            $one_order = Orders::where('active', 1)
                ->where('deleted', 0)
                ->where('id', $order)
                ->with('ordersData')
                ->with('ordersUsers')
                ->with(['basket' => function ($q) {
                    $q->with('oImage');
                }])
                ->where('front_user_id',Session::get('session-front-user'))
                ->orderBy('created_at', 'asc')
                ->first();


            if(!$one_order){
                return abort(404, 'Unauthorized action.');
            }


            $view = 'front.pages.onde_order';


            $meta_static = '';
            $meta_static = ShowLabelById(191, $this->lang_id).$one_order->id. ' - ' . env('APP_NAME') ?? env('APP_NAME');
        }


    return view($view, get_defined_vars());
    }

    public function repeatUserOrder(Request $request)
    {
        $order_id = $request->input('order_id');

        if ($order_id)
            $front_user_order = Orders::where('active', 1)
                ->where('deleted', 0)
                ->where('id', $order_id)
                ->where('front_user_id', getAuthorizedUser()->id)
                ->orderBy('created_at', 'desc')
                ->first();
        else
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ]);

        $front_user_order_items = [];
        if ($front_user_order)
            $front_user_order_items = Basket::where('basket_id', $front_user_order->basket_id)
                ->orderBy('created_at', 'desc')
                ->get();

        if (!empty($front_user_order_items)) {

            $basket_id = new BasketId();
            $basket_id->user_ip = $request->ip();
            $basket_id->save();

            Cookie::queue('basket', $basket_id->id, 45000);

            foreach ($front_user_order_items as $one_order_item) {

                $goods_item_id = GoodsItemId::where('id', $one_order_item->goods_item_id)
                    ->where('active', 1)
                    ->with('itemByLang')
                    ->first();

                if ($goods_item_id) {
                    $save_basket = new Basket();
                    $save_basket->basket_id = $basket_id->id;
                    $save_basket->goods_item_id = $goods_item_id->id;
                    $save_basket->items_count = $one_order_item->items_count;
                    $save_basket->goods_price = $goods_item_id->price;
                    $save_basket->goods_name = $goods_item_id->itemByLang->name;
                    $save_basket->save();
                } else
                    return response()->json([
                        'status' => false,
                        'message' => 'Goods not found'
                    ]);
            }
        }

        return response()->json([
            'status' => true,
            'redirect' => url($this->lang, 'cart'),
        ]);
    }

    public function changePass(Request $request) {

        $view = 'front.pages.change-pass';

        $meta_static = '';
        $meta_static = ShowLabelById(118, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());
    }

    public function changePassAjax(Request $request) {

        $user = FrontUser::where('id', Session::get('session-front-user'))
            ->first();

        $validator = Validator::make($request->all(), [
            'old_pass' => 'required',
            'new_pass' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);

        if (!empty($request->input('old_pass'))) {

            if (!empty($user) && Hash::check($request->input('old_pass'), $user->password) == false) {
                $user = null;
            }

            if ($user == null)
                return response()->json([
                    'status' => false,
                    'message' => 'Неправильный старый пароль',
                ]);





            $user->password = bcrypt($request->input('password'));

            return response()->json([
                'status' => true,
                'message'=>'Пароль изменен'
            ]);

        }



    }



/*if (!empty($request->input('current_password'))) {

if (!empty($user) && Hash::check($request->input('current_password'), $user->password) == false) {
$user = null;
}

if ($user == null)
    return response()->json([
        'status' => false,
        'message' => ShowLabelById(108, $this->lang_id),
    ]);

$validator = Validator::make($request->all(), [
    'current_password' => 'required',
    'password' => 'required|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/',
]);

if ($validator->fails())
    return response()->json([
        'status' => false,
        'messages' => $validator->messages(),
    ]);

$user->password = bcrypt($request->input('password'));
}*/


    public function newReview(Request $request)
    {

        //check if user have reviewed before

        $user_in_review = ReviewsGoods::where('front_user_id', Session::get('session-front-user'))
            ->where('goods_id', $request->input('item_id'))
            ->first();


        if (empty($user_in_review)) {
            $validator = Validator::make($request->all(), [
                'rating' => 'required',
            ]);


            if ($validator->fails())
                return response()->json([
                    'status' => false,
                    'messages' => $validator->messages(),
                ]);

            $user = new ReviewsGoods();
            $user->front_user_id = Session::get('session-front-user');
            $user->goods_id = $request->input('item_id');
            $user->body = $request->input('body');
            $user->rating = $request->input('rating');
            $user->active = 1;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Благодарим вас за отзыв :)'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Вы уже давали отзыв на этот товар'
            ]);
        }
    }

    public function saveUserData(Request $request)
    {

        //check if user have reviewed before
        /*
                $user_in_review = ReviewsGoods::where('front_user_id', Session::get('session-front-user'))
                    ->where('goods_id', $request->input('item_id'))
                    ->first();*/
        $user = FrontUser::where('id', Session::get('session-front-user'))->first();

        $curient_email_user = FrontUser::where('id',Session::get('session-front-user'))->where('email',$request->input('email'))->first();

        if($user->facebook_id == null && $user->google_id == null) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'last_name' => 'required',
                'email' => 'email',
                'phone' => 'nullable|numeric'
            ]);

            if ($validator->fails())
                return response()->json([
                    'status' => false,
                    'messages' => $validator->messages(),
                ]);

            $mailexist = FrontUser::where('email', $request->input('email'))->first();
            if (empty($mailexist) || $mailexist == $curient_email_user) {

                FrontUser::where('id', Session::get('session-front-user'))
                    ->update(['name' => $request->input('name'), 'last_name' => $request->input('last_name'), 'email' => $request->input('email'), 'phone' => $request->input('phone')]);


                return response()->json([
                    'status' => true,
                    'message' => 'Данные изменены :)',
                    'redirect' => 'cabinet'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Данный mail уже используется'
                ]);
            }

        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'last_name' => 'required',
                'phone' => 'nullable|numeric'
            ]);
            if ($validator->fails())
                return response()->json([
                    'status' => false,
                    'messages' => $validator->messages(),
                ]);

            FrontUser::where('id', Session::get('session-front-user'))
                ->update(['name' => $request->input('name'), 'last_name' => $request->input('last_name'), 'email' => $request->input('email'), 'phone' => $request->input('phone')]);

            return response()->json([
                'status' => true,
                'message' => 'Данные изменены :)',
                'redirect' => 'cabinet'
            ]);

       }






     /*   if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);



        FrontUser::where('id', Session::get('session-front-user'))
            ->update(['name' => $request->input('name'), 'last_name' => $request->input('last_name'), 'email' => $request->input('email'), 'phone' => $request->input('phone')]);
*/


    }
}


