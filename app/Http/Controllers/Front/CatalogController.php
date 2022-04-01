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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CatalogController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index(Request $request, $lang, $category = null, $item = null)

    {
        $goods_subject = null;
        $goods_subject_id = null;

        if (!is_null($category)) {

            $goods_subject = GoodsSubjectId::where('alias', $category)
                ->where('active', 1)
                ->where('deleted', 0)
                ->with('itemByLang')
                ->with(['children' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc');
                        }])
                ->first();



            if (!$goods_subject)
                return abort(404, 'Unauthorized action.');
            //return redirect($lang . '/catalog');

            $goods_subject_id = $goods_subject->id;
        }

        if (!$category && !$item) {

            $goods_subject_l1 = GoodsSubjectId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', 0)
                ->orderBy('position', 'asc')
                ->with('itemByLang')
                ->with(['children' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc')
                        ->with(['children' => function ($q) {
                            $q->where('active', 1)
                                ->where('deleted', 0)
                                ->has('itemByLang')
                                ->with('itemByLang')
                                ->orderBy('position', 'asc');

                        }]);

                }])
                ->get();


            $view = 'front.pages.catalog-list';

        } elseif (!is_null($item)) {

            $goods_item = GoodsItemId::where('alias', $item)
                ->where('active', 1)
                ->where('deleted', 0)
                ->with('itemByLang')
                ->first();

            if (!$goods_item)
                return abort(404, 'Unauthorized action.');
            //return redirect($lang . '/catalog/' . $goods_subject->alias);

         /*   $goods_images = GoodsPhoto::where('active', 1)
                ->where('goods_item_id', $goods_item->id)
                ->orderBy('position', 'asc')
                ->get();*/

          /*  $reviews_item = ReviewsGoods::Where('active',1)
                            ->where('goods_id',$goods_item->id)
                            ->orderBy('created_at','asc')
                            ->with('userInfo')
                            ->get();
            if($reviews_item->count() > 0) {
                $review_rating = 0;

                foreach ($reviews_item as $one_review) {
                    $review_rating = $review_rating + $one_review->rating;
                }


                $review_rating = $review_rating / $reviews_item->count();
            }
            $params = ParametrDisplay($goods_item->goods_subject_id,$goods_item,LANG_ID);
*/


            //For meta tags
            $meta_tag = $goods_item;
        /*    if ($meta_tag && $meta_tag->oImage && $meta_tag->oImage->img)
                $current_meta_img = asset('upfiles/gallery/' . $meta_tag->oImage->img);*/

            //For meta tags


            $view = 'front.pages.ArchivePage';

        }elseif($goods_subject->children->isNotEmpty()) {
            $subjects = GoodsSubjectId::where('p_id', $goods_subject->id)
                ->where('active', 1)
                ->where('deleted', 0)
                ->with('itemByLang')
                ->orderBy('position', 'asc')
                ->get();

            $items = [];
            if($subjects->isNotEmpty()){
                foreach ($subjects as $one_subject){
                    $last = [];
                    GetEndSubjectsListLar('GoodsSubjectId', 'goods_subject_id', $one_subject->id, $one_subject,1,0,$last);
                    if($last) {
                        $subjects_in = array_keys($last);
                        if(!empty($subjects_in)){
                            $items[$one_subject->id] = GoodsItemId::where('active', 1)
                                ->where('deleted', 0)
                                ->whereIn('goods_subject_id', $subjects_in)
                                ->with('itemByLang')
                                ->with('parent')
                                ->get();
                        }
                    }
                }
            }

            $meta_tag = $goods_subject;

            $view = 'front.pages.ArchiveCategorys';

        }


        else {

            $goods_subject = GoodsSubjectId::where('alias',$category)
                ->where('active',1)
                ->where('deleted',0)
                ->with('itemByLang')
                ->with(['goodsItemId' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc');
                }])->first();


          /*  $parameters = [];
            $parameters = GoodsParametrId::where('active', 1)
                ->where('deleted', 0)
                ->where('goods_subject_id', $goods_subject->id)
                ->wherein('parametr_type', ['select', 'radio', 'checkbox'])
                ->has('itemByLang')
                ->with('itemByLang')
                ->orderBy('position', 'asc')
                ->get();*/

          /*  //Get parameter values
            $parameters_value = [];
            if ($parameters) {
                foreach ($parameters as $one_parameter) {
                    $parameters_value[$one_parameter->id] = GoodsParametrValueId::where('active', 1)
                        ->where('goods_parametr_id', $one_parameter->id)
                        ->whereRaw('goods_parametr_value_id.id IN(SELECT goods_parametr_value_id FROM goods_parametr_item_rsc LEFT JOIN goods_parametr_item_id ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) LEFT JOIN goods_item_id ON(goods_item_id.id=goods_parametr_item_id.goods_item_id) WHERE goods_subject_id=' . $goods_subject->id . ')')
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc')
                        ->get();
                }
            }*/




            //For meta tags
            $meta_tag = $goods_subject;

            //$if_has_child = IfHasChildMy($goods_subject->id, 'goods_subject_id', 1, 0);

            /*  $filters_elements = $request->except(['_token', 'page']);
              $filters_elements = array_filter($filters_elements);

              $sorting = Cookie::get('sorting');
              $count_per_page = env('PRODUCTS_PER_PAGE');

              if (!empty($filters_elements) && count($filters_elements) > 0) {

                  $new_filters_elem = [];

                  foreach ($filters_elements as $key => $one_filter_elem) {
                      $new_filters_elem[$key] = $one_filter_elem;
                      if (strpos($one_filter_elem, '[') !== false || strpos($one_filter_elem, ']' )!== false) {
                          $new_filters_elem[$key] = explode(',', substr($filters_elements[$key], 1, -1));
                      }
                  }

                  $filters_elements = $new_filters_elem;

              }

              $search = @$filters_elements['s'];


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

              $subjects_array = [];
              $my_search_query = null;
              if (!is_null($goods_subject)) {

                  GetEndSubjectsList('goods_subject_id', $goods_subject->goods_subject_id, $this->lang_id, $active = 1, $deleted = 0, $all_goods_subjects);

                  if (!empty($all_goods_subjects) && count($all_goods_subjects)) {
                      foreach ($all_goods_subjects as $one_subject) {
                          array_push($subjects_array, $one_subject->id);
                      }
                  }
              }*/
           /* if (!is_null($goods_subject))
                $goods_items_list = GetItemsPodborList($this->lang_id, $goods_subject->id, $filters_elements, $sorting,
                    $count_per_page);
            else
                $goods_items_list = GetItemsPodborList($this->lang_id, null, $filters_elements, $sorting,
                    $count_per_page);

            
            $goods_items_brands = GoodsItemId::where('active', 1)
                ->where('deleted', 0)
                ->where('goods_subject_id', $goods_subject->id)
                ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
                ->where('lang_id', $this->lang_id)
                ->select('brand_id')
                ->groupBy('brand_id')
                ->get();*/

            //dd($goods_items_brands);

        /*    $brands_list = [];
            if (!empty($goods_items_brands)) {
                foreach ($goods_items_brands as $one_goods_brand) {
                    if (!empty($one_goods_brand->brand_id)) {
                        array_push($brands_list, $one_goods_brand->brand_id);
                    }
                }
            }

            if ($brands_list)
                $brands = Brand::where('active', 1)
                    ->where('deleted', 0)
                    ->whereIn('id', $brands_list)
                    ->orderBy('name_ro', 'asc')
                    ->get();*/


            $view = 'front.pages.ArchiveItems';
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

