<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Admin\TnelbForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Exception;

class FormsManagementController extends BaseController
{

    public function index(){

        $activeForms = TnelbForms::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admincms.forms.forms', compact('activeForms'));
    }


    public function addNewForm(Request $request)
    {

        // var_dump($request->all());die;

        // $request->validate([
        //     'cert_name' => 'required|string',
        //     'form_name' => 'required|string',
        //     // 'license_name' => 'required|string',
        //     'fresh_fees' => 'required|numeric',
        //     'fresh_fees_on' => 'required|date',
        //     // 'freshamount_ends' => 'nullable|date',
        //     'renewal_fees' => 'required|numeric',
        //     'renewal_fees_on' => 'required|date',
        //     // 'renewalamount_ends' => 'nullable|date',
        //     'latefee_for_renewal' => 'required|numeric',
        //     'late_renewal_fees_on' => 'required|date',
        //     'fresh_form_duration' => 'required|numeric',
        //     'fresh_form_duration_on' => 'required|date',
            
        //     'renewal_form_duration' => 'required|numeric',
        //     'renewal_duration_on' => 'required|date',
        //     'renewal_late_fee_duration' => 'required|numeric',
        //     'renewal_late_fee_duration_on' => 'required|date',
        //     'instruction_upload' => 'required|file|mimes:pdf|min:5| max:250',
        //     'form_status' => 'required',
        // ],[
        //     'instruction_upload.min' => 'File size permitted only 5KB to 250KB.',
        //     'instruction_upload.max' => 'File size permitted only 5KB to 250KB.',
        // ]);

        DB::beginTransaction(); 
        

        try {

            
            $filePath = null;
            if ($request->hasFile('instruction_upload')) {
                $file = $request->file('instruction_upload');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('formInstructions', $fileName, 'public'); // stores in storage/app/public/uploads/forms
            }

            $form = TnelbForms::create([
                'form_name'                 => $request->form_name,
                'license_name'              => $request->cert_name,
                'fresh_fee_amount'          => $request->fresh_fees,
                'fresh_fee_starts'          => $request->fresh_fees_on,
                // 'freshamount_ends'          => $request->freshamount_ends,
                'renewal_amount'            => $request->renewal_fees,
                'renewalamount_starts'      => $request->renewal_fees_on,
                // 'renewalamount_ends'        => $request->renewalamount_ends,
                'latefee_amount'            => $request->late_renewal_fees,
                'latefee_starts'            => $request->late_renewal_fees_on,
                'latefee_ends'              => $request->latefee_ends,
                'duration_freshfee'         => $request->fresh_form_duration,
                'duration_renewalfee'       => $request->renewal_form_duration,
                'duration_latefee'              => $request->renewal_late_fee_duration,
                'duration_freshfee_starts'    => $request->fresh_form_duration_on,
                'duration_renewalfee_starts'    => $request->renewal_duration_on,
                'duration_latefee_starts'       => $request->renewal_late_fee_duration_on,
                'duration_latefee_ends'         => $request->duration_latefee,
                'instructions_upload'           => $filePath,
                'status'                        => $request->form_status,
                'created_by'                    => now(),       
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

}
