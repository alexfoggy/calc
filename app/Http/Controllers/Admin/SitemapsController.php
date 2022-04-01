<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GallerySubjectId;
use App\Models\GalleryItemId;
use App\Models\InfoItemId;
use App\Models\InfoLineId;
use App\Models\MenuId;
use App\Models\ProductionId;
use EllisTheDev\Robots\Robots;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Watson\Sitemap\Facades\Sitemap;

class SitemapsController extends Controller
{

    public function index()
    {
//        $lang = $this->lang()['lang'];
        $lang_list = $this->lang()['lang_list'];

        // You can use the route helpers too.
        $routeCollection = Route::getRoutes();
        $url_list = [];
        foreach ($routeCollection as $value) {
            $prefix = $value->getAction()['prefix'];

            if ($prefix != '{lang?}/back' && !in_array('POST', $value->methods())) {
                if (strpos($value->uri(), '{parent}') === false && strpos($value->uri(), '{children?}') === false && strpos($value->uri(), '{category?}') === false && strpos($value->uri(), 'search') === false) {

                    $getPath = str_replace('{lang?}', '', $value->uri());

                    if (!empty($prefix)) {
                        if (!empty($lang_list)) {
                            foreach ($lang_list as $item) {
                                $url_list[] = url($item->lang . $getPath);
                            }
                        }
                    } else {
                        if (!empty($lang_list)) {
                            foreach ($lang_list as $item) {
                                $url_list[] = url($item->lang . $getPath);
                            }
                        }
                    }

                }
            }
        }


//        Menu pages
        $menu_pages = MenuId::where('active', 1)
            ->where('deleted', 0)
            ->where('level', 1)
            ->get();

        if (!$menu_pages->isEmpty()) {
            foreach ($menu_pages as $menu_page) {
                if (!empty($lang_list) && $menu_page->alias != 'about' && $menu_page->alias != 'goods') {
                    foreach ($lang_list as $item) {
                        $url_list[] = url($item->lang . '/menu/' . $menu_page->alias);
                    }
                }
            }
        }

//        Menu pages

//        About us pages
        $about_us_pages_parent = MenuId::where('active', 1)
            ->where('deleted', 0)
            ->where('alias', 'about')
            ->first();

        if (!is_null($about_us_pages_parent)) {
            $about_us_pages = MenuId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $about_us_pages_parent->id)
                ->get();

            if (!$about_us_pages->isEmpty()) {
                foreach ($about_us_pages as $about_us_page) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            if ($about_us_page->page_type == 'page')
                                $url_list[] = url($item->lang . '/menu/' . $about_us_pages_parent->alias . '/' . $about_us_page->alias);
                        }
                    }
                }
            }
        }

//        About us pages

//        Buyers pages
        $about_us_pages_parent = MenuId::where('active', 0)
            ->where('deleted', 0)
            ->where('alias', 'buyers')
            ->first();

        if (!is_null($about_us_pages_parent)) {
            $about_us_pages = MenuId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $about_us_pages_parent->id)
                ->get();

            if (!$about_us_pages->isEmpty()) {
                foreach ($about_us_pages as $about_us_page) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            if ($about_us_page->page_type == 'page')
                                $url_list[] = url($item->lang . '/menu/' . $about_us_page->alias);
                        }
                    }
                }
            }
        }

//        Buyers pages

//        Company Verix Group pages
        $about_us_pages_parent = MenuId::where('active', 0)
            ->where('deleted', 0)
            ->where('alias', 'company-verix-group')
            ->first();

        if (!is_null($about_us_pages_parent)) {
            $about_us_pages = MenuId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $about_us_pages_parent->id)
                ->get();

            if (!$about_us_pages->isEmpty()) {
                foreach ($about_us_pages as $about_us_page) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            if ($about_us_page->page_type == 'page')
                                $url_list[] = url($item->lang . '/menu/' . $about_us_page->alias);
                        }
                    }
                }
            }
        }

//        Company Verix Group pages

//        Partners pages
        $about_us_pages_parent = MenuId::where('active', 0)
            ->where('deleted', 0)
            ->where('alias', 'for-partners')
            ->first();

        if (!is_null($about_us_pages_parent)) {
            $about_us_pages = MenuId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $about_us_pages_parent->id)
                ->get();

            if (!$about_us_pages->isEmpty()) {
                foreach ($about_us_pages as $about_us_page) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            if ($about_us_page->page_type == 'page')
                                $url_list[] = url($item->lang . '/menu/' . $about_us_page->alias);
                        }
                    }
                }
            }
        }

