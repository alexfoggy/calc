<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\FrontUser;
use App\Models\TableId;
use App\Models\TableMain;
use App\Models\Shorts;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class TableController extends Controller
{
    private $lang_id;
    private $lang;

    public function __construct()
    {
        $this->lang_id = $this->lang()['lang_id'];
        $this->lang = $this->lang()['lang'];
    }

    public function index(Request $request)
    {
        $view = 'front.pages.create';


        return view($view, get_defined_vars());

    }

    public function pageTable(Request $request,$lang,$id)
    {
        $view = 'front.pages.page-table';

        $curientTable = TableId::where('id',$id)->with('getBody')->with('shorts')->first();

        $defacBody = explode($curientTable->key_detect,$curientTable->getBody->body);





        return view($view, get_defined_vars());

    }
    public function createTable(Request $request){

        $validator = Validator::make($request->all(), [
            'group' => 'required',
            'study_place' => 'required',
            'city' => 'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'messages' => $validator->messages(),
            ]);




        $datap = $request->input('p');
        $key = '//'.bcrypt($request->input('group')).'//';
        $new_datap = ' ';
        foreach($datap as $one_bitch)
        {
            if(is_null($one_bitch)){
                $one_bitch = 'null';
            }
            $new_datap .= $one_bitch.$key;

        }
        $user = new TableId();
        $user->active = 1;
        $user->front_user_id = Session::get('session-front-user');
        $user->key_detect = $key;
        $user->row_count = $request->input('row_count');
        $user->save();

        $r = new TableMain();
        $r->body = $new_datap;
        $r->table_id = $user->id;
        $r->city = $request->input('city');
        $r->group_name = $request->input('group');
        $r->class = $request->input('study_place');
        $r->save();

        if($request->input('head_shorts') != null && $request->input('body_shorts') != null) {
            $s = new Shorts();
            $s->table_id = $user->id;
            $s->head_shorts = $request->input('head_shorts');
            $s->body_shorts  = $request->input('body_shorts');
            $s->save();
        }








//        $string = implode($request->input('p'),'???');

        return 1;

    }

     function createPdfdoc(Request $request)
    {
        $tableId = $request->input('id');
//        $pdfFiles = [];
        $destinationPath = 'upfiles/pdf-tickets/';

        $curientTable = TableId::where('id',$tableId)->with('getBody')->with('shorts')->first();

        $defacBody = explode($curientTable->key_detect,$curientTable->getBody->body);

//         $htmlcontent= $this->load->view('admin/test',$data,true);
//         $this->pdf->loadHtml($htmlcontent);
//         $this->pdf->render();
//

            $pdfFileName = 'table-' . $curientTable->getBody->group_name . '.pdf';

//         PDF::setOptions(['logOutputFile'=>storage_path('tmp/pdf.log'),'tempDir'=>storage_path('tmp/')]);
//         $pdf = PDF::LoadView('front.templates.pdf-table',['defacBody' => $defacBody, 'curientTable' => $curientTable],['title'=>$pdfFileName]);
//         return $pdf->download($pdfFileName);

            $pdfTicket = PDF::loadView('front.templates.pdf-table',['defacBody' => $defacBody, 'curientTable' => $curientTable],['title'=>$pdfFileName]);
          $pdfTicket->save($destinationPath . $pdfFileName);
            $pdfFiles[] = $destinationPath . $pdfFileName;

        return $pdfFiles;
    }

}

