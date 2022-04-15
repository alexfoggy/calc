<?php

namespace App\Providers;
use App\Models\BannerTopId;
use App\Models\Basket;
use App\Models\Brand;
use App\Models\CalcSubjectId;
use App\Models\FrontUser;
use App\Models\GoodsSubjectId;
use App\Models\Lang;
use App\Models\CalcId;
use App\Models\MenuId;
use App\Models\Wish;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

class DefineElementsForFrontSite extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    private $lang_id;
    private $lang;

    public function boot()
    {
        $controller = new Controller();
        $this->lang_id = $controller->lang()['lang_id'];
        $this->lang = $controller->lang()['lang'];
        /*Header*/
        View::composer(['front.header'], function($view) {

            $lang_list = Lang::where('active', 1)->get();

            $header_menu = MenuId::where('active', 1)
                ->where('top_menu', 1)
                ->where('deleted', 0)
                ->where('p_id', 0)
                ->with('itemByLang')
                ->orderBy('position', 'asc')
                ->get();

            $calc_list = CalcSubjectId::where('active', 1)
                ->where('deleted', 0)
                ->orderBy('created_at', 'asc')
                ->with('itemByLang')
                ->get();

            //Save new session for checkbox remember
//            if (!Session::get('session-front-user')) {
//                if (Cookie::get('front-user-remember')) {
//                    $user = FrontUser::where('id', Cookie::get('front-user-remember'))->first();
//                    if($user)
//                        Session::put('session-front-user', $user->id);
//                }
//            }

            /*Show*/
            $view->lang_list = $lang_list;
            $view->calc_list = $calc_list;
            $view->header_menu = $header_menu;
        });

        /*Footer*/
        View::composer(['front.footer'], function($view) {

            $footer_menu = MenuId::where('active', 1)
                ->where('footer_menu', 1)
                ->where('deleted', 0)
                ->where('p_id', 0)
                ->orderBy('position', 'asc')
                ->with('itemByLang')
                ->get();

            $new_calc_list = CalcId::where('active', 1)
                ->where('deleted', 0)
                ->orderBy('created_at', 'desc')
                ->with('itemByLang')
                ->with('parent')
                ->limit(5)
                ->get();

            $view->footer_menu = $footer_menu;
            $view->new_calc_list = $new_calc_list;
        });

        /*All*/
        View::composer('*', function ($view) {

            $user_info = [];

            if (Cookie::get('user_remember') && !Session::get('session-front-user')) {
                $user = FrontUser::where('id', Cookie::get('user_remember'))->first();
                if ($user)
                    Session::put('session-front-user', $user->id);

            }
            if(Session::get('session-front-user') != null) {
                $id = Session::get('session-front-user');
                $user_info = FrontUser::where('id', $id)->first();
            }




            $meta_default = env('APP_NAME');
            $meta_page_img = asset('front-assets/img/share-logo.png');

            $meta_main_page = MenuId::where('alias','main')->with('itemByLang')->first();

            $view->meta_default = $meta_default;
            $view->meta_page_img = $meta_page_img;
            $view->meta_main_page = $meta_main_page;

            $view->user_info = $user_info;
        });

        if(!defined('LANG_ID'))
            define('LANG_ID', $this->lang_id);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
