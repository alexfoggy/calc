<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\FrontUser;
use App\Models\GoodsItem;
use App\Models\GoodsItemId;
use App\Models\GoodsParametrId;
use App\Models\GoodsParametrItemId;
use App\Models\GoodsParametrValueId;
use App\Models\GoodsPhoto;
use App\Models\GoodsSubject;
use App\Models\GoodsSubjectId;
use App\Models\MenuId;
use App\Models\ReviewsGoods;
use App\Models\Tech;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ProjectController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index(Request $request, $lang, $project = null)
    {
        $goods_subject = null;
        $goods_subject_id = null;

        if($project) {
            $curient_project = GoodsItemId::where('alias',$project)->with('allImages')->with('itemByLang')->first();
            $view = 'front.pages.products-page';
            if(empty($curient_project)){
                return abort(404, 'Unauthorized action.');
            }
            $meta_tag = $curient_project;
        }
        else {
            $projects_list = GoodsSubjectId::where('alias','projects')
                ->with(['goodsItemId' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc');
                }])
                ->first();

            $meta_tag = $projects_list;

            $view = 'front.pages.products-list';
        }
        return view($view, get_defined_vars());
    }

    public function filterResults(Request $request, $lang)
    {
        $view_ajax = 'front.templates.ajax-products-list';
        $filters_elements = $request->except(['_token', 'data-parent']);
        $filters_elements = array_filter($filters_elements, 'arrayMergeFilter');

        $sorting = Cookie::get('sorting');
        $count_per_page = env('PRODUCTS_PER_PAGE');

        $new_url = '';

        if (!empty($filters_elements)) {
            foreach ($filters_elements as $key => $one_filter_el) {
                if (is_array($one_filter_el)) {
                    $new_url_arr = '';
                    foreach ($one_filter_el as $k => $filter_el) {
                        $new_url_arr .= $filter_el . ',';
                    }

                    $new_url .= $key . '=[' . substr($new_url_arr, 0, -1) . ']&';
                } else {
                    if ($key != 'order')
                        $new_url .= $key . '=' . $one_filter_el . '&';
                }
            }
            $new_url = '?' . substr($new_url, 0, -1);
        }

        $goods_subject_id_parent = null;

        $parent_subject = $request->input('data-parent');

        if (!is_null($parent_subject)) {
            $goods_subject_id_parent = GoodsSubjectId::where('alias', $parent_subject)
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            $goods_subject_id_parent = $goods_subject_id_parent->id;
        } else
            $goods_subject_id_parent = null;

        if (!is_null($goods_subject_id_parent))
            $goods_items_list = GetItemsPodborList($this->lang_id, $goods_subject_id_parent, $filters_elements, $sorting, $count_per_page);
        else
            $goods_items_list = GetItemsPodborList($this->lang_id, $goods_subject_id_parent, $filters_elements, $sorting,$count_per_page);



        if (!empty($goods_items_list))
            return response()->json([
                'status' => true,
                'messages' => $new_url,
                'view' => view($view_ajax, compact('goods_items_list', 'new_url'))->render(),
            ]);

        return response()->json([
            'status' => false
        ]);
    }

    public function ajaxSortPage(Request $request)
    {
        if (!is_null($request->input('sorting'))) {
            if ($request->input('sorting') != 'null') {
                if (!is_null(Cookie::get('sorting'))) {
                    Cookie::queue(Cookie::forget('sorting'));
                }

                Cookie::queue('sorting', $request->input('sorting'), 45000);
            } else {
                if (!is_null(Cookie::get('sorting'))) {
                    Cookie::queue(Cookie::forget('sorting'));
                }
            }
        }
        return response()->json([
            'status' => true
        ]);
    }
    public function goodsSearch(Request $request)
    {
        $view = 'front.pages.search-list';
        $lang_id = $this->lang_id;

        if ($request->input('s'))

            $search = '';
        $search = $request->input('s');

        $filters_elements = $request->except(['_token', 'page']);
        $filters_elements = array_filter($filters_elements);

        $new_url = '';

        if (!empty($filters_elements)) {
            foreach ($filters_elements as $key => $one_filter_el) {

                if (is_array($one_filter_el)) {
                    $new_url_arr = '';
                    foreach ($one_filter_el as $k => $filter_el) {
                        $new_url_arr .= $filter_el . ',';
                    }
                    $new_url .= $key . '=[' . substr($new_url_arr, 0, -1) . ']&';
                } else {
                    $new_url .= $key . '=' . $one_filter_el . '&';
                }
            }

            $new_url = '?' . substr($new_url, 0, -1);
        }
        /*if(is_null($search))
            return redirect($this->lang);*/

        $goods_items_list = GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_descr', 'like', '%' . $search . '%')
                    ->orWhere('body', 'like', '%' . $search . '%');
            })
            ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
            ->where('lang_id', $lang_id)
            ->select('*', 'goods_item_id.id as id')
            ->orderBy('position', 'asc')
            ->paginate(env('PRODUCTS_PER_PAGE'));

        $goods_count = count($goods_items_list);

        return view($view, get_defined_vars());
    }

}

