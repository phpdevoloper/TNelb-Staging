<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Admin\FeesValidity;
use App\Models\Admin\LicenceCategory;
use App\Models\Admin\TnelbForms;
use App\Models\Admin\MstFeesDetail;
use App\Models\MstLicence;
use App\Models\TnelbLicense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use DateTime;
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


        $activeForms = TnelbForms::leftJoin('mst_licences', 'tnelb_forms.licence_id', '=', 'mst_licences.id')
        ->where('tnelb_forms.status', 1)
        ->orderBy('tnelb_forms.created_at', 'desc')
        ->select('mst_licences.licence_name', 'tnelb_forms.*')
        ->get();

         $validity_periods = FeesValidity::leftJoin('mst_licences', 'fees_validity.licence_id', '=', 'mst_licences.id')
        ->where('fees_validity.status', 1)
        ->orderBy('fees_validity.created_at', 'desc')
        ->select('mst_licences.licence_name','mst_licences.form_name', 'fees_validity.*')
        ->get();
        

        // return view('admincms.forms.forms', compact('activeForms', 'all_licences'));
        return view('admincms.forms.feesvalidity', compact('activeForms', 'all_licences', 'validity_periods'));
    }

    

    public function view_licences(){

        $categories = LicenceCategory::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();

        $all_licences = MstLicence::leftJoin('mst_licence_category', 'mst_licences.category_id', '=', 'mst_licence_category.id')
        ->where('mst_licences.status', 1)
        ->orderBy('mst_licences.created_at', 'desc')
        ->select('mst_licence_category.category_name', 'mst_licences.*')
        ->get();

        return view('admincms.forms.view_forms', compact('categories','all_licences'));
    }

    public function add_licence(Request $request)
    {
        try {
            $isUpdate = !empty($request->cert_id);
            // 🔹 1. Validate input fields
            $validated = $request->validate([
                'form_cate'     => 'required|integer',
                'cert_name'         => 'required|string|regex:/^[A-Za-z\s]+$/|min:3|max:100',
                'cate_licence_code' => ['required','string','max:5',Rule::unique('mst_licences', 'cert_licence_code')->ignore($request->cert_id)],
                'form_name'         => 'required|string|regex:/^[A-Za-z\s]+$/|min:2|max:100',
                'form_code'         => ['required','string','max:5',Rule::unique('mst_licences', 'form_code')->ignore($request->cert_id)],
                'form_status'       => 'required|in:1,2',
            ], [
                'form_cate.required'         => 'Please choose the category',
                'cert_name.required'         => 'Please fill the Certificate / Licence Name',
                'cert_name.regex'            => 'Certtificate / Licence Name should contain only letters and spaces',
                'form_name.regex'            => 'Form Code should contain only letters and spaces',
                'cate_licence_code.required' => 'Please fill the Certificate / Licence Code',
                'cate_licence_code.unique'   => 'This Certificate / Licence Code already exists',
                'form_name.required'         => 'Please fill the Form Name',
                'form_name.regex'            => 'Form Name should contain only letters and spaces',
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
            ];

            // var_dump($data);die;

            if ($isUpdate) {
                $data['updated_at'] = now();
                MstLicence::where('id', $request->cert_id)
                ->update($data);

                $message = 'Updated successfully!';
            }else{
                $data['created_at'] = now();
                MstLicence::insert($data);
                $message = 'Created successfully!';
            }

            return response()->json([
                'status'  => true,
                'message' => $message,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->validator->errors()->first(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function licenceCategory(){

        $categories = LicenceCategory::where('status', 1)
                    ->orderBy('created_at', 'asc')
                    ->get();


        return view('admincms.forms.category', compact('categories'));
    }

    public function add_category(Request $request){

        $isUpdate = $request->filled('cate_id');
        
        if ($isUpdate) {
            $request->validate([
                'edit_cate_name' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                'form_status' => ['nullable', 'in:1,2'],
            ], [
                'edit_cate_name.required' => 'Category name is required.',
                'edit_cate_name.regex' => 'Category name should contain only letters and spaces.',
            ]);
            
            $category = LicenceCategory::findOrFail($request->cate_id);
            $category->update([
                'category_name' => $request->edit_cate_name,
                'status' => $request->status ?? $category->status,
                'updated_by' => $this->userId,
                'updated_at' => now()->toDateString(),
            ]);
            
            $message = 'Category updated successfully';
        } else {

            $request->validate([
                'cate_name' => ['required', 'regex:/^[a-zA-Z\s]+$/', Rule::unique((new LicenceCategory())->getTable(), 'category_name')],
                'form_status' => ['nullable', 'in:1,2'],
                ], [
                    'cate_name.required' => 'Category name is required.',
                    'cate_name.regex' => 'Category name should contain only letters and spaces.',
                    'cate_name.unique' => 'This category already exists.',
                ]);

            $category = LicenceCategory::create([
                'category_name' => $request->cate_name,
                'status' => $request->form_status ?? 1,
                'created_by' => $this->userId,
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ]);
    
            $message = 'Category added successfully';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $category
        ]);
    }

    public function formHistory(Request $request){


        $form_id = $request->form_id;


        $formHistory = TnelbForms::where('status', 0)
                    ->where('licence_id', $form_id)
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

    public function getPaymentDetails(Request $request){
        
        $licence_code = $request->licence_code;
        $issued_licence = $request->issued_licence;
        
        // var_dump($issued_licence);die;

        try {


            $licence_details = TnelbLicense::where('license_number', $issued_licence)
            ->select('*')
            ->first();
       
            $current_licence = DB::table('mst_licences as l')
            ->leftJoin('tnelb_forms as f', DB::raw('CAST(f.license_name AS INTEGER)'), '=', 'l.id')
            ->where('f.status', 1)
            ->where('l.cert_licence_code', $licence_code)
            ->select('f.*', 'l.*')
            ->orderBy('f.created_at', 'desc')
            ->first();


            $issuedAt = $licence_details->issued_at;
            $expiry = $licence_details->expires_at;
            
            $lateFeeAmount = $current_licence->latefee_amount;
            $durationInDays = $current_licence->duration_latefee;
            $lateFeeStarts = $current_licence->latefee_starts;
            
            $freshFee = $current_licence->fresh_fee_amount;
            $freshFeeStarts = $current_licence->fresh_fee_starts;
            $renewalFee = $current_licence->renewal_amount;
            $renewalFeeStarts = $current_licence->renewal_amount;
            
            $current = new DateTime('2029-7-28');

            // Calculate the 3-period-before-expiry date (based on durationInDays)
            $threePeriodsBeforeExpiry = (clone $expiry)->modify("-" . ($durationInDays * 3) . " days");
            $fees_details = [];
            $lateFee = 0;

            if ($current < $threePeriodsBeforeExpiry) {
                $lateFee;
            }

            if ($current > $expiry) {
                // ✅ Case 1: After expiry
                $diff = $expiry->diff($current);
                $monthDiff = $diff->m + ($diff->y * 12);
                $fees_details['renewalFee'] = $freshFee;
                $fees_details['lateFees'] = $lateFee;

            }elseif ($current >= $threePeriodsBeforeExpiry && $current <= $expiry) {
                // Case 2: Within the 3 months before expiry
                // How many months difference from the start of 3-month window
                $diff = $threePeriodsBeforeExpiry->diff($current);
                $monthDiff = $diff->m + ($diff->y * 12);

                // At least 1 month means late fee applies
                $lateFee = $current_licence->latefee_amount * ($monthDiff + 1);
                $fees_details['renewalFee'] = $renewalFee;
                $fees_details['lateFees'] = $lateFee;
            }

            $fees_details['certificate_name'] = $current_licence->licence_name;

            if ($current_licence) {
                return response()->json([
                    'status' => 'success',
                    'fees_details' => $fees_details,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No matching licence found.',
                ], 404);
            }

        } catch (Exception $e) {
             return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }    
    }


    public function updateFees(Request $request)
    {
        // var_dump($request->all());die;
        $request->validate([
            'cert_name' => 'required|string',
            'form_name' => 'required|string',
            // 'license_name' => 'required|string',
            'fresh_fees' => 'required|numeric',
            'fresh_fees_on' => 'required|date',
            'fresh_fees_ends_on' => 'nullable|date',
            'renewal_fees' => 'required|numeric',
            'renewal_fees_as_on' => 'required|date',
            'renewal_fees_ends_on' => 'nullable|date',
            'latefee_for_renewal' => 'required|numeric',
            'late_renewal_fees_on' => 'required|date',
            'late_renewal_fees_ends_on' => 'nullable|date',
            // 'fresh_form_duration' => 'required|numeric',
            // 'fresh_form_duration_on' => 'required|date',
            // 'renewal_form_duration' => 'required|numeric',
            // 'renewal_duration_on' => 'required|date',
            // 'renewal_late_fee_duration' => 'required|numeric',
            // 'renewal_late_fee_duration_on' => 'required|date',
            'form_status' => 'required',
        ]);

        DB::beginTransaction(); 
        
        try {

            $form = TnelbForms::create([
                'form_name'                 => $request->form_name,
                'licence_id'                => $request->cert_name,
                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                'fresh_fee_ends'            => $request->fresh_fees_ends_on,

                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_as_on,
                'renewalamount_ends'        => $request->renewal_fees_ends_on,

                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'              => $request->late_renewal_fees_ends_on,
                // 'duration_freshfee'         => $request->fresh_form_duration,
                // 'duration_renewalfee'       => $request->renewal_form_duration,
                // 'duration_latefee'              => $request->renewal_late_fee_duration,
                // 'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                // 'duration_renewalfee_starts'    => $request->renewal_duration_on,
                // 'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                // 'duration_latefee_ends'         => $request->duration_latefee,
                'status'                        => $request->form_status,
                'created_by'                    => $this->userId,       
                // 'category', 
            ]); 

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Form created successfully!',
            ]);

         } catch (Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateForm(Request $request){
        // var_dump($request->all());die;

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

            // 'fresh_form_duration'           => 'required|numeric',
            // 'fresh_form_duration_on'        => 'required|date',
            // 'fresh_form_duration_ends_on'   => 'nullable|date',

            // 'renewal_form_duration'     => 'required|numeric',
            // 'renewal_duration_on'       => 'required|date',
            // 'renewal_duration_ends_on'  => 'nullable|date',

            // 'renewal_late_fee_duration'         => 'required|numeric',
            // 'renewal_late_fee_duration_on'      => 'required|date',
            // 'renewal_late_fee_duration_ends_on' => 'nullable|date',

            'form_status' => 'required',
        ]);

        DB::beginTransaction(); 
        
        try {

            $form_id = $request->form_id;
            $checked_form = TnelbForms::find($form_id);

            if (!$checked_form) {
                return response()->json([
                    'status' => false,
                    'message' => 'Fees Details not found',
                ]);
            }

            
            // Create an array of fields to compare
            $fieldsToCompare = [
                'form_name'                 => $request->form_name,
                'licence_id'                => $request->cert_name,
                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                'fresh_fee_ends'          => $request->fresh_fees_ends_on,
                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                'renewalamount_ends'      => $request->renewal_fees_ends_on,
                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'            => $request->late_renewal_fees_ends_on,
                // 'duration_freshfee'         => $request->fresh_form_duration,
                // 'duration_renewalfee'       => $request->renewal_form_duration,
                // 'duration_latefee'          => $request->renewal_late_fee_duration,
                // 'duration_freshfee_starts'  => $request->fresh_form_duration_on,
                // 'duration_renewalfee_starts'=> $request->renewal_duration_on,
                // 'duration_latefee_starts'   => $request->renewal_late_fee_duration_on,
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
                $checked_form->fresh_fee_ends = now(); 
                $checked_form->renewalamount_ends = now(); 
                $checked_form->latefee_ends = now(); 
                // $checked_form->duration_freshfee_ends = now(); 
                // $checked_form->duration_renewalfee_ends = now(); 
                // $checked_form->duration_latefee_ends = now(); 
                $checked_form->updated_by = $this->userId; 
                $checked_form->updated_at = now(); 
                $checked_form->save();
            }

            $form = TnelbForms::create([
                'form_name'                     => $request->form_name,
                'licence_id'                  => $request->cert_name,

                'fresh_fee_amount'              => $request->fresh_fees,
                'fresh_fee_starts'              => $request->fresh_fees_on,
                'fresh_fee_ends'                => $request->fresh_fees_ends_on,

                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                'renewalamount_ends'        => $request->renewal_fees_ends_on,

                'latefee_amount'            => $request->latefee_for_renewal,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'              => $request->late_renewal_fees_ends_on,

                // 'duration_freshfee'         => $request->fresh_form_duration,
                // 'duration_renewalfee'       => $request->renewal_form_duration,
                // 'duration_latefee'              => $request->renewal_late_fee_duration,

                // 'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                // 'duration_freshfee_ends'    => $request->fresh_form_duration_ends_on,

                // 'duration_renewalfee_starts'    => $request->renewal_duration_on,
                // 'duration_renewalfee_ends'      => $request->renewal_duration_ends_on,

                // 'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                // 'duration_latefee_ends'         => $request->renewal_late_fee_duration_ends_on,

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

    public function management(){

        $all_licences = MstLicence::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->get();
        

        $activeForms = TnelbForms::leftJoin('mst_licences', 'tnelb_forms.licence_id', '=', 'mst_licences.id')
        ->where('tnelb_forms.status', 1)
        ->orderBy('tnelb_forms.created_at', 'desc')
        ->select('mst_licences.licence_name', 'tnelb_forms.*')
        ->get();

        $validity_periods = FeesValidity::leftJoin('mst_licences', 'fees_validity.licence_id', '=', 'mst_licences.id')
        ->where('fees_validity.status', 1)
        ->orderBy('fees_validity.created_at', 'desc')
        ->select('mst_licences.licence_name','mst_licences.form_name', 'fees_validity.*')
        ->get();

        
        // compact('activeForms', 'all_licences')
        return view('admincms.forms.viewLicences', compact('all_licences', 'activeForms','validity_periods'));

    }

    public function updateValidity(Request $request)
    {
        // var_dump($request->all());die;

        $formType = $request->input('form_type');

        $rules = [
            'cert_id' => 'required|integer',
            'form_type'  => 'required|in:N,R',
            'form_status'=> 'nullable|in:1,0,true,false,on',
        ];

        $nullable = [
            'fresh_form_duration'                => 'nullable|numeric|min:1',
            'fresh_form_duration_on'             => 'nullable|date',
            'fresh_form_duration_ends_on'        => 'nullable|date|after_or_equal:fresh_form_duration_on',

            'renewal_form_duration'              => 'nullable|numeric|min:1',
            'renewal_duration_on'                => 'nullable|date',
            'renewal_duration_ends_on'           => 'nullable|date|after_or_equal:renewal_duration_on',

            'renewal_late_fee_duration'          => 'nullable|numeric|min:1',
            'renewal_late_fee_duration_on'       => 'nullable|date',
            'renewal_late_fee_duration_ends_on'  => 'nullable|date|after_or_equal:renewal_late_fee_duration_on',
        ];

        $rules = array_merge($rules, $nullable);

        // var_dump($formType);die;

        switch ($formType) {
            case 'N': // New Form
                $rules = array_merge($rules, [
                    'fresh_form_duration' => 'required|numeric|min:1',
                    'fresh_form_duration_on' => 'required|date',
                    'fresh_form_duration_ends_on' => 'required|date|after_or_equal:fresh_form_duration_on',

                    // prevent mixing renewal fields when form_type = N (optional but strict)
                    'renewal_form_duration'              => 'prohibited',
                    'renewal_duration_on'                => 'prohibited',
                    'renewal_duration_ends_on'           => 'prohibited',
                    'renewal_late_fee_duration'          => 'prohibited',
                    'renewal_late_fee_duration_on'       => 'prohibited',
                    'renewal_late_fee_duration_ends_on'  => 'prohibited',
                ]);
                break;

            case 'R': // Renewal
                $rules = array_merge($rules, [
                    'renewal_form_duration' => 'required|numeric|min:1',
                    'renewal_duration_on' => 'required|date',
                    'renewal_duration_ends_on' => 'required|date|after_or_equal:renewal_duration_on',
                    'renewal_late_fee_duration' => 'required|numeric|min:1',
                    'renewal_late_fee_duration_on' => 'required|date',
                    'renewal_late_fee_duration_ends_on' => 'required|date|after_or_equal:renewal_late_fee_duration_on',

                    // prevent mixing fresh fields when form_type = R (optional but strict)
                    'fresh_form_duration'         => 'prohibited',
                    'fresh_form_duration_on'      => 'prohibited',
                    'fresh_form_duration_ends_on' => 'prohibited',
                ]);
                break;

                
        }

                
        $messages = [
            'cert_id.required'          => 'Please choose the certificate / licence.',
            'form_type.required'          => 'Please choose the form type.',
            'form_status.required'          => 'Please choose the form status.',

            'fresh_form_duration.required'          => 'Please enter the fresh form duration.',
            'fresh_form_duration_on.required'       => 'Please select the fresh form start date (As on).',
            'fresh_form_duration_ends_on.required'  => 'Please select the fresh form end date (Ends on).',
            'fresh_form_duration_ends_on.after_or_equal' => 'Fresh form end date cannot be earlier than the start date.',

            'renewal_form_duration.required'        => 'Please enter the renewal form duration.',
            'renewal_duration_on.required'          => 'Please select the renewal start date (As on).',
            'renewal_duration_ends_on.required'     => 'Please select the renewal end date (Ends on).',
            'renewal_duration_ends_on.after_or_equal'=> 'Renewal end date must be the same day or later than the start date.',

            'renewal_late_fee_duration.required'    => 'Please enter the late fee duration.',
            'renewal_late_fee_duration_on.required' => 'Please select the late fee start date (As on).',
            'renewal_late_fee_duration_ends_on.required' => 'Please select the late fee end date (Ends on).',
            'renewal_late_fee_duration_ends_on.after_or_equal' => 'Late fee end date must be the same day or later than the start date.',

            'renewal_*.*.prohibited'                => 'Renewal fields are not allowed for New Form.',
            'fresh_*.*.prohibited'                  => 'Fresh fields are not allowed for Renewal Form.',
        ];

        $request->validate($rules, $messages);

        // var_dump('dfgfd');die;

        // $request->validate([
        //     'cert_id' => 'required|int',
        //     'form_type' => 'required|string',
        //     // 'license_name' => 'required|string',
            
        //     'renewal_form_duration' => 'required|numeric',
        //     'renewal_duration_on' => 'required|date',
        //     'renewal_late_fee_duration' => 'required|numeric',
        //     'renewal_late_fee_duration_on' => 'required|date',
        //     'form_status' => 'required',
        // ]);

        DB::beginTransaction(); 
        
        try {

            $form = FeesValidity::create([
                'licence_id'                => $request->cert_id,
                'form_type'                 => $request->form_type,
                'duration_freshfee'         => $request->fresh_form_duration,
                'duration_renewalfee'       => $request->renewal_form_duration,
                'duration_latefee'              => $request->renewal_late_fee_duration,
                'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                'duration_freshfee_ends'    => $request->fresh_form_duration_ends_on,
                'duration_renewalfee_starts'    => $request->renewal_duration_on,
                'duration_renewalfee_ends'    => $request->renewal_duration_ends_on,

                'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                'duration_latefee_ends'         => $request->renewal_late_fee_duration_ends_on,
                'status'                        => in_array($request->form_status, ['1', 1, 'true', true, 'on']) ? 1 : 0,
                'created_by'                    => $this->userId,       
                'created_at'                    => now(),       
            ]); 

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Form created successfully!',
            ]);

         } catch (Exception $e) {
            DB::rollBack(); 

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. ' . $e->getMessage(),
            ], 500);
        }
    }




    

}
