<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Mst_Staffs;
use App\Models\Admin\Mst_Staffs_Tbl;
use App\Models\Admin\MstRoles;
use App\Models\Admin\StaffAssigned;
use App\Models\Admin\TnelbForms;
use App\Models\MstLicence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{

     protected $updatedBy,$supervisorRoleId;

    public function __construct()
    {
        // Ensure user is authenticated before accessing
        $this->middleware(function ($request, $next) {
            $this->updatedBy = Auth::user()->name ?? 'System';
            $this->supervisorRoleId = MstRoles::where('name', 'supervisor')->value('id');
            return $next($request);
        });
    }
    public function index(){

        $staffs = Mst_Staffs_Tbl::orderBy('roles_id','asc')->get();
        // $staff = Mst_Staffs_Tbl::with('assignedForms')->with('assignedForms')->find($id);
        // var_dump($staff);die;
        $forms = MstLicence::all();
        
        $formlist = MstLicence::where('status', 1)
        ->orderBy('category_id', 'asc')
        ->get();

        $userRoles = MstRoles::all();

        return view('admincms.staffdetails.index', compact( 'staffs', 'forms', 'formlist','userRoles'));
    }

    public function insertStaff(Request $request)
    {
        
        // var_dump($request->all());
        // exit;
        
        DB::beginTransaction();

        try {

            $request->validate([
            'staff_name'          => ['required', 'regex:/^[A-Za-z ]+$/', 'max:50'],
            'role_id'             => 'required| int',
            'staff_email'         => 'required|email|unique:mst_staffs,staff_email',
            'handle_forms'        => 'required|array',
            'status'              => 'required|in:1,2',
            
            ],[
                'staff_name.required' => 'Please enter staff name.',
                'staff_name.regex' => 'Staff name may only contain letters and spaces.',
                'staff_name.max'      => 'Staff name cannot exceed 50 characters.',
                
                'roles_id.required'   => 'Please select a user role.',
                // 'name.required'       => 'Please enter designation name.',
                // 'name.string'         => 'Designation name must be a valid text.',
                // 'name.unique'         => 'This designation name is already taken.',
            
                'staff_email.required'      => 'Please enter an email address.',
                'staff_email.email'         => 'Please enter a valid email address.',
                'staff_email.unique'        => 'This email is already registered.',

                'handle_forms.required' => 'Please select at least one form.',
                'handle_forms.array'    => 'Invalid form selection.',
            
            
                'status.required'     => 'Please select the staff status.',
                'status.in'           => 'Invalid status value selected.',

            ]);


            // $form_id = $request->handle_forms;

            $lastStaffID = Mst_Staffs::latest('s_id')->value('staff_id');

            // ✅ Generate next staff_id safely
            $lastStaffId = Mst_Staffs::lockForUpdate()
                ->orderBy('s_id', 'desc')
                ->value('staff_id');

            $nextNumber = $lastStaffId
            ? ((int) preg_replace('/\D/', '', $lastStaffId) + 1)
            : 1;

             $staffId = 'STF' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);


            //  var_dump($staffId);
            //     exit;


            $staff = Mst_Staffs::create([
                'staff_id'    => $staffId,
                'role_id'       => $request->role_id,
                'staff_name'    => $request->staff_name,
                'staff_email'         => $request->staff_email,
                'login_passwd'    => Hash::make($request->user_random_pass),
                // 'handle_forms'  => json_encode($request->handle_forms),
                'status'        => $request->status,
                // 'created_by'    => $request->created_by,
                'updated_by'    => Auth::id(),
                
            ]);


            // if (!empty($request->form_ids)) {
            //     foreach ($request->form_ids as $formId) {
            //         StaffAssigned::create([
            //             'staff_id'    => $staff->id,
            //             'form_id'     => $formId,
            //             'is_active'   => 1,
            //             'assigned_by'=> Auth::id(),
            //             'assigned_at'=> now(),
            //         ]);
            //     }
            // }

            foreach ($request->handle_forms as $formId) {
                StaffAssigned::create([
                    'staff_id'     => $staff->staff_id,
                    'form_id'      => $formId,
                    'is_active'    => 1,
                    'assigned_by'  => Auth::id(),
                    'assigned_at'  => now(),
                ]);
            }

            DB::commit();

           
        
            
            // Step 2: Update forms with this staff_id
            // TnelbForms::whereIn('id', $request->handle_forms)->update([
            //     'staff_id' => $staff->id,
            //     'Assigned' => 'A'
            // ]);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Staff Added Successfully.',
                'staff' => $staff,
                'form_names' => TnelbForms::whereIn('id', $request->handle_forms)->pluck('form_name')->toArray()
            ]);


        }catch (ValidationException $e) {

            return response()->json([
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(), // remove in production
            ], 500);

        }

    }


    // ------------------------update staff details-----------------------

    public function updateStaff(Request $request)
{
    // dd($request->all());
    // exit;
    $request->validate([
        'id'            => 'required|exists:mst__staffs__tbls,id',
        'staff_name'    => 'required|string',
        'name'          => 'required|string',
        'email'         => 'required|email|unique:mst__staffs__tbls,email,' . $request->id,
        'handle_forms'  => 'required|array',
        'status'        => 'required|in:0,1,2',
        
    ]);

    $staff = Mst_Staffs_Tbl::findOrFail($request->id);

    // Update staff
    $staff->update([
        'staff_name'    => $request->staff_name,
        'name'          => $request->name,
        'email'         => $request->email,
        'handle_forms'  => json_encode($request->handle_forms),
        'status'        => $request->status,
        'updated_by'    => $this->updatedBy,
    ]);
    
    // Reset old forms (optional logic to clear old assignments)
    MstLicence::where('staff_id', $staff->id)->update([
        'staff_id' => null,
    ]);
    
    // Assign selected forms
    $formNames = MstLicence::whereIn('id', $request->handle_forms)->update([
        'staff_id' => $staff->id,
        // 'Assigned' => 'A'
    ]);
    
    // ✅ Get form names to return
    // $formNames = MstLicence::whereIn('id', $request->handle_forms)->pluck('form_name')->toArray();
    
    return response()->json([
        'status' => 'success',
        'message' => 'Staff updated successfully.',
        'staff' => $staff,
        'form_names' => $formNames,
        'handle_forms' => $request->handle_forms
    ]);
    
    
}

    

}
