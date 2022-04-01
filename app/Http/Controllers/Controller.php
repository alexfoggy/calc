<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\ModulesId;
use App\Models\Lang;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\Modules;
use App\Models\User;
use App\Models\AdminUserActionPermision;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function menu()
	{
		if (Auth::check()){
			if(!is_null(Auth::user()->admin_user_group_id)){
				$user = User::find(Auth::user()->id);
				$user_group_id = Auth::user()->admin_user_group_id;

				$menuIds = array_keys($user->group()->first()
				                           ->userPermission()->get(['modules_id'])
				                           ->groupBy(['modules_id'])
				                           ->toArray()
				);

				$menu_modules_id = ModulesId::where('level', 1)
				                            ->where('active', 1)
				                            ->where('deleted', 0)
				                            ->orderBy('position', 'asc')
				                            ->findMany($menuIds);

				$menu = [];
				if(!$menu_modules_id->isEmpty()) {
					foreach ($menu_modules_id as $item) {
						$menu[] = Modules::where('modules_id', $item->id)
						                 ->where('lang_id', $this->lang()['lang_id'])
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
					                       ->where('lang_id', $this->lang()['lang_id'])
					                       ->first();
				}

				$modules_sumbenu_name_id = ModulesId::where('alias', request()->segment(4))
				                                    ->where('active', 1)
				                                    ->where('deleted', 0)
				                                    ->first();

				$modules_sumbenu_name = [];

				if(!is_null($modules_sumbenu_name_id)) {
					$modules_sumbenu_name = Modules::where('modules_id', $modules_sumbenu_name_id->id)
					                               ->where('lang_id', $this->lang()['lang_id'])
					                               ->first();
				}


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
			}
		}

		return get_defined_vars();
	}

	public function lang()
	{
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

		$lang = Request::segment(1);
		if (array_key_exists($lang, $arr_lang)) {
			Session::put('applocale', $lang);
		}
		else{
			Session::put('applocale', $default_lang->lang);
		}

		if (Session::has('applocale') && array_key_exists(Session::get('applocale'), $arr_lang)) {
			App::setLocale(Session::get('applocale'));
		}
		else {
			App::setLocale($default_lang->lang);
		}

		$lang = App::getLocale();
		$lang_id = Lang::where('lang', $lang)->first()->id;
		$default_lang_id = Lang::where('lang', $default_lang->lang)->first()->id;

		return get_defined_vars();
	}

}
