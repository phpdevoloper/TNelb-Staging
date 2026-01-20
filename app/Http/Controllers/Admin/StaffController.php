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
        
        $staffs = DB::table('mst_staffs as st')
        ->leftJoin('mst__roles as role', 'role.id', '=', 'st.role_id')
        ->leftJoin('staff_assigned as sa', function ($join) {
            $join->on('sa.staff_id', '=', 'st.staff_id')
                ->where('sa.is_active', '=', 1);
        })
        ->leftJoin('mst_licences as f', 'f.id', '=', 'sa.form_id')
        ->select(
            'st.staff_id',
            'st.staff_name',
            'st.staff_email',
            'st.status',
            'role.name as role_name',
            'role.id as role_id',
            DB::raw("STRING_AGG(f.form_name, ', ') as handling_forms")
        )
        ->groupBy(
            'st.staff_id',
            'st.staff_name',
            'st.staff_email',
            'st.status',
            'role.name',
            'role.id'
        )
        ->get();

        $supervisorRoleId = DB::table('mst__roles')
        ->where('role_code', 'SUPERVISOR')
        ->value('id');

        
        $forms = MstLicence::all();
        
        $formlist = MstLicence::where('status', 1)
        ->orderBy('category_id', 'asc')
        ->get();

        $userRoles = MstRoles::all();

        return view('admincms.staffdetails.index', compact( 'staffs', 'forms', 'formlist','userRoles'));
    }


    public function getAssignedForms(Request $request)
    {
        $formIds = DB::table('staff_assigned')
            ->where('staff_id', $request->staff_id)
            ->where('is_active', 1)
            ->pluck('form_id')
            ->toArray();

        return response()->json([
            'status' => true,
            'form_ids' => $formIds
        ]);
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
        // var_dump($request->all());die;
        $request->validate([
            'staff_id'     => 'required|exists:mst_staffs,staff_id',
            'staff_name'   => 'required|string|max:50',
            'email'        => 'required|email|unique:mst_staffs,staff_email,' . $request->staff_id . ',staff_id',
            'role_id'      => 'required|integer',
            'handle_forms' => 'required|array|min:1',
            'status'       => 'required|in:0,1,2',
        ]);

        DB::beginTransaction();

        try {

            // ✅ Update staff master
            $staff = Mst_Staffs::where('staff_id', $request->staff_id)->firstOrFail();

            $staff->update([
                'staff_name' => $request->staff_name,
                'email'=> $request->staff_email,
                'role_id'    => $request->role_id,
                'status'     => $request->status,
                'updated_by' => Auth::id(),
            ]);

            // ✅ Deactivate old assignments
            StaffAssigned::where('staff_id', $staff->staff_id)
                ->update(['is_active' => 0]);

            // ✅ Assign new forms
            foreach ($request->handle_forms as $formId) {
                StaffAssigned::updateOrCreate(
                    [
                        'staff_id' => $staff->staff_id,
                        'form_id'  => $formId,
                    ],
                    [
                        'is_active'   => 1,
                        'assigned_by'=> Auth::id(),
                        'assigned_at'=> now(),
                    ]
                );
            }

            // ✅ Fetch updated form names
            $formNames = DB::table('staff_assigned as sa')
                ->join('mst_licences as f', 'f.id', '=', 'sa.form_id')
                ->where('sa.staff_id', $staff->staff_id)
                ->where('sa.is_active', 1)
                ->pluck('f.form_name')
                ->toArray();

            DB::commit();

            return response()->json([
                'status'     => true,
                'message'    => 'Staff updated successfully.',
                'staff'      => $staff,
                'form_names' => $formNames,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> 'Update failed',
                'error'  => $e->getMessage()
            ], 500);
        }
    }


    

}
