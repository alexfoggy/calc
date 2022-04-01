<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\BasketId;
use App\Models\FrontUser;
use App\Models\GoodsItemId;
use App\Models\Orders;
use App\Models\OrdersData;
use App\Models\OrdersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function newOrder(Request $request)
    {
        if (!empty($request->all()))
            $item = Validator::make($request->all(), [
                'name' => 'required|min:2|max:30',
                'last_name' => 'required|min:2|max:30',
                'phone' => 'required|min:8',
                'email' => 'required|email|max:100',
                'pay_method' => 'required',
//                'agree' => 'required'
            ]);

        if ($item->fails())
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);

//        if (reCaptchaVersionThree($request->input('g-recaptcha-response')) == false)
//            return response()->json([
//                'status' => false,
//                'messages' => ['Spam'],
//            ]);

        if (!empty($request->cookie('basket'))) {

            $basket_id = BasketId::where('id', $request->cookie('basket'))->first();

            $basket = Basket::where('basket_id', $request->cookie('basket'))->get();

            if (!empty($basket) && count($basket)) {

                foreach ($basket as $key => $one_item) {

                    $goods_item_id = GoodsItemId::where('id', $one_item->goods_item_id)
                        ->select('*', 'goods_item_id.id as id')
                        ->first();

                    if ($goods_item_id)
                        Basket::where('id', $one_item->id)->update([
                            'goods_price' => $goods_item_id->price
                        ]);
                }

                $total_price = 0;
                $total_count = 0;
                //$delivery_price = env('DELIVERY_PRICE');

                if (!empty($basket)) {
                    foreach ($basket as $new_basket) {
                        $total_price += $new_basket->items_count * $new_basket->goods_price;
                        $total_count += $new_basket->items_count;
                    }
                }

                $order_new = new Orders();
                $order_new->basket_id = $basket_id->id;
                $order_new->pay_method = $request->input('pay_method');

                if (Session::get('session-front-user'))

                    $order_new->front_user_id = Session::get('session-front-user');

                $order_new->active = 1;
                $order_new->deleted = 0;
                $order_new->save();

                $user_order = Orders::where('id', $order_new->id)->first();

                if (!empty($user_order)) {

                    $orders_data = new OrdersData();
                    $orders_data->orders_id = $user_order->id;
                    $orders_data->total_price = $total_price;
                    $orders_data->total_count = $total_count;
                    $orders_data->save();

                    $orders_users = new OrdersUsers();
                    $orders_users->orders_id = $user_order->id;
                    $orders_users->user_ip = $request->ip();
                    $orders_users->name = $request->input('name');
                    $orders_users->last_name = $request->input('last_name');
                    $orders_users->phone = $request->input('phone');
                    $orders_users->email = $request->input('email');
                    $orders_users->address = $request->input('address');
                    $orders_users->city = $request->input('city');
                    $orders_users->descr = $request->input('descr');
                    $orders_users->save();

                    if (getAuthorizedUser()) {
                        if (getAuthorizedUser()->phone == null)
                            getAuthorizedUser()->update(['phone' => $request->input('phone')]);
                    }
                }

                Cookie::queue(Cookie::forget('basket'));

                $new_orders_data = OrdersData::where('id', $orders_data->id)->first();

                if ($new_orders_data->email_sent == 0) {

                    $this->SendEmail($request, $new_orders_data->orders_id);

                    OrdersData::where('id', $new_orders_data->id)->update(['email_sent' => 1]);
                }

                Session::put('if-checkout-success', 1);
               // Session::put('order-id', $new_orders_data->orders_id);

                return response()->json([
                    'status' => true,
                    'message' => ShowLabelById(109, $this->lang_id),
                    'redirect' => url($this->lang, 'checkout-success'),
                ]);

            } else
                return response()->json([
                    'status' => false,
                    'text' => trans('variables.something_wrong')
                ]);
        } else
            return response()->json([
                'status' => false,
                'text' => trans('variables.empty_cart')
            ]);
    }

    public function SendEmail(Request $request, $id)
    {
        $orders = Orders::where('id', $id)->first();

        $basket = [];
        if (!empty($orders)) {
            $basket = Basket::where('basket_id', $orders->basket_id)->get();
        }

        $orders_data = OrdersData::where('orders_id', $id)->select('*', 'orders_id as id')->first();
        $orders_user = OrdersUsers::where('orders_id', $id)->first();

        $total_price = 0;
        $orderItems = [];

        if (!empty($basket)) {
            foreach ($basket as $one_basket) {
                $item = GoodsItemId::where('id', $one_basket->goods_item_id)->first();
                $item->count = $one_basket->items_count;

                if (!is_null($item)) {
                    $total_price += $one_basket->goods_price * $one_basket->items_count;
                    $orderItems[] = $item;
                }
            }
        }

        $my_email = null;
        if (!empty($orders_user->email))
            $my_email = $orders_user->email;

        $subject = str_replace('{site_name}', env('APP_DOMAIN'), ShowLabelById(182, $this->lang_id));
        $subject_for_admin = ShowLabelById(181, $this->lang_id);

        $if_admin = 0;

        if (filter_var($my_email, FILTER_VALIDATE_EMAIL)) {
            Mail::send('front.email.emailNewOrder', ['orders' => $orders, 'basket' => $basket, 'orders_data' => $orders_data, 'orders_user' => $orders_user, 'total_price' => $total_price, 'if_admin' => $if_admin], function ($message) use ($my_email, $subject) {
                $message->from(showSettingBodyByAlias('send-email-from', $this->lang_id), ShowLabelById(184, $this->lang_id));
                $message->to($my_email);
                $message->subject($subject);
            });
        }

        $if_admin = 1;

        if (filter_var(showSettingBodyByAlias('email-phone', $this->lang_id), FILTER_VALIDATE_EMAIL)) {
            Mail::send('front.email.emailNewOrder', ['orders' => $orders, 'basket' => $basket, 'orders_data' => $orders_data, 'orders_user' => $orders_user, 'total_price' => $total_price, 'if_admin' => $if_admin], function ($message) use ($my_email, $subject_for_admin) {
                $message->from(showSettingBodyByAlias('send-email-from', $this->lang_id), str_replace('{site_name}', env('APP_DOMAIN'), ShowLabelById(182, $this->lang_id)));
                $message->to(showSettingBodyByAlias('email-phone', $this->lang_id));
                $message->subject($subject_for_admin);
            });
        }
    }

    /*public function checkoutSuccess()
    {
        if (Session::get('if-checkout-success') == 1) {
            $view = 'front.pages.order-success-page';

            $order_id = Session::get('order-id');

            Session::forget('if-checkout-success');
            Session::forget('order-id');
        } else {
            return redirect(url($this->lang));
        }

        return view($view, get_defined_vars());

    }*/
    public function orderSuccess()
    {
        if (Session::get('if-checkout-success') == 1) {
            $view = 'front.pages.checkout-success';
            Session::forget('if-checkout-success');
        } else {
            return redirect(url($this->lang));
        }

        return view($view, get_defined_vars());

    }
}

