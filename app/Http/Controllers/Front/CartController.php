<?php

namespace App\Http\Controllers\Front;

use App\Models\MenuId;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\BasketId;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\GoodsSubjectId;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function calculate_basket($basket/*, $change_delivery = 'chisinau'*/)
    {

        $ret = [];
        $ret['total_price'] = 0;
        $ret['total_item_price'] = [];

        if (!empty($basket)) {

            foreach ($basket as $one_item) {
                $goods_item_price = $one_item->goodsItemId->price;
                $ret['total_price'] += $goods_item_price * $one_item->items_count;
                $ret['total_item_price'][$one_item->id] = $goods_item_price * $one_item->items_count;
            }
        }

        return $ret;
    }

    public function index(Request $request)
    {
        $view = 'front.pages.cart-page';

        $cookie_basket = $request->cookie('basket');
        $lang_id = $this->lang_id;
        $meta_static = '';
        $basket = [];
        $total_price = 0;
        $total_price_items = 0;
        $delivery_price = env('DELIVERY_PRICE');

        if (!is_null($cookie_basket)) {

            $basket_id = BasketId::where('id', $cookie_basket)->first();

            if (!is_null($basket_id)) {
                $basket = Basket::where('basket_id', $basket_id->id)
                    ->get();

                $deleted_items = [];
                $deleted_item_name = [];

                $basket_items_count = $basket->count('items_count');

                if (!empty($basket)) {

                    foreach ($basket as $key => $one_item) {

                        $goods_item[$one_item->id] = GoodsItemId::where('active', 1)
                            ->where('deleted', 0)
                            ->where('goods_item_id.id', $one_item->goods_item_id)
                            ->with('ItemByLang')
                            ->first();

                        if (empty($goods_item[$one_item->id]) || $goods_item[$one_item->id]->active == 0 || $goods_item[$one_item->id]->deleted == 1) {
                            $deleted_items[$key] = Basket::where('id', $one_item->id)->first();
                            Basket::where('id', $one_item->id)->delete();
                        }
                    }

                    $vars = $this->calculate_basket($basket);
                    $total_price = $vars['total_price'];
                    $total_item_price = $vars['total_item_price'];
                }

                $basket = Basket::where('basket_id', $basket_id->id)
                    ->get();

                if ($basket->isEmpty())
                    $basket = [];
            }
        }

        $meta_static = ShowLabelById(3, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());
    }

    public function ajaxAddToCart(Request $request)
    {
        $goods_id = $request->input('id');
        $number_count = $request->input('number');
        $cookie_basket = Cookie::get('basket');

        $front_count = !is_null($number_count) && $number_count > 0 ? $number_count : 1;

        $goods_item_id = GoodsItemId::where('id', $goods_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        if (is_null($goods_item_id))
            return response()->json([
                'status' => false,
                'text' => 'Product not found'
            ]);

        $basket = null;
        $basket_id = null;

        $basket_id = BasketId::updateOrCreate(['id' => $cookie_basket], [
            'user_ip' => $request->ip()
        ]);

        if ($basket_id) {
            $basket = Basket::where('goods_item_id', $goods_item_id->id)
                ->where('basket_id', $basket_id->id)
                ->first();

            $if_has_basket_id = $basket ? $basket->id : 0;

            Basket::updateOrCreate(['id' => $if_has_basket_id], [
                'basket_id' => $basket_id->id,
                'goods_item_id' => $goods_item_id->id,
                'alias_item'=>$goods_item_id->alias,
                'items_count' => $front_count,
                'goods_price' => $goods_item_id->price,
                'goods_one_c_code' => $goods_item_id->one_c_code,
                'goods_name' => IfHasName($goods_item_id->id, $this->lang_id, 'goods_item'),
            ]);

            Cookie::queue('basket', $basket_id->id, 45000);
        } else {
            return response()->json([
                'status' => false,
                'text' => 'Basket not found'
            ]);
        }

        $count_all_goods = Basket::where('basket_id', $basket_id->id)->count('id');

        $all_basket_items = Basket::where('basket_id', $basket_id->id)
            ->get();

        $total_price = 0;

        if (!empty($all_basket_items)) {
            $vars = $this->calculate_basket($all_basket_items);
            $total_price = $vars['total_price'];
        }

        return response()->json([
            'status' => true,
            'basket_count' => $count_all_goods,
            'total_price' => $total_price,
            'message' => ShowLabelById(180, $this->lang_id),
            //'type' => 'to_cart'
        ]);
    }

    public function diffSumItemCart(Request $request)
    {
        $goods_item = $request->input('id');
        $cookie_basket = $request->cookie('basket');
        $page = $request->input('page');
        $number = $request->input('number');


        if (!is_null($number) && $number > 0) {
            $item_count = $number;
        } else {
            $item_count = 1;
        }

        /*if(!empty($page) && $page == 'main-page'){

            $goods_item_id = GoodsItemId::where('id', $goods_item)
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            $total_item_price = $item_count * $goods_item_id->price;

            return response()->json([
                'status' => true,
                'total_item_price' => $total_item_price,
                'page' => $page,
                'number' => $item_count
            ]);
        }*/

        $basket_one_item = '';
        $basket_one_item_price = '';
        $total_price = '';
        $total_item_price = '';
        //$delivery_price = env('DELIVERY_PRICE');

        if (!empty($page) && $page == 'cart') {

            $basket = Basket::where('goods_item_id', $goods_item)
                ->where('basket_id', $cookie_basket)
                ->first();


            if (is_null($basket) || is_null($cookie_basket)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Empty basket'
                ]);
            }

            Basket::where('goods_item_id', $goods_item)
                ->where('basket_id', $cookie_basket)
                ->update(['items_count' => $item_count]);

            $basket_one_item = Basket::where('goods_item_id', $goods_item)
                ->where('basket_id', $cookie_basket)
                ->first();

            $basket_one_item_price = $basket_one_item->goodsItemId->price;

            $total_item_price = $item_count * $basket_one_item_price;
        }

        //$count_all_goods = Basket::where('basket_id', $cookie_basket)->sum('items_count');
        $count_all_goods = Basket::where('basket_id', $cookie_basket)->count('id');

        $all_basket_items = Basket::where('basket_id', $cookie_basket)->get();

        if (!empty($all_basket_items)) {
            $vars = $this->calculate_basket($all_basket_items);
            $total_price = $vars['total_price'];
        }

        return response()->json([
            'status' => true,
            'basket_count' => $count_all_goods,
            'basket_count_item' => $basket_one_item->items_count,
            'item_price' => $basket_one_item_price,
            //'sub_total' => $total_price,
            'total_price' => $total_price,
            'total_item_price' => $total_item_price,
        ]);
    }

    public function destroyItemCart(Request $request)
    {
        $goods_item = $request->input('id');
        $cookie_basket = $request->cookie('basket');

        $basket = Basket::where('goods_item_id', $goods_item)
            ->where('basket_id', $cookie_basket)
            ->first();

        if (is_null($basket) || is_null($cookie_basket))
            return response()->json([
                'status' => false,
                'message' => 'Basket item is empty'
            ]);

        Basket::where('goods_item_id', $goods_item)
            ->where('basket_id', $cookie_basket)
            ->delete();

        $count_all_goods = Basket::where('basket_id', $cookie_basket)->count('id');

        $basket_item_after_delete = Basket::where('basket_id', $cookie_basket)
            ->count();

        if ($basket_item_after_delete < 1) {
            BasketId::where('id', $cookie_basket)->delete();

            if (!is_null(Cookie::get('basket'))) {
                Cookie::queue(Cookie::forget('basket'));
            }
        }

        $all_basket_items = Basket::where('basket_id', $cookie_basket)->get();

        $total_price = 0;
        //$delivery_price = env('DELIVERY_PRICE');

        if (!empty($all_basket_items)) {
            $vars = $this->calculate_basket($all_basket_items);
            $total_price = $vars['total_price'];
        }

        return response()->json([
            'status' => true,
            'basket_count' => $count_all_goods,
            //'sub_total' => $total_price,
            'total_price' => $total_price,
            'message' => ShowLabelById(161, $this->lang_id),
        ]);
    }
}

