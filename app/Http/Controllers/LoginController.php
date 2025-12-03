<?php

namespace App\Http\Controllers;

use App\Models\Admin\FeesValidity;
use App\Models\admin\Tnelb_Newsboard;
use Carbon\Carbon;
use App\Models\Login_Logs;
use App\Models\MstLicence;
use App\Models\Register;
// use App\Models\Tnelb_Newsboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends BaseController
{

    protected $today;

    public function __construct()
    {
        parent::__construct();
        $this->today = now()->toDateString();
    }

    public function login()
    { 
        return view('login');
    }
    public function check(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            // 'captcha' => ['required'],
        ], [
            'phone.required' => 'Enter Mobile Number.',
            'phone.digits' => 'Enter a valid 10-digit mobile number.',
        ]);



        // Check if the phone number exists
        $user =Register::where('mobile', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a valid user. Please register now.'
            ], 422);
        }


        // Store login ID in session temporarily
        Session::put('login_user', $user->login_id);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully'
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if ($request->otp !== '123456') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 422);
        }

        $loginUser = Session::get('login_user');
        if (!$loginUser) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please log in again.'
            ], 401);
        }

        $user = Register::where('login_id', $loginUser)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Store session and login
        Session::put('login_id', $user->login_id);
        Session::put('user_name', $user->name);
        Auth::login($user);

        Login_Logs::create([
            'login_id' => $user->login_id,
            'ipaddress' => request()->ip(),
            'Idate' => now(),
            'attempt' => 1,
            'duration' => 0.00,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect_url' => route('finalize.login'),

            'user_name' => $user->name,
        ], 200);
    }




    public function logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        $loginId = session('login_id'); // Get login_id from session

        if (!$loginId) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        // Retrieve user details
        $user = DB::table('tnelb_registers')->where('login_id', $loginId)->first();

        // var_dump($user->first_name.$user->last_name);die;
        // Store user name in session
        if ($user) {
            session(['name' => $user->first_name.$user->last_name]);
        }



        // Fetch workflows
        $workflows_cl = DB::table('tnelb_ea_applications as af')
            ->leftJoin('tnelb_license as l', 'af.application_id', '=', 'l.application_id')
            ->leftJoin('tnelb_renewal_license as rl', 'af.application_id', '=', 'rl.application_id')
            ->where('af.login_id', $loginId)
            ->orderBy('af.updated_at', 'desc')
            ->select(
                'af.*',
                'l.expires_at as original_expires_at',
                'rl.expires_at as renewal_expires_at',
                DB::raw("
                    CASE 
                        WHEN af.appl_type = 'N' THEN l.license_number 
                        ELSE rl.license_number 
                    END as license_number
                "),
                DB::raw("
                    CASE 
                        WHEN af.appl_type = 'N' THEN l.expires_at 
                        ELSE rl.expires_at 
                    END as expires_at
                "),
                DB::raw("(
                    SELECT t2.application_id
                    FROM tnelb_ea_applications t2
                    WHERE t2.login_id = af.login_id
                    AND t2.id > af.id
                    AND t2.form_name = af.form_name
                    ORDER BY t2.id ASC
                    LIMIT 1
                ) AS next_application_id")
            )
            ->get();



        $workflows_present = DB::table('tnelb_application_tbl as ta')
            ->where('ta.login_id', $loginId)
            ->orderBy('ta.created_at', 'desc')
            ->get()
            ->map(function ($workflow) {

                $licenseNumber = null;
                $expiry = null;
                $renewalApplicationId = null;
                $isValid = false;
                $validityMonth = null;

                $licenceID = null;


                $licenceID = MstLicence::where('cert_licence_code', $workflow->license_name)->value('id');
                
                // var_dump($workflow->appl_type);
                

            if ($workflow->appl_type === 'N') {
                // Fresh license
                $license = DB::table('tnelb_license')
                    ->where('application_id', $workflow->application_id)
                    ->select('license_number', 'expires_at')
                    ->first();

                if ($license) {
                    // 🔑 Check if renewal exists (draft or submitted) using old_application
                    $renewalApp = DB::table('tnelb_application_tbl')
                        ->where('old_application', $workflow->application_id)
                        ->where('appl_type', 'R')
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($renewalApp) {
                        // Renewal exists → show renewal app id in expired row
                        $renewalApplicationId = $renewalApp->application_id;
                        $licenseNumber = null;
                        $expiry = null;
                    } else {
                        // no renewal yet → show original license details
                        $licenseNumber = $license->license_number;
                        $expiry = $license->expires_at;
                    }
                }
            } elseif ($workflow->appl_type === 'R') {
                // Renewal application itself
                $renewal = DB::table('tnelb_renewal_license')
                    ->where('application_id', $workflow->application_id)
                    ->select('license_number', 'expires_at')
                    ->first();

                if ($renewal) {
                    $licenseNumber = $renewal->license_number;
                    $expiry = $renewal->expires_at;
                }
            }

            // assign back

            

            if ($expiry) {
                // var_dump('sdf');
                $validityMonths = FeesValidity::where('licence_id', $licenceID)
                ->where('form_type', 'A')
                ->where('validity_start_date', '<=', $this->today)
                ->value('validity');
                
                $expiryDate = Carbon::parse($expiry);
                $validFromDate = $expiryDate->copy()->subMonths((int)$validityMonths);
                $today = Carbon::today();

                $oneYearAfterExpiry = $expiryDate->copy()->addYear();

                $isValid = $isValid = ($today->greaterThanOrEqualTo($validFromDate)
             && $today->lessThanOrEqualTo($oneYearAfterExpiry));

                // var_dump($today->greaterThanOrEqualTo($expiryDate->copy()->addYear()));
                
               


            }else {
                // No expiry means license not issued yet -> can't renew
                $isValid = false;
            }

            // var_dump($expiry,$isValid.'<br>');
            

            $workflow->license_number = $licenseNumber;
            $workflow->expires_at = $expiry;
            $workflow->renewal_application_id = $renewalApplicationId;
            $workflow->is_under_validity_period = $isValid;

            return $workflow;

        });

        // die;

        
        // var_dump($workflows_present);die;


        $renewal_applications = DB::table('tnelb_application_tbl as ta')
        ->leftJoin('tnelb_license as l', 'ta.application_id', '=', 'l.application_id')
        ->where('ta.login_id', $loginId)
        ->where('ta.appl_type', 'R')
        ->select(
            'ta.*',
            'l.license_number',
            'l.expires_at',
            DB::raw("(
                SELECT t2.application_id
                FROM tnelb_application_tbl t2
                WHERE t2.login_id = ta.login_id
                    AND t2.id > ta.id
                    AND t2.form_name = ta.form_name
                ORDER BY t2.id ASC
                LIMIT 1
            ) AS next_application_id")
        )
        ->orderBy('ta.created_at', 'desc')
        ->get();


        $present_license = DB::table(function ($query) use ($loginId) {
            // First-time license
            $query->select(
                    'l.license_number',
                    'l.expires_at',
                    'l.issued_at',
                    'ta.application_id',
                    'ta.form_name',
                    'ta.license_name',
                    DB::raw("'N' as license_type")
                )
                ->from('tnelb_license as l')
                ->join('tnelb_application_tbl as ta', 'ta.application_id', '=', 'l.application_id')
                ->where('ta.login_id', $loginId)
    
            ->unionAll(
                // Renewal licenses
                DB::table('tnelb_renewal_license as rl')
                    ->join('tnelb_application_tbl as ta', 'ta.application_id', '=', 'rl.application_id')
                    ->select(
                        'rl.license_number',
                        'rl.expires_at',
                        'rl.issued_at',
                        'rl.application_id',
                        'ta.form_name',
                        'ta.license_name',
                        DB::raw("'R' as license_type")
                    )
                    ->where('rl.login_id', $loginId)
            );
        }, 'licenses')
        // ->whereDate('licenses.expires_at', '>=', now())
        ->orderBy('licenses.expires_at', 'desc')
        ->get();


            $present_license_ea = DB::table('tnelb_ea_applications as ta')
            ->join('tnelb_license as l', 'ta.application_id', '=', 'l.application_id')
            ->where('ta.login_id', $loginId)
            ->select(
                'ta.*',
                'l.*',
            )
            ->get();
            
        
        $table_applied_form = DB::table('tnelb_application_tbl as ta')
        ->where('ta.login_id', $loginId)
        ->pluck('form_name') // only need form_name values
        ->map(fn ($v) => strtoupper(trim($v))) // normalize
        ->toArray();

        $table_applied_formA = DB::table('tnelb_ea_applications as ta')
        ->where('ta.login_id', $loginId)
        ->pluck('form_name') // only need form_name values
        ->map(fn ($v) => strtoupper(trim($v))) // normalize
        ->toArray();
    
    return view('user_login.index', compact(
        'loginId', 'workflows_cl', 'workflows_present', 'present_license', 'present_license_ea', 'table_applied_form', 'table_applied_formA' , 'table_applied_form',
        'renewal_applications' 
    ));
    
    }

    public function noticeboardcontent($news_id)
    {
        // Fetch the record by ID
        $news = Tnelb_Newsboard::find($news_id);

        if (!$news) {
            abort(404, 'Newsboard not found');
        }

        // Pass it to a view
        return view('noticeboardcontent', compact('news'));
    }
}
