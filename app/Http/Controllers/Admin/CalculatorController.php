<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalcId;
use App\Models\Calc;
use App\Models\CalcInputId;
use App\Models\CalcSubjectId;
use App\Models\CalcSubject;
use App\Models\FormulaId;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalculatorController extends Controller
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
        $view = 'admin.calc.calc';

        $lang_id = $this->lang_id;

        $calc_list = CalcSubjectId::where('active', 1)
            ->where('deleted', 0)
            ->with('itemByLang')
            ->get();

        return view($view, get_defined_vars());
    }

    public function childList(Request $request)
    {
        $view = 'admin.calc.calc-child';

        $lang_id = $this->lang_id;

        $alias = $request->segment(4);

        $parent = CalcSubjectId::where('alias', $alias)
            ->first();
        if ($parent) {
            $calc_list = CalcId::where('active', 1)
                ->where('calc_subject_id', $parent->id)
                ->where('deleted', 0)
                ->with('itemByLang')
                ->get();
        }
        return view($view, get_defined_vars());
    }

    public function editCalc(Request $request)
    {
        $view = 'admin.calc.edit-calc';

        $lang_id = $request->segment(7);

        $lang = Lang::where('id',$lang_id)->pluck('lang')->first();

        $alias = $request->segment(4);

        $types = getEnumValues('calc_id','type_calc');

        $lang_list = Lang::get();

        $calc_id = CalcId::where('active', 1)
            ->where('alias', $alias)
            ->where('deleted', 0)
            ->first();

        $calc_with_lang = Calc::where('lang_id', $lang_id)->where('calc_id',$calc_id->id)
            ->first();

        if ($calc_with_lang != null) {
            $calc_vars = CalcInputId::where('calc_id', $calc_id->id)->with('itemByLang')
                ->get();
        } else {
            $calc_vars = CalcInputId::where('calc_id', $calc_id->id)
                ->get();
        }

        $formulas = FormulaId::where('calc_id',$calc_id->id)->with('itemByLang')->get();


        return view($view, get_defined_vars());
    }

    public function editCalcSubject(Request $request)
    {
        $view = 'admin.calc.edit-calc-subject';

        $lang_id = $request->segment(7);

        $lang = Lang::where('id',$lang_id)->pluck('lang')->first();

        $alias = $request->segment(4);


        $lang_list = Lang::get();

        $calc_id = CalcSubjectId::where('active', 1)
            ->where('alias', $alias)
            ->where('deleted', 0)
            ->first();

        $calc_with_lang = CalcSubject::where('lang_id', $lang_id)->where('calc_subject_id',$calc_id->id)
            ->first();

        return view($view, get_defined_vars());
    }

    public function createItem(Request $request)
    {
        $view = 'admin.calc.create-calc';
        $lang_id = $this->lang_id;
        $lang_list = Lang::get();
        $types = getEnumValues('calc_id','type_calc');
        return view($view, get_defined_vars());
    }

    public function createCalcSubject(Request $request)
    {
        $view = 'admin.calc.create-calc-subject';
        $lang_id = $this->lang_id;
        $lang_list = Lang::get();

        return view($view, get_defined_vars());
    }

    public function saveCalc(Request $request)
    {
        if (request()->input('parent')) {
            $parent = CalcSubjectId::where('alias', request()->input('parent'))
                ->pluck('id')
                ->first();
        } else {
            $parent = CalcId::where('id', $request->segment(6))
                ->pluck('calc_subject_id')
                ->first();
        }

        $formulas = request()->input('formula');
        $formulas_name = request()->input('formula_name');
        $parents = request()->input('parents_formulas');
        $dime = request()->input('formula_dime');
        $dimetext = request()->input('formula_dimetext');

        $alias = $request->segment(4);
        //$calc_id = $request->segment(6);
        $calc_id = $request->segment(6);
        $lang_id = $request->segment(7);

        $calc = CalcId::updateOrCreate(['id' => $calc_id], [
            'alias' => request()->input('alias'),
            'calc_subject_id' => $parent,
            'active' => 1,
            'deleted' => 0,
            'formula' => null,
            'type_calc' => request()->input('type_calc'),
        ]);

        $calc->itemByLang()
            ->updateOrCreate([
                'calc_id' => $calc->id,
                'lang_id' => $this->lang_id,
            ], [
                'name' => request()->input('name'),
                'body' => request()->input('body'),
                'meta_title' => request()->input('meta_title'),
                'meta_keywords' => request()->input('meta_keywords'),
                'meta_description' => request()->input('meta_description'),
            ]);
        $calc->push();

            foreach ($formulas as $key => $one_formula) {
                if ($formulas[$key] != null) {

                    $calcFormula = FormulaId::updateOrCreate(['id' => $key], [
                        'formula' => $one_formula,
                        'calc_id' => $calc->id,
                        'p_id' => isset($parents[$key]) ? $parents[$key] : 0,
                    ]);

                    $calcFormula->itemByLang()->updateOrCreate([
                            'formula_id' => $calcFormula->id,
                            'lang_id' => $this->lang_id,
                        ], [
                            'name' => $formulas_name[$key],
                            'dime' => $dime[$key],
                            'dime_text' => $dimetext[$key],
                        ]);
                    $calcFormula->push();

                }
        }


        if (request()->input('p')) {
            $vars = request()->input('p');
            $v = request()->input('v');
            $text_before = request()->input('v_before');
            $text_after = request()->input('v_after');

            foreach ($vars as $key => $one_var) {
                if ($v[$key] != null) {
                    $varry = CalcInputId::updateOrCreate([
                        'calc_id' => $calc->id,
                        'id' => $key
                    ], [
                        'variable' => $v[$key],
                    ]);
                    $varry->itemByLang()
                        ->updateOrCreate([
                            'calc_input_row_id' => $varry->id,
                            'lang_id' => $this->lang_id,
                        ], [
                            'name' => $one_var,
                            'after_text' => $text_after[$key],
                            'before_text' => $text_before[$key],
                        ]);

                    $varry->push();

                }

            }
        }

        return response()->json([
            'status' => true,
                    'messages' => [controllerTrans('variables.save', $this->lang)],
                   'redirect' => urlForFunctionLanguage($this->lang, request()->input('alias').'/editCalc/'.$calc->id.'/'.$this->lang_id)
        ]);

    }

    public function saveCalcSubject(Request $request)
    {

        $calc_id = $request->segment(6);
        $lang_id = $request->segment(7);

        $calc = CalcSubjectId::updateOrCreate(['id' => $calc_id], [
            'alias' => request()->input('alias'),
            'active' => 1,
            'deleted' => 0,
        ]);

        $calc->itemByLang()
            ->updateOrCreate([
                'calc_subject_id' => $calc->id,
                'lang_id' => $lang_id,
            ], [
                'name' => request()->input('name'),
                'body' => request()->input('body'),
                'meta_title' => request()->input('meta_title'),
                'meta_keywords' => request()->input('meta_keywords'),
                'meta_description' => request()->input('meta_description'),
            ]);
        $calc->push();

        return response()->json([
            'status' => true,
            'messages' => ['Успех'],
            //        'messages' => [controllerTrans('variables.save', $this->lang)],
                   'redirect' => urlForFunctionLanguage($this->lang, request()->input('alias').'/editCalcSubject/'.$calc->id.'/'.$this->lang_id)
        ]);

    }

    public function deleteCalcRow()
    {
        $calcId = CalcInputId::where('id', request()->input('id'))->delete();

        return response()->json([
            'status' => true,
            'messages' => ['Успех'],
        ]);
    }

    public function deleteCalcFormula()
    {
        $calcId = FormulaId::where('id', request()->input('id'))->delete();

        return response()->json([
            'status' => true,
            'messages' => ['Успех'],
        ]);
    }

    public function destroyCalcId()
    {
        $ids = request()->input('id');
        $ids = substr($ids, 1, -1);
        $ids = explode(',', $ids);
        foreach ($ids as $one_id) {
            $calcId = CalcId::where('id', $one_id)->delete();
        }


        return response()->json([
            'status' => true,
            'deleted_elements' => $ids
        ]);
    }


    public function destroyCalcSubjectId()
    {
        $ids = request()->input('id');
        $ids = substr($ids, 1, -1);
        $ids = explode(',', $ids);
        foreach ($ids as $one_id) {
            $calcId = CalcSubjectId::where('id', $one_id)->delete();
        }


        return response()->json([
            'status' => true,
            'deleted_elements' => $ids
        ]);
    }

}