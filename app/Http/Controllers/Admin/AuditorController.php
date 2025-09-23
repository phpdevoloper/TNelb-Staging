<?php

namespace App\Http\Controllers\Admin;

use App\Models\EA_Application_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditorController extends Controller
{
    public function index()
    {
        $applications = DB::table('tnelb_application_tbl')
            ->select('*')
            ->get();

        return view('admin.dashboards.auditor', compact('applications'));
    }

    public function view(Request $request)
    {

        $formId = $request->query('form_id');

        $new_applications = DB::table('tnelb_application_tbl as ta')
        ->where('ta.appl_type', 'N')
        ->where('ta.form_id', $formId)
        ->where(function($q) {
            $q->where(function($sub) {
                $sub->whereIn('ta.status', ['F'])
                    ->where('ta.processed_by', 'S');
            })
            ->orWhere('ta.processed_by', 'S2');
        })
        ->select('ta.*')
        ->orderByDesc('ta.id')
        ->get();


        // var_dump($new_applications);die;

        $renewal_applications = DB::table('tnelb_application_tbl as ta')
        ->where('ta.appl_type', 'R')
        ->where('ta.form_id', $formId)
        ->where(function($q) {
            $q->where(function($sub) {
                $sub->whereIn('ta.status', ['F'])
                    ->where('ta.processed_by', 'S');
            })
            ->orWhere('ta.processed_by', 'S2');
        })
        ->select('ta.*')
        ->orderByDesc('ta.id')
        ->get();



        return view('admin.auditor.view', compact('new_applications','renewal_applications'));
    }
    public function view_completed(Request $request)
    {

        $formId = $request->query('form_id');

        if($formId == 2){

            $workflows = DB::table('tnelb_application_tbl as ta')
            ->whereIn('ta.processed_by', ['S2','A','SE','PR'])
            ->where('ta.form_id', $formId)
            ->where(function ($query) {
                $query->where('ta.status', 'A')
                        ->orWhere('ta.status', 'F');
            })
            ->select('ta.*')
            ->orderByDesc('ta.id')
            ->get();


            
        } else {
            $workflows = DB::table('tnelb_application_tbl as ta')
            ->whereIn('ta.processed_by', ['SE','PR','A'])
            ->where('ta.form_id', $formId)
            ->where(function ($query) {
                $query->where('ta.status', 'A')
                        ->orWhere('ta.status', 'F');
            })
            ->select('ta.*')
            ->orderByDesc('ta.id')
            ->get();


            // $workflows = DB::table('tnelb_application_tbl as ta')
            // ->where('ta.status',['F','A'])
            // ->where('ta.processed_by',['A'])
            // ->where('ta.status', '!=', 'RF')
            // ->where('ta.appl_type','N')
            // ->orWhere('ta.processed_by','S2')
            // ->where('ta.form_id', $formId)
            // ->select('ta.*')
            // ->orderByDesc('ta.id')
            // ->get();

            // dd($workflows);die;
            
        }


        return view('admin.supervisor.completed', compact('workflows'));
    }

    public function view_forma_pending()
    {
        $userRole = Auth::user()->roles_id; // Auditor's Role ID (2)

        $workflows = DB::table('tnelb_ea_applications')
        ->whereIn('application_status', ['F'])
        ->whereIn('processed_by', ['S']) 
        ->orderBy('created_at', 'DESC')
        ->select('*')
        ->get();

        return view('admin.auditor.view_forma', compact('workflows'));
    }

    public function view_forma_completed()
    {
        $userRole = Auth::user()->roles_id; // Auditor's Role ID (2)

        $workflows = EA_Application_model::whereIn('application_status', ['F','A','RE'])
        ->whereIn('processed_by', ['A','SE','PR']) 
        ->orderby('updated_at', 'DESC')
        ->select('*')
        ->get();

        return view('admin.auditor.completed_forma', compact('workflows'));
    }

    public function view_rejected(Request $request)
    {

        $page_title = 'Rejected';
        $formId = $request->query('form_id');



        if($formId == 2){

            $workflows = DB::table('tnelb_application_tbl as ta')
            // ->whereIn('ta.processed_by', ['S2','A','SE'])
            ->where('ta.form_id', $formId)
            ->where('ta.status', 'RJ')
            ->select('ta.*')
            ->get();
            
        } else {
            $workflows = DB::table('tnelb_application_tbl as ta')
            // ->whereIn('ta.processed_by', ['SE','PR','A'])
            ->where('ta.form_id', $formId)
            ->where('ta.status', 'RJ')
            ->select('ta.*')
            ->get();   
        }


        return view('admin.supervisor.rejected', compact('workflows','page_title'));
    }
}