//        Partners pages

//        Useful links pages
        $about_us_pages_parent = MenuId::where('active', 0)
            ->where('deleted', 0)
            ->where('alias', 'useful-links')
            ->first();

        if (!is_null($about_us_pages_parent)) {
            $about_us_pages = MenuId::where('active', 1)
                ->where('deleted', 0)
                ->where('p_id', $about_us_pages_parent->id)
                ->get();

            if (!$about_us_pages->isEmpty()) {
                foreach ($about_us_pages as $about_us_page) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            if ($about_us_page->page_type == 'page')
                                $url_list[] = url($item->lang . '/menu/' . $about_us_pages_parent->alias . '/' . $about_us_page->alias);
                        }
                    }
                }
            }
        }

//        Useful links pages

//        News
        $info_line_id = InfoLineId::where('active', 1)
            ->where('deleted', 0)
            ->where('alias', 'news')
            ->first();

        if(!is_null($info_line_id)){

            $info_item_id = InfoItemId::where('info_line_id', $info_line_id->id)
                ->where('active', 1)
                ->where('deleted', 0)
                ->where('is_public', 1)
                ->get();

            if(!$info_item_id->isEmpty()){
                foreach ($info_item_id as $info_item) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            $url_list[] = url($item->lang . '/menu/news/' . $info_item->alias);
                        }
                    }
                }
            }

        }
//        News

//        Subjects

        $goods_subject_id = GallerySubjectId::where('active', 1)
            ->where('deleted', 0)
            ->get();

        if(!$goods_subject_id->isEmpty()) {
            foreach($goods_subject_id as $productions_list) {
                $first_parent = getGallerySubjectInfoByTree($item->id, $productions_list->p_id);

                if(!is_null($first_parent) && $first_parent->active == 1 && $first_parent->deleted == 0) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            $url_list[] = url($item->lang . '/' . SelectGallerySubjectsAliasAsc($item->id, $productions_list->id));
                        }
                    }
                }
            }
        }


//        Subjects

//        Items

        $goods_item_id = GalleryItemId::where('active', 1)
            ->where('deleted', 0)
            ->get();

        if(!$goods_item_id->isEmpty()) {
            foreach($goods_item_id as $productions_list) {
                $first_parent = getGallerySubjectInfoByTree($item->id, $productions_list->goods_subject_id);

                if(!is_null($first_parent) && $first_parent->active == 1 && $first_parent->deleted == 0) {
                    if (!empty($lang_list)) {
                        foreach ($lang_list as $item) {
                            $url_list[] = url($item->lang . '/' . SelectGallerySubjectsAliasAsc($item->id, $productions_list->goods_subject_id)) . '/' . $productions_list->alias;
                        }
                    }
                }
            }
        }

//        Items

//        $sitemap_xml = '';
        if (!empty($url_list)) {
            foreach ($url_list as $key => $item) {
                Sitemap::addTag($item, '', 'daily', '0.8');
//                $sitemap_xml .= $key + 1 . " - <a href='{$item}'>$item</a> <br />";
            }
        }

        $sitemap_xml = Sitemap::render();

         if(File::exists('sitemap.xml')){
         	File::delete('sitemap.xml');
             File::put('sitemap.xml', $sitemap_xml->content());
         }
         else {
         	File::put('sitemap.xml', $sitemap_xml->content());
         }

        $robots = new Robots();
        if (app()->environment() == 'production') {
            $robots->addUserAgent('*');
            $robots->addSitemap(url('sitemap.xml'));
            $robots->addDisallow('/admin-assets');
        } else {
            $robots->addUserAgent('*');
            $robots->addDisallow('*');
        }

        if (File::exists('robots.txt')) {
            File::delete('robots.txt');
            File::put('robots.txt', $robots->generate());
        } else {
            File::put('robots.txt', $robots->generate());
        }

//        return $sitemap_xml;
        return response()->json([
            'status' => true,
            'type' => 'info',
            'messages' => [controllerTrans('variables.sitemap_msg', $this->lang()['lang'])]
        ]);
    }


}