<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Admin\LicenceCategory;
use App\Models\Admin\TnelbForms;
use App\Models\MstLicence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\Rule;

class LicenceManagementController extends BaseController
{
    protected $userId;

    public function __construct()
    {
        // ✅ Ensure user must be logged in
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                // Not logged in
                return redirect()->route('login');
            }

            // ✅ If logged in, store the user ID
            $this->userId = Auth::id();

            return $next($request);
        });
    }

    public function index(){

        $all_licences = MstLicence::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();


        $activeForms = TnelbForms::leftJoin('mst_licences', DB::raw('CAST(tnelb_forms.license_name AS INTEGER)'), '=', 'mst_licences.id')
        ->where('tnelb_forms.status', 1)
        ->orderBy('tnelb_forms.created_at', 'desc')
        ->select('mst_licences.licence_name', 'tnelb_forms.*')
        ->get();
        // ->toArray();

        // dd($activeForms);die;

        // var_dump($activeForms);die;
        
        

        return view('admincms.forms.forms', compact('activeForms', 'all_licences'));
    }

    

    public function view_licences(){

        $categories = LicenceCategory::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

         $all_licences = MstLicence::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admincms.forms.view_forms', compact('categories','all_licences'));
    }

    public function add_licence(Request $request)
    {
        // var_dump($request->all());die;
        try {
            // 🔹 1. Validate input fields
            $validated = $request->validate([
                'form_cate'     => 'required|integer',
                'cert_name'         => 'required|string|min:3|max:100',
                'cate_licence_code' => 'required|string|max:5|unique:mst_licences,cert_licence_code',
                'form_name'         => 'required|string|min:2|max:100',
                'form_code'         => 'required|string|max:5|unique:mst_licences,form_code',
                'form_status'       => 'required|in:1,2',
            ], [
                'form_cate.required'         => 'Please choose the category',
                'cert_name.required'         => 'Please fill the Certificate / Licence Name',
                'cate_licence_code.required' => 'Please fill the Certificate / Licence Code',
                'cate_licence_code.unique'   => 'This Certificate / Licence Code already exists',
                'form_name.required'         => 'Please fill the Form Name',
                'form_code.required'         => 'Please fill the Form Code',
                'form_code.unique'           => 'This Form Code already exists',
                'form_status.required'       => 'Please choose the Status',
            ]);

            // 🔹 2. Insert into database (example table: mst_licences)
            $data = [
                'category_id'       => $request->form_cate,
                'licence_name'      => trim($request->cert_name),
                'cert_licence_code' => strtoupper(trim($request->cate_licence_code)),
                'form_name'         => trim($request->form_name),
                'form_code'         => strtoupper(trim($request->form_code)),
                'status'            => $request->form_status,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];


            DB::table('mst_licences')->insert($data);

            // 🔹 3. Return JSON response for AJAX
            return response()->json([
                'status'  => true,
                'message' => 'Form created successfully!',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 🔸 Handle validation errors
            return response()->json([
                'status'  => false,
                'message' => $e->validator->errors()->first(),
            ], 422);

        } catch (\Exception $e) {
            // 🔸 Handle unexpected errors
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function licenceCategory(){

        $categories = LicenceCategory::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();


        return view('admincms.forms.category', compact('categories'));
    }

    public function add_category(Request $request){
         $request->validate([
            'cate_name' => ['required', 'regex:/^[a-zA-Z\s]+$/', Rule::unique((new LicenceCategory())->getTable(), 'category_name')],
        ], [
            'cate_name.required' => 'Category name is required.',
            'cate_name.regex' => 'Category name should contain only letters and spaces.',
            'cate_name.unique' => 'This category already exists.',
        ]);


        $category = LicenceCategory::create([
            'category_name' => $request->cate_name,
            'status' => 1, 
            'created_by' => $this->userId,
            'created_at' => now()->toDateString(),
            'updated_at' => now()->toDateString(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category added successfully',
            'data' => $category
        ]);
    }

    public function formHistory(){

        $formHistory = TnelbForms::where('status', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();


        $html = '';
        $sno = 1;

        foreach ($formHistory as $form) {
            $html .= '<tr>';
            $html .= '<td>' . $sno++ . '</td>';
            $html .= '<td>' . $form->form_name . '</td>';
            $html .= '<td>' . $form->license_name . '</td>';
            $html .= '<td>' . $form->fresh_fee_amount . '</td>';
            $html .= '<td>' . $form->fresh_fee_starts . '</td>';
            $html .= '<td>' . $form->fresh_fee_ends . '</td>';
            $html .= '<td>' . $form->renewal_amount . '</td>';
            $html .= '<td>' . $form->renewalamount_starts . '</td>';
            $html .= '<td>' . $form->renewalamount_ends . '</td>';
            $html .= '<td>' . $form->latefee_amount . '</td>';
            $html .= '<td>' . $form->latefee_starts . '</td>';
            $html .= '<td>' . $form->latefee_ends . '</td>';
            $html .= '<td class="text-center">';
            if ($form->status == 1) {
                $html .= '<span class="badge bg-success">Active</span>';
            } else {
                $html .= '<span class="badge bg-danger">Inactive</span>';
            }
            $html .= '</td>';


            $html .= '<td>' . $form->created_at . '</td>';
            $html .= '<td>' . ($form->updated_at ?? '-') . '</td>';
            $html .= '</tr>';
        }

        return response()->json(['html' => $html]);
        
    }


    public function addNewForm(Request $request)
    {
        $request->validate([
            'cert_name' => 'required|string',
            'form_name' => 'required|string',
            // 'license_name' => 'required|string',
            'fresh_fees' => 'required|numeric',
            'fresh_fees_on' => 'required|date',
            // 'freshamount_ends' => 'nullable|date',
            'renewal_fees' => 'required|numeric',
            'renewal_fees_on' => 'required|date',
            // 'renewalamount_ends' => 'nullable|date',
            'latefee_for_renewal' => 'required|numeric',
            'late_renewal_fees_on' => 'required|date',
            'fresh_form_duration' => 'required|numeric',
            'fresh_form_duration_on' => 'required|date',
            'renewal_form_duration' => 'required|numeric',
            'renewal_duration_on' => 'required|date',
            'renewal_late_fee_duration' => 'required|numeric',
            'renewal_late_fee_duration_on' => 'required|date',
            'form_status' => 'required',
        ]);

        DB::beginTransaction(); 
        

        try {

            
            

            $form = TnelbForms::create([
                'form_name'                 => $request->form_name,
                'license_name'              => $request->cert_name,
                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                // 'freshamount_ends'          => $request->freshamount_ends,
                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                // 'renewalamount_ends'        => $request->renewalamount_ends,
                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'              => $request->latefee_ends,
                'duration_freshfee'         => $request->fresh_form_duration,
                'duration_renewalfee'       => $request->renewal_form_duration,
                'duration_latefee'              => $request->renewal_late_fee_duration,
                'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                'duration_renewalfee_starts'    => $request->renewal_duration_on,
                'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                'duration_latefee_ends'         => $request->duration_latefee,
                'status'                        => $request->form_status,
                'created_by'                    => $this->userId,       
                'category', 
            ]); 

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Form created successfully!',
            ]);

         } catch (Exception $e) {
            DB::rollBack(); 

            // Optional: delete uploaded file if DB failed
            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }

    }

    public function updateForm(Request $request){


        $request->validate([
            'cert_name' => 'required|string',
            'form_name' => 'required|string',
            // 'license_name' => 'required|string',
            'fresh_fees'            => 'required|numeric',
            'fresh_fees_on'         => 'required|date',
            'fresh_fees_ends_on'    => 'nullable|date',
            'renewal_fees'          => 'required|numeric',
            'renewal_fees_on'       => 'required|date',
            'renewal_fees_ends_on'  => 'nullable|date',

            'latefee_for_renewal'       => 'required|numeric',
            'late_renewal_fees_on'      => 'required|date',
            'late_renewal_fees_ends_on' => 'nullable|date',

            'fresh_form_duration'           => 'required|numeric',
            'fresh_form_duration_on'        => 'required|date',
            'fresh_form_duration_ends_on'   => 'nullable|date',

            'renewal_form_duration'     => 'required|numeric',
            'renewal_duration_on'       => 'required|date',
            'renewal_duration_ends_on'  => 'nullable|date',


            'renewal_late_fee_duration'         => 'required|numeric',
            'renewal_late_fee_duration_on'      => 'required|date',
            'renewal_late_fee_duration_ends_on' => 'nullable|date',

            'form_status' => 'required',
        ]);

        DB::beginTransaction(); 
        

        try {
            // var_dump($checked_form);die;

            // if ($checked_form) {
                
            // }

            $form_id = $request->form_id;
            $checked_form = TnelbForms::find($form_id);

            if (!$checked_form) {
                return response()->json([
                    'status' => false,
                    'message' => 'Form not found',
                ]);
            }
            
            // Create an array of fields to compare
            $fieldsToCompare = [
                'form_name'                 => $request->form_name,
                'license_name'              => $request->cert_name,
                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'duration_freshfee'         => $request->fresh_form_duration,
                'duration_renewalfee'       => $request->renewal_form_duration,
                'duration_latefee'          => $request->renewal_late_fee_duration,
                'duration_freshfee_starts'  => $request->fresh_form_duration_on,
                'duration_renewalfee_starts'=> $request->renewal_duration_on,
                'duration_latefee_starts'   => $request->renewal_late_fee_duration_on,
                'status'                    => $request->form_status,
            ];
            
            // Compare current vs old
            $changes = [];
            foreach ($fieldsToCompare as $key => $newValue) {
                $oldValue = $checked_form->$key;
                if ((string)$oldValue !== (string)$newValue) {
                    $changes[$key] = ['old' => $oldValue, 'new' => $newValue];
                }
            }
            
            // If no changes, stop
            if (empty($changes)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No changes detected. Form remains unchanged.',
                ]);
            }



            if ($checked_form) {
                $checked_form->status = 0;
                $checked_form->updated_by = $this->userId; 
                $checked_form->updated_at = now(); 
                $checked_form->save();
            }



            

            $form = TnelbForms::create([
                'form_name'                 => $request->form_name,
                'license_name'              => $request->cert_name,

                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                'fresh_fee_ends'            => $request->fresh_fees_ends_on,

                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                'renewalamount_ends'        => $request->renewal_fees_ends_on,

                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'              => $request->late_renewal_fees_ends_on,

                'duration_freshfee'         => $request->fresh_form_duration,
                'duration_renewalfee'       => $request->renewal_form_duration,
                'duration_latefee'              => $request->renewal_late_fee_duration,

                'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                'duration_freshfee_ends'    => $request->fresh_form_duration_ends_on,

                'duration_renewalfee_starts'    => $request->renewal_duration_on,
                'duration_renewalfee_ends'      => $request->renewal_duration_ends_on,

                'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                'duration_latefee_ends'         => $request->renewal_late_fee_duration_ends_on,

                'status'                        => $request->form_status,
                'created_by'                    => $this->userId,       
                'category', 
            ]); 

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Form Updated successfully!',
            ]);

         } catch (Exception $e) {
            DB::rollBack(); 

            // Optional: delete uploaded file if DB failed
            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }


    

}
