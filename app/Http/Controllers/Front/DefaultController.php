<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CalcId;
use App\Models\CalcInputId;
use App\Models\CalcSubjectId;
use App\Models\FormulaId;
use App\Models\GoodsSubjectId;
use App\Models\Menu;
use App\Models\Wish;
use App\Models\WishId;
use App\Models\MenuId;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DefaultController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index()
    {
        $view = 'front.index';
        $lang_id = $this->lang_id;

        $calc_categorys = CalcSubjectId::where('active', 1)->where('deleted', 0)->with('itemByLang')
            ->with(['children' => function ($q) {
                $q->where('active', 1)
                    ->where('deleted', 0)
                    ->has('itemByLang')
                    ->with('itemByLang')
                    ->orderBy('created_at', 'asc');
            }])->get();

        return view($view, get_defined_vars());
    }

    public function apiMain(){

        $calc_categorys = CalcSubjectId::where('active', 1)->where('deleted', 0)->with('itemByLang')
            ->with(['children' => function ($q) {
                $q->where('active', 1)
                    ->where('deleted', 0)
                    ->has('itemByLang')
                    ->with('itemByLang')
                    ->orderBy('created_at', 'asc');
            }])->get();

        return response()->json($calc_categorys);
    }


    public function menuElements($lang, $parent, $children = null)
    {
        $lang = $this->lang;
        $lang_id = $this->lang_id;

        $parent_menu = MenuId::where('alias', $parent)
            ->where('active', 1)
            ->where('deleted', 0)
            ->with('itemByLang')
            ->with('oImage')
            ->first();

        if (!is_null($children)) {

            switch ($parent) {
                default:
                    return abort(404, 'Unauthorized action.');
                //return redirect($lang);
            }
        } else {

            if (is_null($parent_menu) || is_null($parent))
                return abort(404, 'Unauthorized action.');

            switch ($parent) {
//                case 'about':
//                    return $this->AboutPage($parent_menu, $lang_id);
                case 'sitemap':
                    return $this->SitemapPage($parent_menu, $lang_id);
/*                case 'new':
                    return $this->NewItemsPage($parent_menu, $lang_id);
                case 'sale':
                    return $this->SaleItemsPage($parent_menu, $lang_id);*/
                default:
                    return $this->textPage($parent_menu, $children, $lang_id);
            }
        }
    }

    /*public function AboutPage($parent_menu, $lang_id)
    {
        $view = 'front.pages.about-page';

        $partners = Brand::where('deleted', 0)
            ->where('active', 1)
            ->where('is_partners', 1)
            ->orderBy('position', 'asc')
            ->limit(12)
            ->get();

        $meta_tag = $parent_menu;

        return view($view, get_defined_vars());
    }*/

    public function calcWork($lang_id, $parent = null, $children = null)
    {


        if ($children != null) {
            $view = 'front.pages.CalcPage';

            $calc_id = CalcId::where('alias', $children)->where('active', 1)->where('deleted', 0)->with('itemByLang')->with('parent')->first();

            if(!$calc_id){
                return abort(404, 'Unauthorized action.');
            }

            $rows = CalcInputId::where('calc_id', $calc_id->id)->with('itemByLang')->get();

            if ($calc_id->type_calc == 'select') {
                $checkbox = FormulaId::where('calc_id', $calc_id->id)->where('p_id',0)->with('itemByLang')->get();
                $checkbox_spec = false;
                if (count($checkbox) < 2) {
                    $checkbox_spec = true;
                }
            }

            $recomended = CalcId::where('calc_subject_id', $calc_id->parent->id)->where('id', '!=', $calc_id->id)
                ->where('active', 1)->where('deleted', 0)->with('itemByLang')->get();

            $meta_tag = $calc_id;

        } elseif ($parent != null) {
            $view = 'front.pages.CalcSubject';

            $subject = CalcSubjectId::where('active', 1)->where('alias', $parent)->where('deleted', 0)->with('itemByLang')
                ->with(['children' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('created_at', 'asc');
                }])->first();
        } else {
            $view = 'front.pages.CalcParent';

            $page = MenuId::where('alias', request()->segment(2))->with('itemByLang')->first();

            $calc_categorys = CalcSubjectId::where('active', 1)->where('deleted', 0)->with('itemByLang')
                ->with(['children' => function ($q) {
                    $q->where('active', 1)
                        ->where('deleted', 0)
                        ->has('itemByLang')
                        ->with('itemByLang')
                        ->orderBy('created_at', 'asc');
                }])->get();

        }

        return view($view, get_defined_vars());
    }

    public function SitemapPage($parent_menu, $lang_id)
    {
        $view = 'front.pages.siteMap';

        $menu = MenuId::where('active',1)->with('itemByLang')->get();
        $calcs = CalcId::where('active',1)->with('itemByLang')->get();
        $calc_subjects = CalcSubjectId::where('active',1)->with('itemByLang')->get();

        $meta_tag = $parent_menu;

        return view($view, get_defined_vars());
    }

    public function SaleItemsPage($parent_menu, $lang_id)
    {
        $view = 'front.pages.items_list_speacial';

        $goods_special = GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->where('price_old', '>', '0')
            ->orderBy('position', 'asc')
            ->with('itemByLang')
            ->with('oImage')
            ->get();


        $meta_tag = $parent_menu;

        return view($view, get_defined_vars());
    }

    public function ContactsPage($parent_menu, $lang_id)
    {
        $view = 'front.pages.contacts-page';

        $shops = ShopsId::where('active', 1)
            ->with('itemByLang')
            ->get();

        $meta_tag = $parent_menu;

        return view($view, get_defined_vars());
    }

    public function textPage($parent_menu, $children, $lang_id)
    {
        $view = 'front.pages.text_page';

        if (is_null($parent_menu))
            return abort(404, 'Unauthorized action.');

        //For meta tags
        $meta_tag = $parent_menu;

        if (!is_null($children))
            return abort(404, 'Unauthorized action.');

        return view($view, get_defined_vars());
    }

    public function calcit(Request $request)
    {

       /* $item = Validator::make($request->all(), [
            '*' => 'required',
        ]);
        if ($item->fails())
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);*/
        $calc_id = CalcId::where('alias', $request->segment(3))->first();

        if ($request->input('formula_type')) {

            $formula_id = $request->input('formula_type');

            $formulas = FormulaId::where('id', $formula_id)->orWhere('p_id',$formula_id)->pluck('formula');
            $formulas_langs = FormulaId::where('id', $formula_id)->orWhere('p_id',$formula_id)->with('itemByLang')->get();

            $list_got = $request->input();

            $result = '';
            foreach ($formulas as $formula) {
                foreach ($list_got as $key => $value) {
                  if($value != '') {
                    $value = str_replace(',', '.', $value);
                    $formula = str_replace($key, preg_replace('#[^0-9\.]#', '', $value), $formula);
                   }
                }
                $result .= $formula . ' || ';
            }
            $result = explode('||', $result);

            $final_result = '';
            foreach ($result as $one_result) {
                if ($one_result != '') {
                    $final_result .= eval('return ' . $one_result . ';') . '||';
                }
            }

            $final_result = explode('||', $final_result);


            //$result = number_format((float)$result, 2, '.', '');


            $final = '';

            foreach ($final_result as $key => $one_result) {
                if ($one_result != '') {
                    $final .= '<div class="result-row"><span class="prev-text"><b>'
                        . $formulas_langs[$key]->itemByLang->dime . ': </b></span><span class="final-result"><span class="bold">' .
                        round($one_result, 4) . '</span> ' . $formulas_langs[$key]->itemByLang->dime_text . '</span></div>';
                }
            }

        } else {


            $formulas = FormulaId::where('calc_id', $calc_id->id)->orderBy('created_at', 'asc')->pluck('formula');
            $formulas_langs = FormulaId::where('calc_id', $calc_id->id)->with('itemByLang')->orderBy('created_at', 'asc')
                ->get();

//            $calc_id = FormulaId::where('id', $request->input('formula_type'))->pluck('formula')->first();

            $list_got = $request->input();

            $result = '';
            foreach ($formulas as $formula) {
                foreach ($list_got as $key => $value) {
                    $value = str_replace(',','.',$value);
                    $formula = str_replace($key, preg_replace('#[^0-9\.]#', '', $value), $formula);
                }
                $result .= $formula . ' || ';
            }
            $result = explode('||', $result);

            $final_result = '';
            foreach ($result as $one_result) {
                if ($one_result != ' ') {
                    $final_result .= eval('return ' . $one_result . ';') . '||';
                }
            }

            $final_result = explode('||', $final_result);


            //$result = number_format((float)$result, 2, '.', '');


            $final = '';

            foreach ($final_result as $key => $one_result) {
                if ($one_result != '') {
                    $final .= '<div class="result-row"><span class="prev-text"><b>'
                        . $formulas_langs[$key]->itemByLang->dime . ': </b></span><span class="final-result"><span class="bold">' .
                        round($one_result, 4) . '</span> ' . $formulas_langs[$key]->itemByLang->dime_text . '</span></div>';
                }
            }


        }

        return response()->json([
            'status' => true,
            'result' => $final,
        ]);

    }

    public function simpleFeedbackAjax(Request $request)
    {


        $item = Validator::make($request->all(), [
            'message' => 'required',
        ]);


        if ($item->fails())
            return response()->json([
                'status' => false,
                'messages' => $item->messages(),
            ]);
        if (reCaptchaVersionThree($request->input('g-recaptcha-response')) == false)
            return response()->json([
                'status' => false,
                'messages' => ['Spam'],
            ]);

        $new_message = new FeedForm();
        $new_message->name = $request->input('name');
        $new_message->email = $request->input('email');
        $new_message->phone = $request->input('phone');
        $new_message->subject = $request->input('theme');
        $new_message->comment = $request->input('comment');
        $new_message->ip = $request->ip();
        $new_message->active = 0;
        $new_message->seen = 0;
        $new_message->save();

        $my_email = showSettingBodyByAlias('email-phone', $this->lang_id);
        $subject = '';
        $subject = ShowLabelById(192, $this->lang_id);

        if (filter_var($my_email, FILTER_VALIDATE_EMAIL)) {
            Mail::send('front.email.emailFeedback', ['data' => $new_message], function ($message) use ($my_email, $subject, $request) {
                $message->from(showSettingBodyByAlias('send-email-from', $this->lang_id), ShowLabelById(192, $this->lang_id));
                $message->to($my_email);
                $message->subject($subject);
            });
        }

        return response()->json([
            'status' => true,
            'message' => ShowLabelById(161, $this->lang_id),
        ]);
    }


    public function ajaxFav(Request $request)
    {
        $id = $request->input('id');
        //$wish_item = $request->input('wish');
        $cookie_fav = $request->cookie('wish');

        $calc_id = CalcId::where('id', $id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        if (is_null($calc_id))
            return response()->json([
                'status' => false
            ]);

        $maxPosition = GetMaxPosition('wish');
        $wish = null;

        if (!is_null($cookie_fav)) {
            $wish = Wish::where('goods_item_id', $calc_id->id)
                ->where('wish_id', $cookie_fav)
                ->first();
        }

        if (!is_null($wish)) {

            Wish::where('goods_item_id', $calc_id->id)
                ->where('wish_id', $cookie_fav)
                ->delete();


            $wish_after_delete = Wish::where('wish_id', $cookie_fav)
                ->count();

            if ($wish_after_delete < 1) {
                WishId::where('id', $cookie_fav)->delete();

                if (!is_null($request->input('fav'))) {
                    Cookie::queue(Cookie::forget('fav'));
                }
            }

            //Count for header
            $get_cookie_wish = $request->cookie('fav');
            $get_wish_count = Wish::where('wish_id', $get_cookie_wish)->count();

            return response()->json([
                'status' => true,
                'wish_item' => $get_wish_count
                //'wish_item' => 0
            ]);
        } else {

            $wish_id = WishId::where('id', $cookie_fav)->first();

            if (!is_null($wish_id)) {
                Wish::create([
                    'wish_id' => $wish_id->id,
                    'goods_item_id' => $calc_id->id,
                    'position' => $maxPosition + 1
                ]);

                //Count for header
                $get_cookie_wish = $request->cookie('wish');
                $get_wish_count = Wish::where('wish_id', $get_cookie_wish)->count();
            } else {
                $wish_id = WishId::create(['user_ip' => request()->ip()]);

                Wish::create([
                    'wish_id' => $wish_id->id,
                    'goods_item_id' => $calc_id->id,
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


}
