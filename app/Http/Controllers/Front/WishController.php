<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CalcId;
use App\Models\GoodsItemId;
use App\Models\MenuId;
use App\Models\Wish;
use App\Models\WishId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class WishController extends Controller
{
    protected $provider;
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function wish(Request $request)
    {
        $view = 'front.pages.FavCals';

        $page = MenuId::where('alias',$request->segment(2))->with('itemByLang')->first();

        $cookie_wish = $request->cookie('wish');
        $meta_static = '';
        $wish = [];

        if (!is_null($cookie_wish)) {
            $wish_id = WishId::where('id', $cookie_wish)->first();

            if (!is_null($wish_id)) {
                $wish = Wish::where('wish_id', $wish_id->id)->get();

                if ($wish->isEmpty())
                    $wish = [];
            }
        }

        $wish_item_id_arr = [];
        if (!empty($wish)) {
            foreach ($wish as $item) {
                $wish_item_id_arr[] = $item->goods_item_id;
            }
            $wish_item_id_arr = array_filter($wish_item_id_arr);
        }

        $calcs = [];
        if (!empty($wish_item_id_arr)) {
            $calcs = CalcId::whereIn('id', $wish_item_id_arr)
                ->where('active', 1)
                ->where('deleted', 0)
                ->with('itemByLang')
                ->with('parent')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $meta_static = $page->itemByLang->name ?? '' . ' - ' . env('APP_NAME') ?? env('APP_NAME');

        return view($view, get_defined_vars());
    }

    public function ajaxWish(Request $request)
    {
        $goods_id = $request->input('goods_id');
        //$wish_item = $request->input('wish');
        $cookie_wish = $request->cookie('wish');

        $goods_item_id = GoodsItemId::where('id', $goods_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        if (is_null($goods_item_id))
            return response()->json([
                'status' => false
            ]);

        $maxPosition = GetMaxPosition('wish');
        $wish = null;

        if (!is_null($cookie_wish)) {
            $wish = Wish::where('goods_item_id', $goods_item_id->id)
                ->where('wish_id', $cookie_wish)
                ->first();
        }

        if (!is_null($wish)) {

            Wish::where('goods_item_id', $goods_item_id->id)
                ->where('wish_id', $cookie_wish)
                ->delete();


            $wish_after_delete = Wish::where('wish_id', $cookie_wish)
                ->count();

            if ($wish_after_delete < 1) {
                WishId::where('id', $cookie_wish)->delete();

                if (!is_null($request->input('wish'))) {
                    Cookie::queue(Cookie::forget('wish'));
                }
            }

            //Count for header
            $get_cookie_wish = $request->cookie('wish');
            $get_wish_count = Wish::where('wish_id', $get_cookie_wish)->count();

            return response()->json([
                'status' => true,
                'wish_item' => $get_wish_count
                //'wish_item' => 0
            ]);
        } else {

            $wish_id = WishId::where('id', $cookie_wish)->first();

            if (!is_null($wish_id)) {
                Wish::create([
                    'wish_id' => $wish_id->id,
                    'goods_item_id' => $goods_item_id->id,
                    'position' => $maxPosition + 1
                ]);

                //Count for header
                $get_cookie_wish = $request->cookie('wish');
                $get_wish_count = Wish::where('wish_id', $get_cookie_wish)->count();
            } else {
                $wish_id = WishId::create(['user_ip' => request()->ip()]);

                Wish::create([
                    'wish_id' => $wish_id->id,
                    'goods_item_id' => $goods_item_id->id,
                    'position' => $maxPosition + 1
                ]);
                $get_wish_count = 1;
            }

            if (!is_null($request->cookie('wish'))) {
                Cookie::queue(Cookie::forget('wish'));
            }

            Cookie::queue('wish', $wish_id->id, 45000);

        }

        return response()->json([
            'status' => true,
            'wish_item' => $get_wish_count
            //'wish_item' => 1
        ]);

    }

	public function destroyItemWish(Request $request)
	{
		$goods_item = $request->input('goods_id');
		$cookie_wish = Cookie::get('wish');

		$wish = Wish::where('goods_item_id', $goods_item)
		            ->where('wish_id', $cookie_wish)
		            ->first();

		if (is_null($wish) || is_null($cookie_wish))
			return response()->json([
				'status' => false
			]);

		Wish::where('goods_item_id', $goods_item)
		    ->where('wish_id', $cookie_wish)
		    ->delete();

        $count_all_goods = Wish::where('wish_id', $cookie_wish)->count('id');

		$wish_item_after_delete = Wish::where('wish_id', $cookie_wish)
		                              ->count();

		if ($wish_item_after_delete < 1) {
			WishId::where('id', $cookie_wish)->delete();

			if (!is_null(Cookie::get('wish'))) {
				Cookie::queue(Cookie::forget('wish'));
			}
		}

		return response()->json([
			'status' => true,
			'wish_count' => $count_all_goods,
			'message' => ShowLabelById(23, $this->lang_id)
		]);

	}

}

