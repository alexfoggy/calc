<?php

namespace App\Providers;

use App\Models\AdminUserGroup;
use App\Models\BannerId;
use App\Models\BannerTopId;
use App\Models\CityId;
use App\Models\FeedForm;
use App\Models\GallerySubjectId;
use App\Models\GoodsSubjectId;
use App\Models\InfoLineId;
use App\Models\Labels;
use App\Models\LabelsId;
use App\Models\Lang;
use App\Models\MenuId;
use App\Models\ModulesId;
use App\Models\ModulesSubmenu;
use App\Models\PromotionsId;
use App\Models\SettingsId;
use App\Models\ShopsId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Modules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\AdminUserActionPermision;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class DefineElementsOfLeftMenu extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*Sidebar, footer, breadcrumbs*/
        View::composer(['admin.sidebar','admin.footer', 'admin.breadcrumbs', 'admin.user.create-group'], function($view) {

            $lang = App::getLocale();
            $lang_id = Lang::where('lang', $lang)->first()->id;

            if (Auth::check()){
                if(!is_null(Auth::user()->admin_user_group_id)){
                    $user = User::find(Auth::user()->id);
                    $user_group_id = Auth::user()->admin_user_group_id;


                    $menuIds = array_keys($user->group()->first()
                        ->userPermission()->get(['modules_id'])
                        ->groupBy(['modules_id'])
                        ->toArray()
                    );

                    $menu_modules_id = ModulesId::where('active', 1)
                        ->where('deleted', 0)
                        ->orderBy('position', 'asc')
                        ->findMany($menuIds);

                    $menu = [];
                    if(!$menu_modules_id->isEmpty()) {
                        foreach ($menu_modules_id as $item) {
                            $menu[] = Modules::where('modules_id', $item->id)
                                ->where('lang_id', $lang_id)
                                ->first();
                        }
                    }

                    $modules_name_id = ModulesId::where('alias', request()->segment(3))
                        ->where('active', 1)
                        ->where('deleted', 0)
                        ->first();

                    $modules_name = [];

                    if(!is_null($modules_name_id)) {
                        $modules_name = Modules::where('modules_id', $modules_name_id->id)
                            ->where('lang_id', $lang_id)
                            ->first();
                    }

                    $modules_sumbenu_name_id = ModulesId::where('alias', request()->segment(4))
                        ->where('active', 1)
                        ->where('deleted', 0)
                        ->first();

                    $modules_sumbenu_name = [];

                    if(!is_null($modules_sumbenu_name_id)) {
                        $modules_sumbenu_name = Modules::where('modules_id', $modules_sumbenu_name_id->id)
                            ->where('lang_id', $lang_id)
                            ->first();
                    }


                    // Feedform
                    $new_feedform = FeedForm::where('seen', 0)->count();
                    // Feedform


                    // Back breadcrumbs module
                    $back_breadcrumbs = '';
                    if(request()->segment(2) == 'back' && !is_null(request()->segment(3))) {
                        $element_list_id = $users_list_id = null;
                        $curr_model = null;
                        $curr_row_id = null;
                        $module_has_cart = true;

                        if(request()->segment(3) == 'goods') {
                            $element_list_id = GoodsSubjectId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'GoodsSubject';
                            $curr_row_id = 'goods_subject_id';
                        }
                        elseif(request()->segment(3) == 'menu') {
                            $element_list_id = MenuId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'Menu';
                            $curr_row_id = 'menu_id';
                        }
                        elseif(request()->segment(3) == 'modules-constructor') {
                            $element_list_id = ModulesId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'Modules';
                            $curr_row_id = 'modules_id';
                        }
                        elseif(request()->segment(3) == 'info_line') {
                            $element_list_id = InfoLineId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'InfoLine';
                            $curr_row_id = 'info_line_id';
                        }
                        elseif(request()->segment(3) == 'gallery') {
                            $element_list_id = GallerySubjectId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'GallerySubject';
                            $curr_row_id = 'gallery_subject_id';
                        }
                        elseif(request()->segment(3) == 'promotions') {
                            $element_list_id = PromotionsId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'Promotions';
                            $curr_row_id = 'promotions_id';
                        }
                        elseif(request()->segment(3) == 'banners') {
                            $curr_model = 'Banner';
                            $curr_row_id = 'banner_id';
                        }
                        elseif(request()->segment(3) == 'brand') {
                            $curr_model = 'Brand';
                            $curr_row_id = 'id';
                        }
                        elseif(request()->segment(3) == 'banner_top') {
                            $curr_model = 'BannerTop';
                            $curr_row_id = 'banner_top_id';
                        }
                        elseif(request()->segment(3) == 'shops') {
                            $element_list_id = ShopsId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'Shops';
                            $curr_row_id = 'shops_id';
                            $module_has_cart = false;
                        }
                        elseif(request()->segment(3) == 'city') {
                            $element_list_id = CityId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'City';
                            $curr_row_id = 'city_id';
                            $module_has_cart = false;
                        }
                        elseif(request()->segment(3) == 'orders') {
                            $curr_model = 'Orders';
                            $curr_row_id = 'id';
                        }
                        elseif(request()->segment(3) == 'feedform') {
                            $curr_model = 'Feedform';
                            $curr_row_id = 'id';
                            $module_has_cart = false;
                        }
                        elseif(request()->segment(3) == 'settings') {
                            $element_list_id = SettingsId::where('alias', request()->segment(4))
                                ->first();
                            $curr_model = 'Settings';
                            $curr_row_id = 'settings_id';
                            $module_has_cart = false;
                        }
                        elseif(request()->segment(3) == 'labels') {
                            $element_list_id = LabelsId::where('id', request()->segment(6))
                                ->first();
                            $curr_model = 'Labels';
                            $curr_row_id = 'labels_id';
                            $module_has_cart = false;
                        }

                        if(!is_null($element_list_id))
                            $back_breadcrumbs = universalBreadcrumbsByDbFinal($lang, $lang_id, $element_list_id->id, $modules_name, $modules_sumbenu_name, request()->segment(3), $curr_model, $curr_row_id, $module_has_cart);
                        else
                            $back_breadcrumbs = universalBreadcrumbsByDbFinal($lang, $lang_id, null, $modules_name, $modules_sumbenu_name, request()->segment(3), $curr_model, $curr_row_id, $module_has_cart);

                        if(request()->segment(3) == 'admin_user') {
                            $users_list_id = AdminUserGroup::where('alias', request()->segment(4))
                                ->first();

                            $user_id = User::where('id', request()->segment(6))->first();

                            $back_breadcrumbs = adminUsersBreadcrumbsByDbFinal($lang, $users_list_id, $modules_name, request()->segment(3), $user_id);
                        }
                    }
                    // Back breadcrumbs module


//                SubRelations (new, save, active, del_to_rec, del_from_rec)
                    if(!is_null($modules_name_id)) {
                        $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
                            ->where('modules_id', $modules_name_id->id)
                            ->first();
                    }

                    elseif(!is_null($modules_sumbenu_name_id)){
                        $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
                            ->where('modules_id', $modules_sumbenu_name_id->id)
                            ->first();
                    }
                    else{
                        $groupSubRelations = [];
                    }
                }
                else {
                    $menu = [];
                    $modules_name = [];
                    $modules_sumbenu_name = [];
                    $groupSubRelations = [];
                    $new_feedform = [];
                    $back_breadcrumbs = '';
                }
            }
            else {
                $menu = [];
                $modules_name = [];
                $modules_sumbenu_name = [];
                $groupSubRelations = [];
                $new_feedform = [];
                $back_breadcrumbs = '';
            }

            /*return*/
            $view->menu = $menu;
            $view->groupSubRelations = $groupSubRelations;
            $view->new_feedform = $new_feedform;
            $view->back_breadcrumbs = $back_breadcrumbs;
            $view->modules_name = $modules_name;
            $view->modules_submenu_name = $modules_sumbenu_name;
        });


        /*Global*/
        View::composer('*', function ($view) {
            //get languages
            $lang_list = Lang::where('active', 1)
                ->orderBy('position', 'ASC')
                ->get();

            $default_lang = Lang::where('active', 1)
                ->where('default_lang', 1)
                ->first();

            $arr_lang = [];
            foreach($lang_list as $key => $one_lang){
                $arr_lang[$one_lang->lang] = $one_lang->lang;
            }
//change languages

            $lang = request()->segment(1);
            if (array_key_exists($lang, $arr_lang)) {
                Session::put('applocale', $lang);
            }
            else{
                Session::put('applocale', $default_lang->lang);
            }

            if (Session::has('applocale') && array_key_exists(Session::get('applocale'), $arr_lang)) {
                App::setLocale(Session::get('applocale'));
            }
            else { // This is optional as Laravel will automatically set the fallback language if there is none specified
                App::setLocale($default_lang->lang);
            }

            $lang = App::getLocale();
            $lang_id = Lang::where('lang', $lang)->first()->id;
//change languages


            if(Auth::user())
                $user_group_id = Auth::user()->admin_user_group_id;

            $module = ModulesId::where('alias',request()->segment(3))->first();

            $groupSubRelations = [];

            if($module && $user_group_id)
                $groupSubRelations = AdminUserActionPermision::where('admin_user_group_id', $user_group_id)
                    ->where('modules_id', $module->id)
                    ->first();

            $view->lang = $lang;
            $view->lang_list = $lang_list;
            $view->lang_id = $lang_id;
            $view->lang_to_edit = request()->segment(7);
            $view->groupSubRelations = $groupSubRelations;

        });
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
