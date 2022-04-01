<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\RoleManager;
use App\Models\FeedForm;
use App\Models\FrontTheme;
use App\Models\GalleryItem;
use App\Models\GalleryItemId;
use App\Models\GallerySubject;
use App\Models\GallerySubjectId;
use App\Models\GoodsItem;
use App\Models\GoodsItemComments;
use App\Models\GoodsItemId;
use App\Models\InfoItemId;
use App\Models\Labels;
use App\Models\MenuId;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class DefaultController extends RoleManager
{
    private $lang;
    private $lang_id;

    public function __construct()
    {
        $this->middleware('custom-auth', ['except' => '/']);
        $this->lang = $this->lang()['lang'];
        $this->lang_id = $this->lang()['lang_id'];
    }

    public function index(){
        $view = 'admin.index';

        $gallery_subject_id = GallerySubjectId::where('active', 1)
            ->where('deleted', 0)
            ->orderBy('created_at', 'desc')
            ->first();

        $gallery_subject = null;
        $gallery_item = [];
        $count_gallery_items = 0;
        if(!is_null($gallery_subject_id)) {
            $gallery_subject = GallerySubject::where('gallery_subject_id', $gallery_subject_id->id)
                ->where('lang_id', $this->lang_id)
                ->first();

            $gallery_item_id = GalleryItemId::where('gallery_subject_id', $gallery_subject_id->id)
                ->where('active', 1)
                ->where('deleted', 0)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            $count_gallery_items = GalleryItemId::where('gallery_subject_id', $gallery_subject_id->id)
                ->where('deleted', 0)
                ->count();

            if(!$gallery_item_id->isEmpty()) {
                foreach ($gallery_item_id as $item) {
                    $gallery_item[] = GalleryItem::where('gallery_item_id', $item->id)
                    ->where('lang_id', $this->lang_id)
                    ->first();
                }

                $gallery_item = array_filter($gallery_item);
            }
        }

        $feedback = FeedForm::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $count_pages = MenuId::where('deleted', 0)
            ->count();

        $count_info_items = InfoItemId::where('deleted', 0)
            ->count();

        $count_feedback = FeedForm::count();

        $count_labels = Labels::count();

        return view($view, get_defined_vars());
    }

    public function ajaxCountFeedback()
    {

        $new_feedform = FeedForm::where('seen', 0)->count();
//        $new_comment = GoodsItemComments::where('seen', 0)->count();

        $count_feedform = 0;
        $count_comment = 0;
        $alias_feedform = '';
        $alias_comment = '';

        if($new_feedform > 0) {
            $count_feedform = $new_feedform;
            $alias_feedform = 'feedform';
        }
//
//        if($new_comment > 0) {
//            $count_comment = $new_comment;
//            $alias_comment = 'comments';
//        }

            return response()->json([
                'status' => true,
                'feedform' => [
                    'count' => $count_feedform,
                    'alias' => $alias_feedform,
                    'messages' => controllerTrans('variables.feedform_count', $this->lang, ['digits' => '<span>' . $count_feedform . '</span>'])
                ],
//                'comments' => [
//                    'count' => $count_comment,
//                    'alias' => $alias_comment,
//                    'messages' => controllerTrans('variables.comments_count', $this->lang, ['digits' => '<span>' . $count_comment . '</span>'])
//                ],
            ]);
    }

    public function hideMenuAjax()
    {
        if(!is_null(Input::get('is_visible'))) {
            if (!is_null(Cookie::get('sidebar'))) {
                Cookie::queue(Cookie::forget('sidebar'));
            }

            Cookie::queue('sidebar', Input::get('is_visible'), 45000);

            return response()->json([
               'status' => true
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

}
