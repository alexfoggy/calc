<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\CalcId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class SearchController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index(Request $request)
    {

        $search = $request->input('value');

        $search_list = explode(' ',$search);

        $search_calc = CalcId::where('active', 1)
            ->where('deleted', 0)
            ->with('parent')
            ->when($search, function ($query) use ($search_list) {
                foreach ($search_list as $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('name', 'meta_keywords', '%' . $search . '%');
                }
            })
            ->join('calc', 'calc.calc_id', '=', 'calc_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'calc_id.id as id')
            ->get();

/*$search_archive = GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->with('parent')
            ->when($search, function ($query) use ($search_list) {
                foreach ($search_list as $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_descr', 'like', '%' . $search . '%');
                }
            })
            ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_item_id.id as id')
            ->get();*/

        $ajaxView = 'front.templates.SmartSearch';

        $search_list_text = [];

        foreach ($search_list as $key => $search_item) {
            $search_list_text[$key] = '<span class="searchyy">'.$search_item.'</span>';
        }


        return response()->json([

            'status' => true,

            'view' => view($ajaxView, compact('search_calc', 'search_archive','search_list','search_list_text'))->render(),


        ]);


    }
    public function searchPage(Request $request)
    {

        $search = $request->input('q');

        $search_list = explode(' ',$search);

        $search_calc = CalcId::where('active', 1)
            ->where('deleted', 0)
            ->with('parent')
            ->when($search, function ($query) use ($search_list) {
                foreach ($search_list as $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('name', 'meta_keywords', '%' . $search . '%');
                }
            })
            ->join('calc', 'calc.calc_id', '=', 'calc_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'calc_id.id as id')
            ->get();

/*$search_archive = GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->with('parent')
            ->when($search, function ($query) use ($search_list) {
                foreach ($search_list as $search) {
                    $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_descr', 'like', '%' . $search . '%');
                }
            })
            ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
            ->where('lang_id', $this->lang_id)
            ->select('*', 'goods_item_id.id as id')
            ->get();*/

        $view = 'front.pages.search-list';

        return view($view, get_defined_vars());


    }

}

