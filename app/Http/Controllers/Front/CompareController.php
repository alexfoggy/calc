<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Compare;
use App\Models\CompareId;
use App\Models\FrontUser;
use App\Models\GoodsItemId;
use App\Models\GoodsParametrId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompareController extends Controller
{
    protected $provider;
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function compare(Request $request)
    {
        $view = 'front.pages.compare-list';

        $cookie_compare = $request->cookie('compare');
        $meta_static = '';
        $compare_list = [];

        if (!is_null($cookie_compare)) {
            $compare_id = CompareId::where('id', $cookie_compare)->first();

            if (!is_null($compare_id)) {
                $compare_subjects = Compare::where('compare_id', $compare_id->id)
                    ->select(DB::raw('DISTINCT goods_subject_id'))
                    ->orderBy('created_at', 'desc')
                    ->pluck('goods_subject_id')
                    ->toArray();

                if (!empty($compare_subjects)) {

                    $compare_subject_id = $request->input('subject');

                    if (!$compare_subject_id || !in_array($compare_subject_id, $compare_subjects))
                        $compare_subject_id = $compare_subjects[0];

                    $compare_list = Compare::where('compare_id', $compare_id->id)
                        ->where('goods_subject_id', $compare_subject_id)
                        ->orderBy('position', 'asc')
                        ->get();


                    $parameters = [];
                    $parameters = GoodsParametrId::where('active', 1)
                        ->where('deleted', 0)
                        ->where('goods_subject_id', $compare_subject_id)
                        //->wherein('parametr_type', ['select', 'radio', 'checkbox'])
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc')
                        ->get();
                }
            }
        }

        $meta_static = ShowLabelById(8, $this->lang_id) . ' - ' . env('APP_NAME') ?? env('APP_NAME');


        return view($view, get_defined_vars());
    }
    public function ajaxCompare(Request $request)
    {
        $goods_id = $request->input('goods_id');
        //$goods_subject_id = $request->input('goods_subject_id');
        //$wish_item = $request->input('wish');
        $cookie_compare = $request->cookie('compare');
        /*$user = Session::get('session-front-user');*/

         $id = Session::get('session-front-user');
        $user  = FrontUser::where('id', $id)->first();

        $compare_count = 0;
        $compare_items = [];

        $goods_item_id = GoodsItemId::where('id', $goods_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        if (is_null($goods_item_id))
            return response()->json([
                'status' => false
            ]);

        $maxPosition = GetMaxPosition('compare');
        $compare_id = null;

        if (!is_null($cookie_compare)) {
            $compare_id = Compare::where('goods_item_id', $goods_item_id->id)
                ->where('compare_id', $cookie_compare)
                ->first();
        }

        if (!is_null($compare_id)) {

            Compare::where('goods_item_id', $goods_item_id->id)
                //->where('goods_subject_id', $goods_item_id->goods_subject_id)
                ->where('compare_id', $cookie_compare)
                ->delete();

            $compare_after_delete = Compare::where('compare_id', $cookie_compare)
                ->count();

            if ($compare_after_delete < 1) {
                CompareId::where('id', $cookie_compare)->delete();

                if (!is_null($request->cookie('compare'))) {
                    Cookie::queue(Cookie::forget('compare'));
                }
            }

            //Count for header
            $compare_count = Compare::where('compare_id', $cookie_compare)->count();

            $compare_items_ids = Compare::where('compare_id', $cookie_compare)
                ->orderBy('position', 'asc')
                ->limit(5)
                ->pluck('goods_item_id');

            if(!empty($compare_items_ids)){
                $compare_items = GoodsItemId::where('active', 1)
                    ->where('deleted', 0)
                    ->whereIn('id', $compare_items_ids)
                    ->with('itemByLang')
                    ->has('itemByLang')
                    ->get();
            }

        /*    $view_ajax = 'front.ajax.header-compare-items';
            $header_compare_items_view = view($view_ajax, ['header_compare_items' => $compare_items])->render();*/

            return response()->json([
                'status' => true,
                'compare_count' => $compare_count,
                /*'header_compare_items_view' => $header_compare_items_view,*/
                'compare_item' => 0,
                'message' => ShowLabelById(97, $this->lang_id)
            ]);
        } else {

            $compare_id = CompareId::where('id', $cookie_compare)->first();

            if (!is_null($compare_id)) {
                Compare::create([
                    'compare_id' => $compare_id->id,
                    'goods_item_id' => $goods_item_id->id,
                    'goods_subject_id' => $goods_item_id->goods_subject_id,
                    'position' => $maxPosition + 1
                ]);
            } else {
                $compare_id = CompareId::create([
                    'user_ip' => request()->ip(),
                    'front_user_id' => $user ? $user->id : null
                ]);

                Compare::create([
                    'compare_id' => $compare_id->id,
                    'goods_item_id' => $goods_item_id->id,
                    'goods_subject_id' => $goods_item_id->goods_subject_id,
                    'position' => $maxPosition + 1
                ]);
            }

            //Count for header
            $compare_count = Compare::where('compare_id', $compare_id->id)->count();


            if (!is_null($request->cookie('compare'))) {
                Cookie::queue(Cookie::forget('compare'));
            }

            $compare_items_ids = Compare::where('compare_id', $compare_id->id)
                ->orderBy('position', 'asc')
                ->limit(5)
                ->pluck('goods_item_id');

            if(!empty($compare_items_ids)){
                $compare_items = GoodsItemId::where('active', 1)
                    ->where('deleted', 0)
                    ->whereIn('id', $compare_items_ids)
                    ->with('itemByLang')
                    ->has('itemByLang')
                    ->get();
            }

            /*$view_ajax = 'front.ajax.header-compare-items';
            $header_compare_items_view = view($view_ajax, ['header_compare_items' => $compare_items])->render();*/

            Cookie::queue('compare', $compare_id->id, env('COOKIE_USER_REMEMBER_TIME'));

        }

        return response()->json([
            'status' => true,
            'compare_count' => $compare_count,
            /*'header_compare_items_view' => $header_compare_items_view,*/
            'compare_item' => 1,
            'message' => ShowLabelById(162, $this->lang_id)
        ]);

    }

    public function destroyCompareItem(Request $request)
    {
        $goods_item = $request->input('goods_id');
        $cookie_compare = Cookie::get('compare');

        $compare_items = [];

        $compare_item = Compare::where('goods_item_id', $goods_item)
            ->where('compare_id', $cookie_compare)
            ->first();

        if (is_null($compare_item) || is_null($cookie_compare))
            return response()->json([
                'status' => false
            ]);

        Compare::where('goods_item_id', $goods_item)
            ->where('compare_id', $cookie_compare)
            ->delete();

        $compare_count = Compare::where('compare_id', $cookie_compare)->count('id');

        $compare_item_after_delete = Compare::where('compare_id', $cookie_compare)
            ->count();

        $compare_items_ids = Compare::where('compare_id', $cookie_compare)
            ->orderBy('position', 'asc')
            ->limit(5)
            ->pluck('goods_item_id');

        if(!empty($compare_items_ids)){
            $compare_items = GoodsItemId::where('active', 1)
                ->where('deleted', 0)
                ->whereIn('id', $compare_items_ids)
                ->with('itemByLang')
                ->has('itemByLang')
                ->get();
        }

       /* $view_ajax = 'front.ajax.header-compare-items';
        $header_compare_items_view = view($view_ajax, ['header_compare_items' => $compare_items])->render();*/

        if ($compare_item_after_delete < 1) {
            CompareId::where('id', $cookie_compare)->delete();

            if (!is_null(Cookie::get('compare'))) {
                Cookie::queue(Cookie::forget('compare'));
            }
        }

        return response()->json([
            'status' => true,
            'compare_count' => $compare_count,
            /*'header_compare_items_view' => $header_compare_items_view,*/
            'message' => ShowLabelById(163, $this->lang_id)
        ]);
    }

    public function destroyAllCompareItems()
    {
        $cookie_compare = Cookie::get('compare');

        $compare = Compare::where('compare_id', $cookie_compare)
            ->first();

        if (is_null($compare) || is_null($cookie_compare))
            return response()->json([
                'status' => false
            ]);

        Compare::where('compare_id', $cookie_compare)
            ->delete();

        Cookie::queue(Cookie::forget('compare'));

        $compare_count = 0;

        $view_ajax = 'front.ajax.header-empty-compare';
        $header_empty_compare_view = view($view_ajax)->render();

        return response()->json([
            'status' => true,
            'header_empty_compare_view' => $header_empty_compare_view,
            'compare_count' => $compare_count,
        ]);
    }

}

