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

class ServicesController extends Controller
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
            $curient_project = MenuId::where('alias',$project)->with('oImage')->with('itemByLang')
                ->with(['children' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc');
                }])
                ->first();
            $works_list = explode(',',$curient_project->related_works);
            $works = GoodsItemId::whereIn('id',$works_list)->with('oImage')->get();
            $view = 'front.pages.services-page';

            $meta_tag = $curient_project;

        }
        else {
            $projects_list = GoodsSubjectId::where('alias','projects')->with('itemByLang')
                ->with(['goodsItemId' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('position', 'asc');
                }])
                ->first();

            $view = 'front.pages.products-list';

            $meta_tag = $projects_list;
        }
        //For meta tags

        return view($view, get_defined_vars());
    }



}

