<?php

namespace App\Http\Controllers\Admin;

use App\Models\EA_Application_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class SecretaryController extends Controller
{
    public function index()
    {
        $applications = DB::table('tnelb_application_tbl')
            ->where('status', '!=', 'P') // Secretary only processes verified applications
            ->get();

        return view('admin.dashboards.secretary', compact('applications'));
    }
    public function view_secratary(Request $request)
    {

        $formId = $request->query('form_id');

        $new_applications = DB::table('tnelb_application_tbl as ta')
        ->where('ta.form_id', $formId)
        ->where(function($q) {
            $q->where(function($q2) {
                $q2->where('ta.processed_by', 'A');
            })
            ->orWhereIn('ta.status', ['RF', 'F']);
        })
        ->where('ta.appl_type', 'N')
        ->select('ta.*')
        ->orderByDesc('ta.id')
        ->get();


       $renewal = DB::table('tnelb_application_tbl as ta')
        ->where('ta.form_id', $formId)
        ->where(function($q) {
            $q->where(function($q2) {
                $q2->where('ta.processed_by', 'A');
            })
            ->orWhereIn('ta.status', ['RF', 'F']);
        })
        ->where('ta.appl_type', 'R')
        ->select('ta.*')
        ->orderByDesc('ta.id')
        ->get();


        return view('admin.secretary.view_pending', compact('new_applications','renewal'));

    }
    public function view_president(Request $request)
    {

        $formId = $request->query('form_id');

        $new_applications = DB::table('tnelb_application_tbl as ta')
            ->where('ta.processed_by', 'SE')
            ->where('ta.appl_type', 'N')
            ->where('ta.status', 'F','RF')
            ->where('ta.form_id', $formId)
            ->select('ta.*')
             ->orderByDesc('ta.id')
            ->get();

        $renewal_applications = DB::table('tnelb_application_tbl as ta')
            ->where('ta.processed_by', 'SE')
            ->where('ta.appl_type', 'R')
            ->where('ta.status', 'F','RF')
            ->where('ta.form_id', $formId)
            ->select('ta.*')
             ->orderByDesc('ta.id')
            ->get();

        return view('admin.auditor.view', compact('new_applications','renewal_applications'));
        
    }
    public function completed_secratary(Request $request)
    {

        $formId = $request->query('form_id');
        
        $workflows = DB::table('tnelb_application_tbl as ta')
        ->whereIn('ta.status', ['F','A'])
            ->where('ta.form_id', $formId)
            ->select('ta.*')
            ->get();

            return view('admin.secretary.completed', compact('workflows'));
    }
    public function completed_pres(Request $request)
    {
        
        $formId = $request->query('form_id');

        $workflows = DB::table('tnelb_application_tbl as ta')
            ->where('ta.status', 'A')
            ->where('ta.processed_by','PR')
            ->where('ta.form_id', $formId)
            ->select('ta.*')
            ->orderByDesc('ta.id')
            ->get();

        return view('admin.secretary.completed', compact('workflows'));
    }
    
    public function view_sec_forma_pending(Request $request)
    {
    
        // $formId = $request->query('form_id');
     $workflows = DB::table('tnelb_ea_applications as ta')
            ->whereIn('ta.processed_by', ['A', 'SPRE']) 
            ->orWhere('ta.application_status', 'F')
            ->orderByDesc('updated_at')
            // ->where('ta.form_id', $formId)
            ->select('ta.*')
            ->get();

    //    $workflows = DB::table('tnelb_ea_applications as ta')
    // ->where(function($q) {
    //     $q->where('ta.processed_by', '=', 'A')
    //       ->orWhereIn('ta.application_status', ['RF', 'F']);
    // })
    // ->orderby('updated_at', 'DESC')
    // // ->where('ta.appl_type', '=', 'N')
    // ->select('ta.*')
    // ->get();

        // $workflows = DB::table('tnelb_ea_applications as ta')
        //     ->where('ta.processed_by', 'A')
        //     ->orWhere('ta.application_status', 'RF')
        //     // ->where('ta.form_id', $formId)
        //     ->select('ta.*')
        //     ->get();
    
        return view('admin.secretary.view_pending_forma', compact('workflows'));
    
    }

    public function view_sec_forma_completed(Request $request)
    {
     $workflows = DB::table('tnelb_ea_applications as ta')
        ->whereIn('ta.application_status', ['F','A', 'RE'])
        ->orderByDesc('updated_at')
            // ->where('ta.form_id', $formId)
            ->select('ta.*')
            ->get();
        // $formId = $request->query('form_id');
    
       // $workflows = DB::table('tnelb_ea_applications as ta')
       //      ->whereIn('ta.processed_by', ['A', 'SPRE']) 
       //      ->orWhere('ta.application_status', 'RF')
       //      // ->where('ta.form_id', $formId)
       //      ->select('ta.*')
       //      ->get();
    
        return view('admin.secretary.view_completed_forma', compact('workflows'));
    
    }

    public function secratary_completed(Request $request)
    {

        $formId = $request->query('form_id');

        if($formId == 2){

            $workflows = DB::table('tnelb_application_tbl as ta')
            ->whereIn('ta.processed_by', ['S2','SE'])
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
            ->whereIn('ta.processed_by', ['SE','PR'])
            ->where('ta.form_id', $formId)
            ->where(function ($query) {
                $query->where('ta.status', 'A')
                        ->orWhere('ta.status', 'F');
            })
            ->select('ta.*')
            ->orderByDesc('ta.id')
            ->get();
            
        }


        return view('admin.secretary.completed', compact('workflows'));
    }
}
