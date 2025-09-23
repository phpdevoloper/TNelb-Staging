<?php

namespace App\Http\Controllers;

use App\Models\EA_Application_model;
use App\Models\Mst_Form_s_w;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Register;
use App\Models\TnelbApplicantPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class RegisterController extends BaseController
{

    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard'); // Redirect logged-in users to dashboard or any other page
        }

        return view('register'); // Show register page for guests
    }



    public function store(Request $request)
    {
        // Validate Input
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Transgender',
            'PhoneNo' => 'required|digits:10|unique:tnelb_registers,mobile',
            'EmailAddress' => 'nullable|email|unique:tnelb_registers,email',
            'Address' => 'required|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'pincode' => 'required|digits:6',
            'aadhaar' => 'required|digits:12',
            'pancard' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate Login ID
        $latestRecord = Register::latest('id')->first();

        if ($latestRecord && preg_match('/tnelb_(\d+)/', $latestRecord->login_id, $matches)) {
            $newRecord = (int) $matches[1] + 1;
        } else {
            $newRecord = 1120; // Start from 1120 if no previous records exist
        }

        $newLoginId = 'tnelb_' . $newRecord;

        // Store Data in Database
        $register = Register::create([
            'name' => $request->input('Name'),
            'gender' => $request->input('gender'),
            'mobile' => $request->input('PhoneNo'),
            'email' => $request->input('EmailAddress'),
            'address' => $request->input('Address'),
            'state' => $request->input('state'),
            'district' => $request->input('district'),
            'pincode' => $request->input('pincode'),
            'aadhaar' => $request->input('aadhaar'),
            'pancard' => $request->input('pancard'),
            'login_id' => $newLoginId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'login_id' => $newLoginId,
        ], 200);
    }

    public function user_login()
    {
        return view('user_login.index');
    }

    // public function renew_form_bk($appl_id)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('logout');
    //     }

    //     if (!$appl_id) {
    //         return redirect()->route('dashboard')->with('error', 'Application ID is required.');
    //     }
        
    //     $application_details = DB::table('tnelb_application_tbl')
    //     ->where('application_id', $appl_id)
    //     // ->where('appl_type', 'R')
    //     ->select('*')
    //     ->first();

        
    //     if (!$application_details) {
    //         return redirect()->route('dashboard')->with('error', 'Application not found.');
    //     }

    //     // $appIds = [$appl_id, $application_details->old_application];
        
    //     $edu_details = DB::table('tnelb_applicants_edu')
    //     ->where('application_id', $appl_id)
    //     ->select('*')
    //     ->get();

    //     $exp_details = DB::table('tnelb_applicants_exp')
    //     ->where('application_id', $appl_id)
    //     ->select('*')
    //     ->get();

        
    //     $apps_doc = DB::table('tnelb_applicants_doc')
    //     ->where('application_id', $appl_id)
    //     ->select('*')
    //     ->get();


    //     $license_details = DB::table('tnelb_license')
    //     ->where('application_id', $appl_id)
    //     ->select('*')
    //     ->first();

    //     $applicant_photo = TnelbApplicantPhoto::where('application_id', $appl_id)
    //     ->latest('id')
    //     ->first();

    //     $applicationid = $appl_id;


    //     return view('user_login.renew-form', compact( 'applicationid','application_details','edu_details','exp_details','apps_doc','license_details','applicant_photo'));
    // }

    public function renew_formcc($appl_id)
    {
        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        if (!$appl_id) {
            return redirect()->route('dashboard')->with('error', 'Application ID is required.');
        }
        
        $application_details = DB::table('tnelb_application_tbl')
        ->where('application_id', $appl_id)
        ->select('*')
        ->first();



        if (!$application_details) {
            return redirect()->route('dashboard')->with('error', 'Application not found.');
        }
        
        $edu_details = DB::table('tnelb_applicants_edu')
        ->where('application_id', $appl_id)
        ->select('*')
        ->orderBy('year_of_passing', 'desc')
        ->get();


        $exp_details = DB::table('tnelb_applicants_exp')
        ->where('application_id', $appl_id)
        ->select('*')
        ->orderBy('id', 'asc')
        ->get();

        
        $apps_doc = DB::table('tnelb_applicants_doc')
        ->where('application_id', $appl_id)
        ->select('*')
        ->get();

        if ($application_details->appl_type == 'R') {
            $license_details = DB::table('tnelb_license')
            ->where('license_number', $application_details->license_number)
            ->select('*')
            ->first();
        }else{
            $license_details = DB::table('tnelb_license')
            ->where('application_id', $appl_id)
            ->select('*')
            ->first();
        }

        $applicant_photo = TnelbApplicantPhoto::where('application_id', $appl_id)
        ->latest('id')
        ->first();

        $applicationid = $appl_id;
        return view('user_login.renew-form', compact( 'applicationid','application_details','edu_details','exp_details','apps_doc','license_details','applicant_photo'));
    }


    public function apply_form_s()
    {
        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        // $user_id = Auth::user()->login_id;
        // $check_applications = Mst_Form_s_w::where('login_id', $user_id)
        //         ->where('form_name', 'S')
        //         ->exists();

        // if ($check_applications) {
        //     return redirect()->route('dashboard')->with('already_applied', true);
        // }

        return view('user_login.apply-form-s');
    }

    public function apply_form_w()
    {

        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        // $user_id = Auth::user()->login_id;
        // $check_applications = Mst_Form_s_w::where('login_id', $user_id)
        //         ->where('form_name', 'W')
        //         ->exists();

        // if ($check_applications) {
        //     return redirect()->route('dashboard')->with('already_applied', true);
        // }


        return view('user_login.apply-form-w');
    }


    public function apply_form_wh()
    {

        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        // $user_id = Auth::user()->login_id;
        // $check_applications = Mst_Form_s_w::where('login_id', $user_id)
        //         ->where('form_name', 'WH')
        //         ->exists();

        // if ($check_applications) {
        //     return redirect()->route('dashboard')->with('already_applied', true);
        // }


        return view('user_login.apply-form-wh');
    }

    public function apply_form_a()
    {

        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        // $user_id = Auth::user()->login_id;
        // $check_applications = EA_Application_model::where('login_id', $user_id)
        //         ->where('form_name', 'A')
        //         ->exists();

        // if ($check_applications) {
        //     return redirect()->route('dashboard')->with('already_applied', true);
        // }


        return view('user_login.apply-form-a');
    }

    public function loginpage()
    {
        return view('loginpage');
    }

    public function apply_form($form_name, $application_id)
    {
        if (!Auth::check()) {
            return redirect()->route('logout');
        }

        
        $application = Mst_Form_s_w::where('application_id', $application_id)->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Application not found.');
        }

        // Return the view with the fetched data
        $viewName = ($form_name === 's') ? 'user_login.apply-form-s' : 'user_login.apply-form-w';

        // Return the dynamic view
        return view($viewName, compact('application', 'form_name'));
    }


  


    
}
