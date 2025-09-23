<?php

namespace App\Http\Controllers;

use App\Models\EA_Application_model;
use App\Models\mst_workflow;
use App\Models\Payment;
use App\Models\ProprietorformA;
use App\Models\TnelbApplicantStaffDetail;
// use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FormAController extends BaseController
{
   private function toUpperCaseRecursive($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->toUpperCaseRecursive($value);
            } elseif (is_string($value)) {
                $data[$key] = strtoupper($value);
            }
        }
        return $data;
    }
    public function formatDatesToDMY(array $fields, Request $request)
    {
        foreach ($fields as $field) {
            $original = $request->input($field);

            if (is_array($original)) {
                $converted = [];

                foreach ($original as $index => $value) {
                    $converted[$index] = $value ? $this->convertToDMY($value) : null;
                }

                // Merge back into request
                $request->merge([
                    $field => $converted
                ]);
            } else {
                if ($original) {
                    $request->merge([
                        $field => $this->convertToDMY($original)
                    ]);
                }
            }
        }
    }

    private function convertToDMY($value)
    {
        try {
            // Ensure Carbon can handle the string, and return formatted date
            return Carbon::parse($value)->format('d/m/Y'); // or 'Y-m-d' based on DB expectations
        } catch (\Exception $e) {
            // Optional: log error for debugging
            // \log()::error("Date parse error for value: $value", ['exception' => $e]);
            return null;
        }
    }

    public function store(Request $request)
    {

             $request->merge([
            'aadhaar' => preg_replace('/\D/', '', $request->aadhaar)
        ]);
        $isDraft = $request->input('form_action') === 'draft';
        $recordId = $request->input('record_id');
// dd($request->input('form_action'));
// exit;
        // Format date fields
        $this->formatDatesToDMY([
            // 'bank_validity',
            // 'cc_validity',
            // 'competency_certificate_validity',
            // 'previous_experience_lnumber_validity'
        ], $request);

        if ($isDraft) {
            // Draft mode: minimal required fields, rest nullable
            $rules = [
                'applicant_name' => 'required|string|max:255',
                'business_address' => 'required|string|max:500',
                'form_name' => 'required|string|max:255',
                'license_name' => 'required|string|max:255',
                'appl_type' => 'required',

                // Optional fields in draft mode
                'authorised_name_designation' => 'nullable|string|max:255',
                'authorised_name' => 'nullable|string|max:255',
                'authorised_designation' => 'nullable|string|max:255',
                'previous_contractor_license' => 'nullable|string|max:10',
                'previous_application_number' => 'nullable|string|max:50',
                'previous_application_validity' => 'nullable',
                'bank_address' => 'nullable|string|max:500',
                'bank_validity' => 'nullable|date',
                
                'previous_contractor_license_verify' => 'nullable|numeric',
                'bank_amount' => 'nullable|numeric|min:0',
                'criminal_offence' => 'nullable|string|in:yes,no',
                'consent_letter_enclose' => 'nullable|string|in:yes,no',
                'cc_holders_enclosed' => 'nullable|string|in:yes,no',
                'purchase_bill_enclose' => 'nullable|string|in:yes,no',
                'test_reports_enclose' => 'nullable|string|in:yes,no',
                'specimen_signature_enclose' => 'nullable|string|in:yes,no',
                'separate_sheet' => 'nullable|string|in:yes,no',
                'aadhaar' => 'nullable',
                'pancard' => 'nullable',
                'gst_number' => 'nullable',
                'declaration1' => 'nullable|string|max:255',
                'declaration2' => 'nullable|string|max:255',
                // 'aadhaar_doc' => 'nullable|file|max:2048',
                // 'pancard_doc' => 'nullable|file|max:2048',
                // 'gst_doc' => 'nullable|file|max:2048',
            ];
        } else {
            // Final submission: all required fields
            $rules = [
                'applicant_name' => 'required|string|max:255',
                'business_address' => 'required|string|max:500',
                'authorised_name_designation' => 'required',
                'authorised_name' => 'nullable|string|max:255',
                'authorised_designation' => 'nullable|string|max:255',
                'previous_contractor_license' => 'required|string|max:10',
                'previous_application_number' => 'nullable|string|max:50',
                'previous_application_validity' => 'nullable',
                'previous_contractor_license_verify' => 'nullable|numeric',
                'bank_address' => 'required|string|max:500',
                'bank_validity' => 'required|date',
                'bank_amount' => 'required|numeric|min:1',
                'criminal_offence' => ['required', 'string', Rule::in(['yes', 'no'])],
                'consent_letter_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'cc_holders_enclosed' => ['required', 'string', Rule::in(['yes', 'no'])],
                'purchase_bill_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'test_reports_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'specimen_signature_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'separate_sheet' => ['required', 'string', Rule::in(['yes', 'no'])],
                'form_name' => 'required|string|max:255',
                'license_name' => 'required|string|max:255',
                'aadhaar' => 'required|digits:12',
                'pancard' => 'required|alpha_num|size:10',
                'gst_number' => 'required|string|min:15',
                'declaration1' => 'required|string|max:255',
                'declaration2' => 'required|string|max:255',
                'aadhaar_doc' => $request->hasFile('aadhaar_doc') ? 'required|file|max:2048' : 'nullable|string',
                'pancard_doc' => $request->hasFile('pancard_doc') ? 'required|file|max:2048' : 'nullable|string',
                'gst_doc'     => $request->hasFile('gst_doc')     ? 'required|file|max:2048' : 'nullable|string',
            ];
        }
    // dd($request->all());
    // exit;
        $validatedData = $request->validate($rules);

        $validatedData['name_of_authorised_to_sign'] = !empty($request->name_of_authorised_to_sign)
            ? json_encode($request->name_of_authorised_to_sign)
            : null;

        // Convert to uppercase for certain fields
        foreach (
            [
                'applicant_name',
                'business_address',
                'authorised_name',
                'authorised_designation',
                'bank_address',
                'form_name',
                'license_name',
                'pancard',
                'gst_number'
            ] as $field
        ) {
            if (!empty($validatedData[$field])) {
                $validatedData[$field] = strtoupper($validatedData[$field]);
            }
        }

        // Encrypt sensitive fields only if they exist
        if (!empty($validatedData['aadhaar'])) {
            $validatedData['aadhaar'] = Crypt::encryptString($validatedData['aadhaar']);
        }
        if (!empty($validatedData['pancard'])) {
            $validatedData['pancard'] = Crypt::encryptString($validatedData['pancard']);
        }
        if (!empty($validatedData['gst_number'])) {
            $validatedData['gst_number'] = Crypt::encryptString($validatedData['gst_number']);
        }

        // Determine if record exists
        $applicationId = null;
        $existing = null;

        if ($recordId) {
            $existing = EA_Application_model::where('application_id', $recordId)->first();
            if ($existing) {
                $applicationId = $existing->application_id;
            }
        }
        if (!$applicationId) {
            $applicationId = $this->generateApplicationId(
                $request->appl_type !== 'N',
                $request->form_name,
                $request->license_name
            );
        }

        // Final data to save
        $dataToSave = $validatedData;
        $dataToSave['application_id'] = $applicationId;
        $dataToSave['login_id'] = $request->login_id_store;
        $dataToSave['payment_status'] = $isDraft ? 'draft' : 'pending';
        $dataToSave['application_status'] = 'P';
        // $dataToSave['created_at'] = now();
        $dataToSave['updated_at'] = now();

        // Upload documents
        $existingDoc = DB::table('tnelb_applicant_doc_A')
            ->where('application_id', $applicationId)
            ->first();

        $aadhaarFilename = $existingDoc->aadhaar_doc ?? null;
        $panFilename = $existingDoc->pancard_doc ?? null;
        $gstFilename = $existingDoc->gst_doc ?? null;

        // Aadhaar upload
        if ($request->hasFile('aadhaar_doc')) {
            $aadhaarPath = 'documents/' . time() . '.' . $request->file('aadhaar_doc')->getClientOriginalExtension();
            $request->file('aadhaar_doc')->move(public_path('documents'), basename($aadhaarPath));
            $aadhaarFilename = Crypt::encryptString($aadhaarPath);
        }

        // PAN upload
        if ($request->hasFile('pancard_doc')) {
            $panPath = 'documents/' . time() . '.' . $request->file('pancard_doc')->getClientOriginalExtension();
            $request->file('pancard_doc')->move(public_path('documents'), basename($panPath));
            $panFilename = Crypt::encryptString($panPath);
        }

        // GST upload
        if ($request->hasFile('gst_doc')) {
            $gstPath = 'documents/' . time() . '.' . $request->file('gst_doc')->getClientOriginalExtension();
            $request->file('gst_doc')->move(public_path('documents'), basename($gstPath));
            $gstFilename = Crypt::encryptString($gstPath);
        }

        // Insert or Update Document
        $documentExists = DB::table('tnelb_applicant_doc_A')
            ->where('application_id', $applicationId)
            ->exists();

        $documentData = [
            'login_id'       => $request->login_id_store,
            'application_id' => $applicationId,
            'aadhaar_doc'    => $aadhaarFilename,
            'pancard_doc'    => $panFilename,
            'gst_doc'        => $gstFilename,
            'updated_at'     => now(),
        ];

        if (!$existingDoc) {
            $documentData['created_at'] = now();
            DB::table('tnelb_applicant_doc_A')->insert($documentData);
        } else {
            DB::table('tnelb_applicant_doc_A')
                ->where('application_id', $applicationId)
                ->update($documentData);
        }
        // dd($request->all());
        // exit;



        if ($request->has('staff_name')) {
            $processedStaffIds = [];
            if ($request->appl_type === 'N') {
                $staffIdsFromForm = $request->staff_id ?? [];
                $existingStaffIds = TnelbApplicantStaffDetail::where('application_id', $applicationId)->pluck('id')->toArray();

                // $processedStaffIds = [];

                foreach ($request->staff_name as $index => $staffName) {
                    if (
                        !empty($staffName) ||
                        // !empty($request->staff_qualification[$index]) ||
                        !empty($request->cc_number[$index]) ||
                        !empty($request->cc_validity[$index]) ||
                        !empty($request->staff_category[$index])
                    ) {
                        $staffId = $staffIdsFromForm[$index] ?? null;
                        $validity = $request->cc_validity[$index] ?? null;

                        $staffData = [
                            'application_id'      => $applicationId,
                            'login_id'            => $request->login_id_store,
                            'staff_name'          => strtoupper($staffName),
                            'staff_qualification' => strtoupper($request->staff_qualification[$index] ?? ''),
                            'cc_number'           => strtoupper($request->cc_number[$index] ?? ''),
                            'cc_validity'         => $validity,
                            'staff_category'      => strtoupper($request->staff_category[$index] ?? ''),
                            'staff_cc_verify'     => $request->staff_cc_verify[$index]
                        ];

                        if ($staffId && in_array($staffId, $existingStaffIds)) {
                            $existingStaff = TnelbApplicantStaffDetail::find($staffId);

                            if (
                                strtoupper($existingStaff->staff_name) !== strtoupper($staffName) ||
                                strtoupper($existingStaff->staff_qualification) !== strtoupper($request->staff_qualification[$index] ?? '') ||
                                strtoupper($existingStaff->cc_number) !== strtoupper($request->cc_number[$index] ?? '') ||
                                $existingStaff->cc_validity !== $validity ||
                                strtoupper($existingStaff->staff_category) !== strtoupper($request->staff_category[$index] ?? '')
                            ) {
                                $existingStaff->update($staffData);
                            }

                            $processedStaffIds[] = $staffId;
                        } else {
                            // Create new entry
                            $newStaff = TnelbApplicantStaffDetail::create($staffData);
                            $processedStaffIds[] = $newStaff->id;
                        }
                    }
                }
            } elseif ($request->appl_type === 'R') {
                foreach ($request->staff_name as $index => $staffName) {
                    if (!empty($staffName) || !empty($request->cc_number[$index]) || !empty($request->cc_validity[$index]) || !empty($request->staff_category[$index])) {

                        $validity = $request->cc_validity[$index] ?? null;

                        $staffData = [
                            'application_id'      => $applicationId,
                            'login_id'            => $request->login_id_store,
                            'staff_name'          => strtoupper($staffName),
                            'staff_qualification' => strtoupper($request->staff_qualification[$index] ?? ''),
                            'cc_number'           => strtoupper($request->cc_number[$index] ?? ''),
                            'cc_validity'         => $validity,
                            'staff_category'      => strtoupper($request->staff_category[$index] ?? ''),
                            'staff_cc_verify'     => $request->staff_cc_verify[$index] ?? null
                        ];

                        TnelbApplicantStaffDetail::create($staffData);
                    }
                }
            }


            // Remove deleted staff
            TnelbApplicantStaffDetail::where('application_id', $applicationId)
                ->whereNotIn('id', $processedStaffIds)
                ->delete();
        }

        // Update only staff_cc_verify values by staff_id (if they exist)
        if ($request->has('staff_cc_verify') && $request->has('staff_id')) {
            foreach ($request->staff_cc_verify as $index => $verifyValue) {
                $staffId = $request->staff_id[$index] ?? null;

                if ($staffId) {
                    TnelbApplicantStaffDetail::where('id', $staffId)->update([
                        'staff_cc_verify' => $verifyValue
                    ]);
                }
            }
        }

        //    dd($request->all());
        // exit;

        $newProprietorIds = [];
        if ($request->has('proprietor_name')) {

            if ($request->appl_type === 'N') {
                foreach ($request->proprietor_name as $index => $proprietor_name) {
                    $competencyHolding = data_get($request->competency_certificate_holding, $index);
                    $presently_employed = data_get($request->presently_employed, $index);
                    $previous_experience = data_get($request->previous_experience, $index);
                    // Skip if no name (avoid empty row)
                    if (empty(trim($proprietor_name))) {
                        continue;
                    }

                    $proprietorId = $request->proprietor_id[$index] ?? null;

                    $data = [
                        'login_id' => $request->login_id_store,
                        'application_id' => $applicationId,
                        'proprietor_name' => strtoupper($proprietor_name ?? ''),
                        'proprietor_address' => strtoupper(data_get($request->proprietor_address, $index, '')),
                        'age' => data_get($request->age, $index),
                        'qualification' => strtoupper(data_get($request->qualification, $index, '')),
                        'fathers_name' => strtoupper(data_get($request->fathers_name, $index, '')),
                        'present_business' => strtoupper(data_get($request->present_business, $index, '')),
                        'competency_certificate_holding' => $competencyHolding,
                        'competency_certificate_number' => $competencyHolding === 'yes' ? strtoupper(data_get($request->competency_certificate_number, $index)) : null,
                        'competency_certificate_validity' => $competencyHolding === 'yes' ? data_get($request->competency_certificate_validity, $index) : null,
                        'proprietor_cc_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_cc_verify, $index) : null,
                        'presently_employed' => $presently_employed,
                        'presently_employed_name' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_name, $index)) : null,
                        'presently_employed_address' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_address, $index)) : null,
                        'previous_experience' => $previous_experience,
                        'previous_experience_name' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_name, $index)) : null,
                        'previous_experience_address' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_address, $index)) : null,
                        'previous_experience_lnumber' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_lnumber, $index)) : null,
                        'previous_experience_lnumber_validity' => data_get($request->previous_experience, $index) === 'yes' ? data_get($request->previous_experience_lnumber_validity, $index) : null,
                        'proprietor_contractor_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_contractor_verify, $index) : null,
                        'proprietor_flag' => 1,
                    ];

                    if ($proprietorId) {
                        // Update existing record
                        ProprietorformA::where('id', $proprietorId)->update($data);
                        $newProprietorIds[] = $proprietorId;
                    } else {
                        // Insert new record
                        $new = ProprietorformA::create($data);
                        $newProprietorIds[] = $new->id;
                    }
                }
            } elseif ($request->appl_type === 'R') {

                foreach ($request->proprietor_name as $index => $proprietor_name) {
                    $competencyHolding = data_get($request->competency_certificate_holding, $index);
                    $presently_employed = data_get($request->presently_employed, $index);
                    $previous_experience = data_get($request->previous_experience, $index);
                    // Skip if no name (avoid empty row)
                    if (empty(trim($proprietor_name))) {
                        continue;
                    }

                    $proprietorId = $request->proprietor_id[$index] ?? null;

                    $data = [
                        'login_id' => $request->login_id_store,
                        'application_id' => $applicationId,
                        'proprietor_name' => strtoupper($proprietor_name ?? ''),
                        'proprietor_address' => strtoupper(data_get($request->proprietor_address, $index, '')),
                        'age' => data_get($request->age, $index),
                        'qualification' => strtoupper(data_get($request->qualification, $index, '')),
                        'fathers_name' => strtoupper(data_get($request->fathers_name, $index, '')),
                        'present_business' => strtoupper(data_get($request->present_business, $index, '')),
                        'competency_certificate_holding' => $competencyHolding,
                        'competency_certificate_number' => $competencyHolding === 'yes' ? strtoupper(data_get($request->competency_certificate_number, $index)) : null,
                        'competency_certificate_validity' => $competencyHolding === 'yes' ? data_get($request->competency_certificate_validity, $index) : null,
                        // 'proprietor_cc_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_cc_verify, $index) : null,
                        'presently_employed' => $presently_employed,
                        'presently_employed_name' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_name, $index)) : null,
                        'presently_employed_address' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_address, $index)) : null,
                        'previous_experience' => $previous_experience,
                        'previous_experience_name' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_name, $index)) : null,
                        'previous_experience_address' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_address, $index)) : null,
                        'previous_experience_lnumber' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_lnumber, $index)) : null,
                        'previous_experience_lnumber_validity' => data_get($request->previous_experience, $index) === 'yes' ? data_get($request->previous_experience_lnumber_validity, $index) : null,
                        // 'proprietor_contractor_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_contractor_verify, $index) : null,
                        'proprietor_flag' => 1,
                    ];

                    if ($proprietorId) {
                        // Fetch existing record
                        $existing = ProprietorformA::find($proprietorId);

                        // If cc_verify is 1 and request is also 1, don't overwrite
                        if ($existing->proprietor_cc_verify == 1 && data_get($request->proprietor_cc_verify, $index) == 1) {
                            $data['proprietor_cc_verify'] = $existing->proprietor_cc_verify;
                        }

                        // If contractor_verify is 1 and request is also 1, don't overwrite
                        if ($existing->proprietor_contractor_verify == 1 && data_get($request->proprietor_contractor_verify, $index) == 1) {
                            $data['proprietor_contractor_verify'] = $existing->proprietor_contractor_verify;
                        }

                        // Update
                        $existing->update($data);
                        $newProprietorIds[] = $proprietorId;
                        // ProprietorformA::where('id', $proprietorId)->update($data);
                        $newProprietorIds[] = $proprietorId;
                    } else {
                        // Insert new record
                        $new = ProprietorformA::create($data);
                        $newProprietorIds[] = $new->id;
                    }
                }
            }

            // 🧹 Deactivate removed rows (not in current request)
            ProprietorformA::where('application_id', $applicationId)
                ->whereNotIn('id', $newProprietorIds)
                ->update(['proprietor_flag' => 0]);
        }



        if ($existing) {

            $updateData = collect($dataToSave)
                ->except(['aadhaar_doc', 'pancard_doc', 'gst_doc'])
                ->toArray();

            EA_Application_model::where('application_id', $existing->application_id)
                ->update($updateData);
        } else {
            $dataToSave['created_at'] = now();
            $createData = collect($dataToSave)
                ->except(['aadhaar_doc', 'pancard_doc', 'gst_doc'])
                ->toArray();

            EA_Application_model::create($createData);
            $message = $isDraft ? 'Draft saved successfully!' : 'Application submitted successfully!';
        }
        $transactionId = 'TXN' . rand(100000, 999999);

        $payment = $isDraft ? 'draft' : 'success';

        if (!$isDraft) {


         
            $form = \DB::table('tnelb_forms')
                ->where('form_name', $request->form_name)
                ->where('status', '1')
                ->first();

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found or inactive.'
                ]);
            }

            // dd($form->license_name);
            // exit;
           Payment::create([
                    'login_id'       => $request->login_id_store,
                    'application_id' => $applicationId,
                    'transaction_id' => $transactionId,
                    'payment_status' => $payment,
                    'amount'         => $form->fresh_amount, //  tnelb_forms
                    'form_name'      => $request->form_name,
                    'license_name'   => $request->license_name,
                ]);

            mst_workflow::create([
                'login_id' => $request->login_id_store,
                'application_id' => $applicationId,
                'transaction_id' => $transactionId,
                'payment_status' => $payment,
                'formname_appliedfor' => $request->form_name,
                'license_name' => $request->license_name,
            ]);

            return response()->json([
                'draft_status' => $isDraft,
                'message' => 'Payment Processed!',
                'login_id' => $applicationId,
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'message' => 'Draft',
            'login_id' => $applicationId,
            'transaction_id' => $isDraft ? 'DRAFT' . rand(100000, 999999) : 'TXN' . rand(100000, 999999),
            'draft_status' => $isDraft
        ]);
    }

    // ------------application id-----------------------
    private function generateApplicationId($isRenewal, $formName, $licenseName)
    {
        $model = $isRenewal ? EA_Application_model::class : EA_Application_model::class;

        $prefix = $isRenewal ? 'R' : '';
        $year = date('y');

        // Get last application for this specific prefix & year
        $lastApplication = $model::where('application_id', 'LIKE', $prefix . $formName . $licenseName . $year . '%')
            ->latest('id')
            ->value('application_id');

        $nextNumber = '000001';

        if ($lastApplication && preg_match('/(\d{6})$/', $lastApplication, $matches)) {
            $lastNumber = (int) $matches[1];
            $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        }

        return strtoupper($prefix . $formName . $licenseName . $year . $nextNumber);
    }




    // ------------------------renewal-----------------------------------


    public function storerenewal(Request $request)
    {
  $request->merge([
            'aadhaar' => preg_replace('/\D/', '', $request->aadhaar)
        ]);
        $isDraft = $request->input('form_action') === 'draft';
        $recordId = $request->input('record_id');

        // Format date fields
        $this->formatDatesToDMY([
            // 'bank_validity',
            // 'cc_validity',
            // 'competency_certificate_validity',
            // 'previous_experience_lnumber_validity'
        ], $request);

        if ($isDraft) {
            // Draft mode: minimal required fields, rest nullable
            $rules = [
                'applicant_name' => 'required|string|max:255',
                'business_address' => 'required|string|max:500',
                'form_name' => 'required|string|max:255',
                'license_name' => 'required|string|max:255',
                'appl_type' => 'required',

                // Optional fields in draft mode
                'authorised_name_designation' => 'nullable|string|max:255',
                'authorised_name' => 'nullable|string|max:255',
                'authorised_designation' => 'nullable|string|max:255',
                'previous_contractor_license' => 'nullable|string|max:10',
                'previous_application_number' => 'nullable|string|max:50',
                'previous_application_validity' => 'nullable',
                 'previous_contractor_license_verify' => 'nullable|numeric',
                'bank_address' => 'nullable|string|max:500',
                'bank_validity' => 'nullable|date',
                'bank_amount' => 'nullable|numeric|min:0',
                'criminal_offence' => 'nullable|string|in:yes,no',
                'consent_letter_enclose' => 'nullable|string|in:yes,no',
                'cc_holders_enclosed' => 'nullable|string|in:yes,no',
                'purchase_bill_enclose' => 'nullable|string|in:yes,no',
                'test_reports_enclose' => 'nullable|string|in:yes,no',
                'specimen_signature_enclose' => 'nullable|string|in:yes,no',
                'separate_sheet' => 'nullable|string|in:yes,no',
                'aadhaar' => 'nullable',
                'pancard' => 'nullable',
                'gst_number' => 'nullable',
                // 'declaration1' => 'nullable|string|max:255',
                // 'declaration2' => 'nullable|string|max:255',
                // 'aadhaar_doc' => 'nullable|file|max:2048',
                // 'pancard_doc' => 'nullable|file|max:2048',
                // 'gst_doc' => 'nullable|file|max:2048',
            ];
        } else {

            $rules = [
                'applicant_name' => 'required|string|max:255',
                'business_address' => 'required|string|max:500',
                'authorised_name_designation' => 'required',
                'authorised_name' => 'nullable|string|max:255',
                'authorised_designation' => 'nullable|string|max:255',
                'previous_contractor_license' => 'required|string|max:10',
                'previous_application_number' => 'nullable|string|max:50',
                'previous_application_validity' => 'nullable',
                 'previous_contractor_license_verify' => 'nullable|numeric',
                'bank_address' => 'required|string|max:500',
                'bank_validity' => 'required|date',
                'bank_amount' => 'required|numeric|min:1',
                'criminal_offence' => ['required', 'string', Rule::in(['yes', 'no'])],
                'consent_letter_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'cc_holders_enclosed' => ['required', 'string', Rule::in(['yes', 'no'])],
                'purchase_bill_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'test_reports_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'specimen_signature_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
                'separate_sheet' => ['required', 'string', Rule::in(['yes', 'no'])],
                'form_name' => 'required|string|max:255',
                'license_name' => 'required|string|max:255',
                'aadhaar' => 'required|digits:12',
                'pancard' => 'required|alpha_num|size:10',
                'gst_number' => 'required|string|min:15',
                'declaration1' => 'required|string|max:255',
                'declaration2' => 'required|string|max:255',
                'aadhaar_doc' => $request->hasFile('aadhaar_doc') ? 'required|file|max:2048' : 'nullable|string',
                'pancard_doc' => $request->hasFile('pancard_doc') ? 'required|file|max:2048' : 'nullable|string',
                'gst_doc'     => $request->hasFile('gst_doc')     ? 'required|file|max:2048' : 'nullable|string',

            ];
        }

        $validatedData = $request->validate($rules);

        $validatedData['name_of_authorised_to_sign'] = !empty($request->name_of_authorised_to_sign)
            ? json_encode($request->name_of_authorised_to_sign)
            : null;

        // Convert to uppercase for certain fields
        foreach (
            [
                'applicant_name',
                'business_address',
                'authorised_name',
                'authorised_designation',
                'bank_address',
                'form_name',
                'license_name',
                'appl_type',
                'pancard',
                'gst_number'
            ] as $field
        ) {
            if (!empty($validatedData[$field])) {
                $validatedData[$field] = strtoupper($validatedData[$field]);
            }
        }

        // Encrypt sensitive fields only if they exist
        if (!empty($validatedData['aadhaar'])) {
            $validatedData['aadhaar'] = Crypt::encryptString($validatedData['aadhaar']);
        }
        if (!empty($validatedData['pancard'])) {
            $validatedData['pancard'] = Crypt::encryptString($validatedData['pancard']);
        }
        if (!empty($validatedData['gst_number'])) {
            $validatedData['gst_number'] = Crypt::encryptString($validatedData['gst_number']);
        }

        // Determine if record exists
        $applicationId = null;

        if (!$applicationId) {
            $applicationId = $this->generateApplicationId(
                $request->appl_type !== 'N',
                $request->form_name,
                $request->license_name
            );
        }


        // Final data to save
        $dataToSave = $validatedData;
        $dataToSave['application_id'] = $applicationId;
        $dataToSave['login_id'] = $request->login_id_store;
        $dataToSave['payment_status'] = $isDraft ? 'draft' : 'pending';
        $dataToSave['application_status'] = 'P';
        $dataToSave['created_at'] = now();
        $dataToSave['updated_at'] = now();
        $dataToSave['updated_at'] = now();
        $dataToSave['appl_type'] = $request->appl_type;


        // Determine if record exists

        $existing = null;
        if ($recordId) {

            // Search by application_id, not numeric id
            $existing = EA_Application_model::where('application_id', $recordId)->first();


            if ($existing) {
                if (!str_starts_with($existing->application_id, 'R')) {
                    $applicationId = $this->generateApplicationId(
                        $request->appl_type !== 'N',
                        $request->form_name,
                        $request->license_name
                    );
                } else {
                    $applicationId = $existing->application_id;
                }
            }
        }

        if (!$applicationId) {
            $applicationId = $this->generateApplicationId(
                $request->appl_type !== 'N',
                $request->form_name,
                $request->license_name
            );
        }

        $dataToSave['application_id'] = $applicationId;
// dd($existing);
// exit;


        if ($existing) {
            // AEA
            if (preg_match('/^EAA/i', trim($existing->application_id))) {
                // Generate a new RAEA ID
                $newApplicationId = $this->generateApplicationId(
                    $request->appl_type !== 'N',
                    $request->form_name,
                    $request->license_name,
                    'RAEA'
                );

                // Use validated form data to avoid missing fields
                $formData = collect($validatedData)
                    ->except(['aadhaar_doc', 'pancard_doc', 'gst_doc'])
                    ->toArray();

                $formData['application_id'] = $newApplicationId;
                $formData['login_id'] = $request->login_id_store;
                $formData['payment_status'] = $isDraft ? 'draft' : 'pending';
                $formData['application_status'] = 'P';

                $formData['old_application'] = $recordId;

                $formData['license_number'] = $request->license_number;
                // $formData['created_at'] = now();
                $formData['updated_at'] = now();

                $formData['appl_type'] = $request->appl_type;

                // dd($formData['appl_type']);
                // exit;

                // Insert as new record
                EA_Application_model::create($formData);

                $message = $isDraft
                    ? 'Draft saved successfully with new RAEA ID!'
                    : 'Application submitted successfully with new RAEA ID!';
            } else {
                // Normal update
                $updateData = collect($dataToSave)
                    ->except(['aadhaar_doc', 'pancard_doc', 'gst_doc'])
                    ->toArray();

                EA_Application_model::where('application_id', $existing->application_id)
                    ->update($updateData);

                $message = $isDraft
                    ? 'Draft updated successfully!'
                    : 'Application updated successfully!';
            }
        } else {
            //    $dataToSave['old_application'] = $recordId ?? null;
            $dataToSave['created_at'] = now();


            EA_Application_model::create($dataToSave);

            $message = $isDraft
                ? 'Draft saved successfully!'
                : 'Application submitted successfully!';
        }




        $transactionId = 'TXN' . rand(100000, 999999);

        $payment = $isDraft ? 'draft' : 'success';



        // dd($applicationId);
        // exit;
        // Upload documents
        // Choose lookup id: if you're cloning old record use $recordId (old AEA), else use $applicationId
        $docLookupId = $recordId ?? $applicationId;

        $existingDoc = DB::table('tnelb_applicant_doc_A')
            ->where('application_id', $docLookupId)
            ->first();

        // Start with existing encrypted DB values (may be null)
        $aadhaarFilename = $existingDoc->aadhaar_doc ?? null;
        $panFilename    = $existingDoc->pancard_doc  ?? null;
        $gstFilename    = $existingDoc->gst_doc      ?? null;

        // Try to decrypt existing paths (for safe comparison)
        $existingDecryptedAadhaar = null;
        $existingDecryptedPan     = null;
        $existingDecryptedGst     = null;

        if (!empty($existingDoc->aadhaar_doc)) {
            try {
                $existingDecryptedAadhaar = Crypt::decryptString($existingDoc->aadhaar_doc);
            } catch (\Exception $e) {
                $existingDecryptedAadhaar = null;
            }
        }
        if (!empty($existingDoc->pancard_doc)) {
            try {
                $existingDecryptedPan = Crypt::decryptString($existingDoc->pancard_doc);
            } catch (\Exception $e) {
                $existingDecryptedPan = null;
            }
        }
        if (!empty($existingDoc->gst_doc)) {
            try {
                $existingDecryptedGst = Crypt::decryptString($existingDoc->gst_doc);
            } catch (\Exception $e) {
                $existingDecryptedGst = null;
            }
        }

        // ---------- Aadhaar ----------
        if ($request->hasFile('aadhaar_doc')) {
            $aadhaarPath = 'documents/' . time() . '.' . $request->file('aadhaar_doc')->getClientOriginalExtension();
            $request->file('aadhaar_doc')->move(public_path('documents'), basename($aadhaarPath));
            $aadhaarFilename = Crypt::encryptString($aadhaarPath);
        } elseif ($request->filled('aadhaar_doc')) {
            // user submitted a hidden input (string path) — keep or encrypt appropriately
            $postedAadhaar = $request->input('aadhaar_doc');

            if ($existingDecryptedAadhaar && $postedAadhaar === $existingDecryptedAadhaar) {
                // keep existing encrypted value (already in $aadhaarFilename)
            } else {
                // encrypt the posted path before saving
                $aadhaarFilename = Crypt::encryptString($postedAadhaar);
            }
        } else {
            // no file and no hidden input => user removed the file
            $aadhaarFilename = null;
        }

        // ---------- PAN ----------
        if ($request->hasFile('pancard_doc')) {
            $panPath = 'documents/' . time() . '.' . $request->file('pancard_doc')->getClientOriginalExtension();
            $request->file('pancard_doc')->move(public_path('documents'), basename($panPath));
            $panFilename = Crypt::encryptString($panPath);
        } elseif ($request->filled('pancard_doc')) {
            $postedPan = $request->input('pancard_doc');

            if ($existingDecryptedPan && $postedPan === $existingDecryptedPan) {
                // keep existing encrypted
            } else {
                $panFilename = Crypt::encryptString($postedPan);
            }
        } else {
            $panFilename = null;
        }

        // ---------- GST ----------
        if ($request->hasFile('gst_doc')) {
            $gstPath = 'documents/' . time() . '.' . $request->file('gst_doc')->getClientOriginalExtension();
            $request->file('gst_doc')->move(public_path('documents'), basename($gstPath));
            $gstFilename = Crypt::encryptString($gstPath);
        } elseif ($request->filled('gst_doc')) {
            $postedGst = $request->input('gst_doc');

            if ($existingDecryptedGst && $postedGst === $existingDecryptedGst) {
                // keep existing encrypted
            } else {
                $gstFilename = Crypt::encryptString($postedGst);
            }
        } else {
            $gstFilename = null;
        }

        // Build document payload
        $documentData = [
            'login_id'       => $request->login_id_store,
            'application_id' => $applicationId,
            'aadhaar_doc'    => $aadhaarFilename,
            'pancard_doc'    => $panFilename,
            'gst_doc'        => $gstFilename,
            'updated_at'     => now(),
        ];
        // dd($documentData);
        // // dd($aadhaarFilename, $panFilename, $gstFilename);
        // exit;

        // Insert or update
        if (DB::table('tnelb_applicant_doc_A')->where('application_id', $applicationId)->exists()) {
            DB::table('tnelb_applicant_doc_A')
                ->where('application_id', $applicationId)
                ->update($documentData);
        } else {
            $documentData['created_at'] = now();
            DB::table('tnelb_applicant_doc_A')->insert($documentData);
        }

        // dd($request->all());
        // exit;

        $processedStaffIds = [];

        if ($request->has('staff_name')) {


            $staffIdsFromForm = $request->staff_id ?? [];
            $existingStaffIds = TnelbApplicantStaffDetail::where('application_id', $applicationId)->pluck('id')->toArray();

            // $processedStaffIds = [];

            foreach ($request->staff_name as $index => $staffName) {
                if (
                    !empty($staffName) ||
                    // !empty($request->staff_qualification[$index]) ||
                    !empty($request->cc_number[$index]) ||
                    !empty($request->cc_validity[$index]) ||
                    !empty($request->staff_category[$index])
                ) {
                    $staffId = $staffIdsFromForm[$index] ?? null;
                    $validity = $request->cc_validity[$index] ?? null;

                    $staffData = [
                        'application_id'      => $applicationId,
                        'login_id'            => $request->login_id_store,
                        'staff_name'          => strtoupper($staffName),
                        'staff_qualification' => strtoupper($request->staff_qualification[$index] ?? ''),
                        'cc_number'           => strtoupper($request->cc_number[$index] ?? ''),
                        'cc_validity'         => $validity,
                        'staff_category'      => strtoupper($request->staff_category[$index] ?? ''),
                        'staff_cc_verify'     => $request->staff_cc_verify[$index]
                    ];

                    if ($staffId && in_array($staffId, $existingStaffIds)) {
                        $existingStaff = TnelbApplicantStaffDetail::find($staffId);

                        if (
                            strtoupper($existingStaff->staff_name) !== strtoupper($staffName) ||
                            strtoupper($existingStaff->staff_qualification) !== strtoupper($request->staff_qualification[$index] ?? '') ||
                            strtoupper($existingStaff->cc_number) !== strtoupper($request->cc_number[$index] ?? '') ||
                            $existingStaff->cc_validity !== $validity ||
                            strtoupper($existingStaff->staff_category) !== strtoupper($request->staff_category[$index] ?? '')
                        ) {
                            $existingStaff->update($staffData);
                        }

                        $processedStaffIds[] = $staffId;
                    } else {
                        // Create new entry
                        $newStaff = TnelbApplicantStaffDetail::create($staffData);
                        $processedStaffIds[] = $newStaff->id;
                    }
                }
            }



            // Remove deleted staff
            TnelbApplicantStaffDetail::where('application_id', $applicationId)
                ->whereNotIn('id', $processedStaffIds)
                ->delete();
        }

        // Update only staff_cc_verify values by staff_id (if they exist)
        if ($request->has('staff_cc_verify') && $request->has('staff_id')) {
            foreach ($request->staff_cc_verify as $index => $verifyValue) {
                $staffId = $request->staff_id[$index] ?? null;

                if ($staffId) {
                    TnelbApplicantStaffDetail::where('id', $staffId)->update([
                        'staff_cc_verify' => $verifyValue
                    ]);
                }
            }
        }

        //    dd($request->all());
        // exit;

        $newProprietorIds = [];
        if ($request->has('proprietor_name')) {


            // dd($request->all());
            // exit;


            foreach ($request->proprietor_name as $index => $proprietor_name) {
                $competencyHolding = data_get($request->competency_certificate_holding, $index);
                $presently_employed = data_get($request->presently_employed, $index);
                $previous_experience = data_get($request->previous_experience, $index);
                // Skip if no name (avoid empty row)
                if (empty(trim($proprietor_name))) {
                    continue;
                }

                $proprietorId = $request->proprietor_id[$index] ?? null;

                $data = [
                    'login_id' => $request->login_id_store,
                    'application_id' => $applicationId,
                    'proprietor_name' => strtoupper($proprietor_name ?? ''),
                    'proprietor_address' => strtoupper(data_get($request->proprietor_address, $index, '')),
                    'age' => data_get($request->age, $index),
                    'qualification' => strtoupper(data_get($request->qualification, $index, '')),
                    'fathers_name' => strtoupper(data_get($request->fathers_name, $index, '')),
                    'present_business' => strtoupper(data_get($request->present_business, $index, '')),
                    'competency_certificate_holding' => $competencyHolding,
                    'competency_certificate_number' => $competencyHolding === 'yes' ? strtoupper(data_get($request->competency_certificate_number, $index)) : null,
                    'competency_certificate_validity' => $competencyHolding === 'yes' ? data_get($request->competency_certificate_validity, $index) : null,
                    'proprietor_cc_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_cc_verify, $index) : null,
                    'presently_employed' => $presently_employed,
                    'presently_employed_name' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_name, $index)) : null,
                    'presently_employed_address' => data_get($request->presently_employed, $index) === 'yes' ? strtoupper(data_get($request->presently_employed_address, $index)) : null,
                    'previous_experience' => $previous_experience,
                    'previous_experience_name' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_name, $index)) : null,
                    'previous_experience_address' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_address, $index)) : null,
                    'previous_experience_lnumber' => data_get($request->previous_experience, $index) === 'yes' ? strtoupper(data_get($request->previous_experience_lnumber, $index)) : null,
                    'previous_experience_lnumber_validity' => data_get($request->previous_experience, $index) === 'yes' ? data_get($request->previous_experience_lnumber_validity, $index) : null,
                    'proprietor_contractor_verify' => $competencyHolding === 'yes' ? data_get($request->proprietor_contractor_verify, $index) : null,
                    'proprietor_flag' => 1,
                ];

                if ($proprietorId && is_numeric($proprietorId) && str_starts_with($proprietorId, 'R')) {
                    // Update numeric IDs only
                   
                    ProprietorformA::where('id', $proprietorId)->update($data);
                    $newProprietorIds[] = $proprietorId;


                } elseif ($proprietorId && str_starts_with($proprietorId, 'R')) {
                  
                    ProprietorformA::where('id', ltrim($proprietorId, 'R'))->update($data);
                    $newProprietorIds[] = ltrim($proprietorId, 'R');
                } elseif ($proprietorId && !is_numeric($proprietorId) && !str_starts_with($proprietorId, 'R')) {
                  
                    // Non-numeric, non-R IDs like AEA250000066 → create new R-prefixed record
                    $new = ProprietorformA::create($data);
                    $newProprietorIds[] = 'R' . $new->id;
                } else {
                   
                    // Fresh entries (no ID at all)
                    $new = ProprietorformA::create($data);
                    $newProprietorIds[] = $new->id;
                }
            }


            // 🧹 Deactivate removed rows (not in current request)
            if ($request->has('record_id') && !empty($request->record_id)) {
                ProprietorformA::where('application_id', $applicationId)
                    ->whereNotIn('id', $newProprietorIds)
                    ->update(['proprietor_flag' => 0]);
            }
        }





        if (!$isDraft) {


            $form = \DB::table('tnelb_forms')
                ->where('form_name', $request->form_name)
                ->where('status', '1')
                ->first();

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found or inactive.'
                ]);
            }
           Payment::create([
                    'login_id'       => $request->login_id_store,
                    'application_id' => $applicationId,
                    'transaction_id' => $transactionId,
                    'payment_status' => $payment,
                    'amount'         => $form->renewal_amount, //  tnelb_forms
                    'form_name'      => $request->form_name,
                    'license_name'   => $request->license_name,
                ]);


            mst_workflow::create([
                'login_id' => $request->login_id_store,
                'application_id' => $applicationId,
                'transaction_id' => $transactionId,
                'payment_status' => $payment,
                'formname_appliedfor' => $request->form_name,
                'license_name' => $request->license_name,
            ]);

            return response()->json([
                'draft_status' => $isDraft,
                'message' => 'Payment Processed!',
                'login_id' => $applicationId,
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'message' => $message,
            'login_id' => $applicationId,
            'transaction_id' => $isDraft ? 'DRAFT' . rand(100000, 999999) : 'TXN' . rand(100000, 999999),
            'draft_status' => $isDraft
        ]);
    }

    // -----------instructions--------------------

public function getFormInstructions(Request $request)
{
    $formName  = $request->get('form_name');
    $appl_type = $request->get('appl_type');

    $form = \DB::table('tnelb_forms')
        ->where('form_name', $formName)
        ->where('status','1')
        ->first();

    if (!$form) {
        return response()->json([
            'instructions' => null,
            'fees'         => null
        ], 404);
    }

    if($appl_type === 'R'){
        $instructions = $form->instructions;
        $fees = $form->renewal_amount;
    }else{
        $instructions = $form->instructions;
        $fees = $form->fresh_amount;
    }

    return response()->json([
        'instructions' => $instructions,
        'fees'         => $fees
    ]);
}




    // -----------------A latest ---------end-------------

    public function store_bk(Request $request)
    {
        $isDraft = $request->input('form_action') === 'draft';
        $request->merge([
            'aadhaar' => preg_replace('/\D/', '', $request->aadhaar)
        ]);
        // ✅ Validation Rules
        $rules = [
            'applicant_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:500',
            'authorised_name_designation' => 'required',
            'authorised_name' => 'nullable|string|max:255',
            'authorised_designation' => 'nullable|string|max:255',
            'previous_contractor_license' => 'required|string|max:10',
            'previous_application_number' => 'nullable|string|max:50',
            'previous_application_validity' => 'nullable',
            'bank_address' => 'required|string|max:500',
            'bank_validity' => 'required|date',
            'bank_amount' => 'required|numeric|min:1',
            'criminal_offence' => ['required', 'string', Rule::in(['yes', 'no'])],
            'consent_letter_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
            'cc_holders_enclosed' => ['required', 'string', Rule::in(['yes', 'no'])],
            'purchase_bill_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
            'test_reports_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
            'specimen_signature_enclose' => ['required', 'string', Rule::in(['yes', 'no'])],
            'separate_sheet' => ['required', 'string', Rule::in(['yes', 'no'])],
            'form_name' => 'required|string|max:255',
            'license_name' => 'required|string|max:255',
            'aadhaar' => 'required|digits:12',
            'pancard' => 'required|alpha_num|size:10',
            'gst_number' => 'required|string|min:15',
            'declaration1' => 'required|string|max:255',
            'declaration2' => 'required|string|max:255',

            // ✅ Proprietor Validation Rules

            // 'proprietor_name' => 'required|string|max:255',
            // 'proprietor_address' => 'nullable|string|max:500',
            // 'age' => 'nullable|integer|min:18|max:100',
            // 'qualification' => 'nullable|string|max:255',
            // 'fathers_name' => 'nullable|string|max:255',
            // 'present_business' => 'nullable|string|max:500',

            // 'competency_certificate_holding' => ['required', Rule::in(['Y', 'N'])],
            // 'competency_certificate_number' => 'nullable|string|max:50',
            // 'competency_certificate_validity' => 'nullable|date',

            // 'presently_employed' => ['required', Rule::in(['Y', 'N'])],
            // 'presently_employed_name' => 'nullable|string|max:255',
            // 'presently_employed_address' => 'nullable|string|max:500',

            // 'previous_experience' => ['required', Rule::in(['Y', 'N'])],
            // 'previous_experience_name' => 'nullable|string|max:255',
            // 'previous_experience_address' => 'nullable|string|max:500',
            // 'previous_experience_lnumber' => 'nullable|string|max:50',
        ];

        // $rules += [
        //     'proprietor_name' => ['required', 'array', 'min:1'],  
        //     // 'proprietor_name.*' => ['required', 'string', 'max:255'],

        //     'proprietor_address' => ['required', 'array', 'min:1'],  
        //     'proprietor_address.*' => ['required', 'string', 'max:500'],

        //     'age' => ['required', 'array', 'min:1'],
        //     'age.*' => ['required', 'integer', 'min:18', 'max:100'],  

        //     'qualification' => ['required', 'array', 'min:1'],
        //     'qualification.*' => ['required', 'string', 'max:255'],  

        //     'fathers_name' => ['required', 'array', 'min:1'],
        //     'fathers_name.*' => ['required', 'string', 'max:255'],

        //     'present_business' => ['nullable', 'array'],
        //     'present_business.*' => ['nullable', 'string', 'max:255'],

        //     'competency_certificate_holding' => ['required', 'array'],
        //     'competency_certificate_holding.*' => ['required', 'in:yes,no'],  

        //     'competency_certificate_number' => ['nullable', 'array'],
        //     'competency_certificate_number.*' => ['nullable', 'string', 'max:255'],

        //     'competency_certificate_validity' => ['nullable', 'array'],
        //     'competency_certificate_validity.*' => ['nullable', 'date'],

        //     'presently_employed' => ['required', 'array'],
        //     'presently_employed.*' => ['required', 'in:yes,no'],  

        //     'presently_employed_name' => ['nullable', 'array'],
        //     'presently_employed_name.*' => ['nullable', 'string', 'max:255'],

        //     'presently_employed_address' => ['nullable', 'array'],
        //     'presently_employed_address.*' => ['nullable', 'string', 'max:500'],

        //     'previous_experience' => ['required', 'array'],
        //     'previous_experience.*' => ['required', 'in:yes,no'],

        //     'previous_experience_name' => ['nullable', 'array'],
        //     'previous_experience_name.*' => ['nullable', 'string', 'max:255'],

        //     'previous_experience_address' => ['nullable', 'array'],
        //     'previous_experience_address.*' => ['nullable', 'string', 'max:500'],

        //     'previous_experience_lnumber' => ['nullable', 'array'],
        //     'previous_experience_lnumber.*' => ['nullable', 'string', 'max:100'],
        // ];
        // $rules += [
        //     'staff_name' => 'required|string|max:255',
        //     'staff_qualification' => 'nullable|string|max:255',
        //     'cc_number' => 'nullable|string|max:50',
        //     'cc_validity' => 'nullable|date',
        // ];    

        // ✅ Relax validation for Draft
        if ($isDraft) {
            foreach ($rules as $key => $rule) {
                $rules[$key] = str_replace('required', 'nullable', $rule);
            }
        }


        $validatedData = $request->validate($rules);

        $lastApplication = EA_Application_model::latest('id')->value('application_id');

        $nextNumber = '0000001';

        if ($lastApplication && preg_match('/(\d{7})$/', $lastApplication, $matches)) {
            $lastNumber = (int) $matches[1];
            $nextNumber = str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT);
        }


        $newApplicationId = $request->form_name . $request->license_name . date('y') . $nextNumber;




        $aadhaarFilename = null;
        if ($request->hasFile('aadhaar_doc')) {
            $aadhaarPath = 'documents/aadhaar_' . time() . '.' . $request->file('aadhaar_doc')->getClientOriginalExtension();
            $request->file('aadhaar_doc')->move(public_path('documents'), basename($aadhaarPath));
            $aadhaarFilename = Crypt::encryptString($aadhaarPath); // Encrypt file path
        }

        $panFilename = null;
        if ($request->hasFile('pancard_doc')) {
            $panPath = 'documents/pan_' . time() . '.' . $request->file('pancard_doc')->getClientOriginalExtension();
            $request->file('pancard_doc')->move(public_path('documents'), basename($panPath));
            $panFilename = Crypt::encryptString($panPath); // Encrypt file path
        }

        $gstFilename = null;
        if ($request->hasFile('gst_doc')) {
            $gstPath = 'documents/gst_' . time() . '.' . $request->file('gst_doc')->getClientOriginalExtension();
            $request->file('gst_doc')->move(public_path('documents'), basename($gstPath));
            $gstFilename = Crypt::encryptString($gstPath); // Encrypt file path
        }



        DB::table('tnelb_applicant_doc_A')->insert([
            'login_id'       => $request->login_id_store,
            'application_id' => $newApplicationId,
            'aadhaar_doc'    => $aadhaarFilename,
            'pancard_doc'    => $panFilename,
            'gst_doc'        => $gstFilename,
            'created_at'     => now(),
            'updated_at'     => now()
        ]);

        $validatedData['aadhaar'] = Crypt::encryptString($validatedData['aadhaar']);
        $validatedData['pancard'] = Crypt::encryptString($validatedData['pancard']);
        $validatedData['gst_number'] = Crypt::encryptString($validatedData['gst_number']);

        $form = EA_Application_model::create([
            'login_id' => $request->login_id_store,
            'application_id' => $newApplicationId,
            'application_status' => 'P',
            'license_number' => '',
            'payment_status' => $isDraft ? 'draft' : 'paid',
            'name_of_authorised_to_sign' => !empty($request->name_of_authorised_to_sign)
                ? json_encode($request->name_of_authorised_to_sign)
                : null,

            'enclosure' => '1',
            'previous_contractor_license' => $request->previous_contractor_license,
            'criminal_offence' => $request->criminal_offence,
            'consent_letter_enclose' => $request->consent_letter_enclose,
            'cc_holders_enclosed' => $request->cc_holders_enclosed,
            'purchase_bill_enclose' => $request->purchase_bill_enclose,
            'test_reports_enclose' => $request->test_reports_enclose,
            'specimen_signature_enclose' => $request->specimen_signature_enclose,


            'separate_sheet' => $request->separate_sheet,

        ] + $validatedData);

        if ($request->has('staff_name')) {
            foreach ($request->staff_name as $index => $staffName) {
                TnelbApplicantStaffDetail::create([
                    'login_id' => $request->login_id_store,
                    'application_id' => $newApplicationId,
                    'staff_name' => $staffName,
                    'staff_qualification' => $request->staff_qualification[$index] ?? null,
                    'cc_number' => $request->cc_number[$index] ?? null,
                    'cc_validity' => $request->cc_validity[$index] ?? null,
                    'staff_category' => $request->staff_category[$index],
                ]);
            }
        }



        if ($request->has('proprietor_name')) {

            foreach ($request->proprietor_name as $index => $proprietor_name) {

                $competencyHolding = $request->competency_certificate_holding[$index] ?? 'no';
                // dd($request->all());
                // die;
                $list = ProprietorformA::create([
                    'login_id' => $request->login_id_store,
                    'application_id' => $newApplicationId,
                    'proprietor_name' => $proprietor_name ?? null,

                    //         'proprietor_address' => '123 Test Street',
                    // 'age' => 35,
                    // 'qualification' => 'B.Tech',
                    // 'fathers_name' => 'John Doe',
                    // 'present_business' => 'Electrical Works',

                    // 'competency_certificate_holding' => 'yes',
                    // 'competency_certificate_number' => 'CC123456',
                    // 'competency_certificate_validity' => '2026-12-31',

                    // 'presently_employed' => 'no',
                    // 'presently_employed_name' => null,
                    // 'presently_employed_address' => null,

                    // 'previous_experience' => 'yes',
                    // 'previous_experience_name' => 'XYZ Pvt Ltd',
                    // 'previous_experience_address' => '456 Business Park',
                    // 'previous_experience_lnumber' => 'LN789012',

                    'proprietor_address' => data_get($request->proprietor_address, $index),
                    'age' => data_get($request->age, $index),
                    'qualification' => data_get($request->qualification, $index),
                    'fathers_name' => data_get($request->fathers_name, $index, 'Not Provided'),
                    'present_business' => data_get($request->present_business, $index),

                    'competency_certificate_holding' => data_get($request->competency_certificate_holding, $index, 'no'),
                    'competency_certificate_number' => data_get($request->competency_certificate_holding, $index) === 'yes'
                        ? data_get($request->competency_certificate_number, $index)
                        : null,
                    'competency_certificate_validity' => data_get($request->competency_certificate_holding, $index) === 'yes'
                        ? data_get($request->competency_certificate_validity, $index)
                        : null,

                    'presently_employed' => data_get($request->presently_employed, $index, 'no'),
                    'presently_employed_name' => data_get($request->presently_employed, $index) === 'yes'
                        ? data_get($request->presently_employed_name, $index)
                        : null,
                    'presently_employed_address' => data_get($request->presently_employed, $index) === 'yes'
                        ? data_get($request->presently_employed_address, $index)
                        : null,

                    'previous_experience' => data_get($request->previous_experience, $index, 'no'),
                    'previous_experience_name' => data_get($request->previous_experience, $index) === 'yes'
                        ? data_get($request->previous_experience_name, $index)
                        : null,
                    'previous_experience_address' => data_get($request->previous_experience, $index) === 'yes'
                        ? data_get($request->previous_experience_address, $index)
                        : null,
                    'previous_experience_lnumber' => data_get($request->previous_experience, $index) === 'yes'
                        ? data_get($request->previous_experience_lnumber, $index)
                        : null,

                        'previous_experience_lnumber_validity' => data_get($request->previous_experience, $index) === 'yes'
                        ? data_get($request->previous_experience_lnumber_validity, $index)
                        : null,


                        



                    // 'competency_certificate_holding' => $request->competency_certificate_holding[$index] ?? 'no',
                    // 'competency_certificate_number' => $request->competency_certificate_number[$index] ?? null,
                    // 'competency_certificate_validity' => $request->competency_certificate_validity[$index] ?? null,
                    // 'competency_certificate_number' => 
                    //     ($request->competency_certificate_holding[$index] === 'yes') ? 
                    //     ($request->competency_certificate_number[$index] ?? null) : null,
                    // 'competency_certificate_validity' => 
                    //     ($request->competency_certificate_holding[$index] === 'yes') ? 
                    //     ($request->competency_certificate_validity[$index] ?? null) : null,
                    // 'competency_certificate_holding' => 'yes',
                    // 'competency_certificate_number' => 'CC123456',
                    // 'competency_certificate_validity' => '2026-12-31',

                    // 'presently_employed' => 'no',
                    // 'presently_employed_name' => null,
                    // 'presently_employed_address' => null,

                    // 'previous_experience' => 'yes',
                    // 'previous_experience_name' => 'XYZ Pvt Ltd',
                    // 'previous_experience_address' => '456 Business Park',
                    // 'previous_experience_lnumber' => 'LN789012',
                    // 'competency_certificate_number' => ($request->competency_certificate_holding[$index] === 'yes')
                    //     ? ($request->competency_certificate_number[$index] ?? null)
                    //     : null,
                    // 'competency_certificate_validity' => ($request->competency_certificate_holding[$index] === 'yes')
                    //     ? ($request->competency_certificate_validity[$index] ?? null)
                    //     : null,

                    // 'presently_employed' => $request->presently_employed[$index] ?? 'no',
                    // 'presently_employed_name' => ($request->presently_employed[$index] === 'yes')
                    //     ? ($request->presently_employed_name[$index] ?? null)
                    //     : null,
                    // 'presently_employed_address' => ($request->presently_employed[$index] === 'yes')
                    //     ? ($request->presently_employed_address[$index] ?? null)
                    //     : null,

                    // 'previous_experience' => $request->previous_experience[$index] ?? 'no',
                    // 'previous_experience_name' => ($request->previous_experience[$index] === 'yes')
                    //     ? ($request->previous_experience_name[$index] ?? null)
                    //     : null,
                    // 'previous_experience_address' => ($request->previous_experience[$index] === 'yes')
                    //     ? ($request->previous_experience_address[$index] ?? null)
                    //     : null,
                    // 'previous_experience_lnumber' => ($request->previous_experience[$index] === 'yes')
                    //     ? ($request->previous_experience_lnumber[$index] ?? null)
                    //     : null,
                ]);
            }
            // var_dump($list);
            // die;
        }



        if (!$isDraft) {
            $transactionId = 'TXN' . rand(100000, 999999);

            Payment::create([
                'login_id' => $request->login_id_store,
                'application_id' => $newApplicationId,
                'transaction_id' => $transactionId,
                'payment_status' => 'success',
                'amount' => $request->amount,
                'form_name' => $form->form_name,
                'license_name' => $form->license_name,
            ]);

            mst_workflow::create([
                'login_id' => $request->login_id_store,
                'application_id' => $newApplicationId,
                'transaction_id' => $transactionId,
                'payment_status' => 'success',
                'formname_appliedfor' => $form->form_name,
                'license_name' => $form->license_name,
            ]);

            return response()->json([
                'message' => 'Payment Processed!',
                'login_id' => $newApplicationId,
                'transaction_id' => $transactionId,
            ]);
        }

        // ✅ Return Draft Response
        return response()->json([
            'message' => 'Payment Processed!',
            'login_id' => $newApplicationId,
            'transaction_id' => '11111',
        ]);
    }

    public function update(Request $request, $id)
    {

        $isDraft = $request->input('form_action') === 'draft';

           
        $request->merge([
            'aadhaar' => preg_replace('/\D/', '', $request->aadhaar)
        ]);

        $rules = [
            'applicant_name'                => 'required|string|max:255',
            'business_address'              => 'required|string|max:500',
            'authorised_name_designation'   => 'required',
            'authorised_name'               => 'nullable|string|max:255',
            'authorised_designation'        => 'nullable|string|max:255',
            'previous_contractor_license'   => 'required|string|max:10',
            'previous_application_number'   => 'nullable|string|max:50',
            'bank_address'                  => 'required|string|max:500',
            'bank_validity'                 => 'required|date',
            'bank_amount'                   => 'required|numeric|min:1',
            'criminal_offence'              => ['required', 'string', Rule::in(['yes', 'no'])],
            'consent_letter_enclose'        => ['required', 'string', Rule::in(['yes', 'no'])],
            'cc_holders_enclosed'           => ['required', 'string', Rule::in(['yes', 'no'])],
            'purchase_bill_enclose'         => ['required', 'string', Rule::in(['yes', 'no'])],
            'test_reports_enclose'          => ['required', 'string', Rule::in(['yes', 'no'])],
            'specimen_signature_enclose'    => ['required', 'string', Rule::in(['yes', 'no'])],
            'separate_sheet'                => ['required', 'string', Rule::in(['yes', 'no'])],
            'form_name'                     => 'required|string|max:255',
            'license_name'                  => 'required|string|max:255',
        ];

        if ($isDraft) {
            foreach ($rules as $key => $rule) {
                $rules[$key] = str_replace('required', 'nullable', $rule);
            }
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();

        try {

            // $ApplicationId = $request->application_id;
                // dd($id);
            $form = EA_Application_model::where('application_id', $id)->firstOrFail();

            $appl_type = $request->appl_type ?? '';


            // generate Application ID
            $lastApplication = EA_Application_model::latest('id')->value('application_id');
            $nextNumber = $lastApplication ? ((int) substr($lastApplication, -7)) + 1 : 1111111;
            $newApplicationId = $appl_type . $request->form_name . $request->license_name . date('y') . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

            // file uploads
            $aadhaarFilename = null;
            if ($request->hasFile('aadhaar_doc')) {
                $aadhaarFilename = 'documents/aadhaar_' . time() . '.' . $request->file('aadhaar_doc')->getClientOriginalExtension();
                $request->file('aadhaar_doc')->move(public_path('documents'), $aadhaarFilename);
            }

            $panFilename = null;
            if ($request->hasFile('pancard_doc')) {
                $panFilename = 'documents/pan_' . time() . '.' . $request->file('pancard_doc')->getClientOriginalExtension();
                $request->file('pancard_doc')->move(public_path('documents'), $panFilename);
            }

            $gst_Filename = null;
            if ($request->hasFile('gst_card_doc')) {
                $gst_Filename = 'documents/gst__' . time() . '.' . $request->file('gst_card_doc')->getClientOriginalExtension();
                $request->file('gst_doc')->move(public_path('documents'), $gst_Filename);
            }



            $document_ea = DB::table('tnelb_applicant_doc_A')->insert([
                'login_id'       => $request->login_id_store,
                'application_id' => $newApplicationId,
                'aadhaar_doc'    => $aadhaarFilename,
                'pancard_doc'    => $panFilename,
                'gst_doc'        => $gst_Filename,
                'created_at'     => now(),
                'updated_at'     => now()
            ]);

            // dd($document_ea);
            // exit;


            // Main form insert
            $form = EA_Application_model::create([
                'login_id'                     => $request->login_id_store,
                'application_id'              => $newApplicationId,
                'application_status'          => 'P',
                'license_number'              => '',
                'payment_status'              => 'paid',  //: 'paid',
                'name_of_authorised_to_sign'  => !empty($request->name_of_authorised_to_sign) ? json_encode($request->name_of_authorised_to_sign) : null,
                'enclosure'                   => '1',
                'license_number'              => $request->previous_application_number,
                'criminal_offence'            => $request->criminal_offence,
                'consent_letter_enclose'      => $request->consent_letter_enclose,
                'cc_holders_enclosed'         => $request->cc_holders_enclosed,
                'purchase_bill_enclose'       => $request->purchase_bill_enclose,
                'test_reports_enclose'        => $request->test_reports_enclose,
                'specimen_signature_enclose'  => $request->specimen_signature_enclose,
                'separate_sheet'              => $request->separate_sheet,
                'aadhaar'                     => $request->aadhaar,
                'pancard'                     => $request->pancard,
                'gst_number'                  => $request->gst_number,
                'appl_type'                   => $appl_type,
                'aadhaar_doc'                 => $aadhaarFilename,
                'pan_doc'                     => $panFilename,
                'gst_doc'                     => $gst_Filename,
                'old_application'             => $id,
            ] + $validatedData);


            // Staff details
            if ($request->has('staff_name')) {
                foreach ($request->staff_name as $index => $staffName) {
                    TnelbApplicantStaffDetail::create([
                        'login_id'          => $request->login_id_store,
                        'application_id'    => $newApplicationId,
                        'staff_name'        => $staffName,
                        'staff_qualification' => $request->staff_qualification[$index] ?? null,
                        'cc_number'         => $request->cc_number[$index] ?? null,
                        'cc_validity'       => $request->cc_validity[$index] ?? null,
                    ]);
                }
            }

            // Proprietors
            if ($request->has('proprietor_name')) {
                foreach ($request->proprietor_name as $index => $proprietor_name) {
                    $competencyHolding = $request->competency_certificate_holding[$index] ?? 'no';

                    ProprietorformA::create([
                        'login_id'                      => $request->login_id_store,
                        'application_id'                => $newApplicationId,
                        'proprietor_name'               => $proprietor_name,
                        'proprietor_address'            => $request->proprietor_address[$index] ?? null,
                        'age'                           => $request->age[$index] ?? null,
                        'qualification'                 => $request->qualification[$index] ?? null,
                        'fathers_name'                  => $request->fathers_name[$index] ?? 'Not Provided',
                        'present_business'              => $request->present_business[$index] ?? null,
                        'competency_certificate_holding' => $competencyHolding,
                        'competency_certificate_number' => $competencyHolding === 'yes' ? ($request->competency_certificate_number[$index] ?? null) : null,
                        'competency_certificate_validity' => $competencyHolding === 'yes' ? ($request->competency_certificate_validity[$index] ?? null) : null,
                        'presently_employed'            => $request->presently_employed[$index] ?? 'no',
                        'presently_employed_name'       => ($request->presently_employed[$index] === 'yes') ? ($request->presently_employed_name[$index] ?? null) : null,
                        'presently_employed_address'    => ($request->presently_employed[$index] === 'yes') ? ($request->presently_employed_address[$index] ?? null) : null,
                        'previous_experience'           => $request->previous_experience[$index] ?? 'no',
                        'previous_experience_name'      => ($request->previous_experience[$index] === 'yes') ? ($request->previous_experience_name[$index] ?? null) : null,
                        'previous_experience_address'   => ($request->previous_experience[$index] === 'yes') ? ($request->previous_experience_address[$index] ?? null) : null,
                        'previous_experience_lnumber'   => ($request->previous_experience[$index] === 'yes') ? ($request->previous_experience_lnumber[$index] ?? null) : null,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => '200',
                'message' => 'Form saved as draft',
                'application_id' => $newApplicationId,
                'applicantName' => $form->applicant_name
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Form store failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    // public function store(Request $request)
    // {
    //     $isDraft = $request->input('form_action') === 'draft';

    //     // ✅ Validation Rules
    //     $rules = [
    //         'applicant_name'                => 'required|string|max:255',
    //         'business_address'              => 'required|string|max:500',
    //         'authorised_name_designation'   => 'required',
    //         'authorised_name'               => 'nullable|string|max:255',
    //         'authorised_designation'        => 'nullable|string|max:255',
    //         'previous_contractor_license'   => 'required|string|max:10',
    //         'previous_application_number'   => 'nullable|string|max:50',
    //         'bank_address'                  => 'required|string|max:500',
    //         'bank_validity'                 => 'required|date',
    //         'bank_amount'                   => 'required|numeric|min:1',
    //         'criminal_offence'              => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'consent_letter_enclose'        => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'cc_holders_enclosed'           => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'purchase_bill_enclose'         => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'test_reports_enclose'          => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'specimen_signature_enclose'    => ['required', 'string', Rule::in(['yes', 'no'])],
    //         'separate_sheet'                => ['required', 'string', Rule::in(['yes', 'no'])], 
    //         'form_name'                     => 'required|string|max:255',
    //         'license_name'                  => 'required|string|max:255',
    //         // 'aadhaar'                       => 'required|digits:12',
    //         // 'pancard'                       => 'required|string|size:10',
    //         // 'declaration1'                  => 'required|string|max:255',
    //         // 'declaration2'                  => 'required|string|max:255',
    //     ];

    //     // Relax validation for Draft
    //     if ($isDraft) {
    //         foreach ($rules as $key => $rule) {
    //             $rules[$key] = str_replace('required', 'nullable', $rule);
    //         }
    //     }

    //     // Validate Data
    //     $validatedData = $request->validate($rules);

    //     // Generate Application ID
    //     $lastApplication    = EA_Application_model::latest('id')->value('application_id');
    //     $nextNumber         = $lastApplication ? ((int) substr($lastApplication, -7)) + 1 : 1111111;
    //     $newApplicationId   = $request->form_name . $request->license_name . date('y') . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

    //       // Initialize paths
    //       $aadhaarFilename = null;
    //       $panFilename = null;

    //       // Aadhaar doc
    //       if ($request->hasFile('aadhaar_doc')) {
    //           $aadhaarFilename = 'documents/'.'aadhaar_' . time() . '.' . $request->file('aadhaar_doc')->getClientOriginalExtension();
    //           $destinationPath = public_path('documents');
    //           $request->file('aadhaar_doc')->move($destinationPath, $aadhaarFilename);
    //       }

    //       // PAN doc
    //       if ($request->hasFile('pancard_doc')) {
    //           $panFilename = 'documents/'.'pan_' . time() . '.' . $request->file('pancard_doc')->getClientOriginalExtension();
    //           $destinationPath = public_path('documents');
    //           $request->file('pancard_doc')->move($destinationPath, $panFilename);
    //       }

    //     // Save Main Form Data
    //     $form = EA_Application_model::create([
    //         'login_id' => $request->login_id_store,
    //         'application_id' => $newApplicationId,
    //         'application_status' => 'P',
    //         'license_number' => '',
    //         'payment_status' => $isDraft ? 'draft' : 'paid',
    //         'name_of_authorised_to_sign' => !empty($request->name_of_authorised_to_sign)? json_encode($request->name_of_authorised_to_sign): null,
    //         'enclosure' => '1',
    //         'previous_contractor_license' => $request->previous_contractor_license,
    //         'criminal_offence' => $request->criminal_offence,
    //         'consent_letter_enclose' => $request->consent_letter_enclose,
    //         'cc_holders_enclosed' => $request->cc_holders_enclosed,
    //         'purchase_bill_enclose' => $request->purchase_bill_enclose,
    //         'test_reports_enclose' => $request->test_reports_enclose,
    //         'specimen_signature_enclose' => $request->specimen_signature_enclose,
    //         'separate_sheet' => $request->separate_sheet,
    //         'aadhaar_doc'         => $aadhaarFilename, 
    //         'pan_doc'             => $panFilename,  


    //     ] + $validatedData);

    //     if ($request->has('staff_name')) {
    //         foreach ($request->staff_name as $index => $staffName) {
    //             TnelbApplicantStaffDetail::create([
    //                 'login_id' => $request->login_id_store,
    //                 'application_id' => $newApplicationId,
    //                 'staff_name' => $staffName,
    //                 'staff_qualification' => $request->staff_qualification[$index] ?? null,
    //                 'cc_number' => $request->cc_number[$index] ?? null,
    //                 'cc_validity' => $request->cc_validity[$index] ?? null,
    //             ]);
    //         }
    //     }


    //     if ($request->has('proprietor_name')) {

    //         foreach ($request->proprietor_name as $index => $proprietor_name) {

    //             $competencyHolding = $request->competency_certificate_holding[$index] ?? 'no';
    //             $list =ProprietorformA::create([
    //                 'login_id' => $request->login_id_store,
    //                 'application_id' => $newApplicationId,
    //                 'proprietor_name' => $proprietor_name,
    //                 'proprietor_address' => $request->proprietor_address[$index] ?? null,
    //                 'age' => $request->age[$index] ?? null,
    //                 'qualification' => $request->qualification[$index] ?? null,
    //                 'fathers_name' => $request->fathers_name[$index] ?? 'Not Provided',
    //                 'present_business' => $request->present_business[$index] ?? null,

    //                 'competency_certificate_holding' => $competencyHolding,
    //                 'competency_certificate_number' => ($competencyHolding === 'yes')
    //                     ? ($request->competency_certificate_number[$index] ?? null)
    //                     : null,
    //                 'competency_certificate_validity' => ($competencyHolding === 'yes')
    //                     ? ($request->competency_certificate_validity[$index] ?? null)
    //                     : null,

    //                 'presently_employed' => $request->presently_employed[$index] ?? 'no',

    //                 'presently_employed_name' => ($request->presently_employed[$index] === 'yes')
    //                     ? ($request->presently_employed_name[$index] ?? null)
    //                     : null,
    //                 'presently_employed_address' => ($request->presently_employed[$index] === 'yes')
    //                     ? ($request->presently_employed_address[$index] ?? null)
    //                     : null,
    //                 'previous_experience' => $request->previous_experience[$index] ?? 'no',
    //                 'previous_experience_name' => ($request->previous_experience[$index] === 'yes')
    //                     ? ($request->previous_experience_name[$index] ?? null)
    //                     : null,
    //                 'previous_experience_address' => ($request->previous_experience[$index] === 'yes')
    //                     ? ($request->previous_experience_address[$index] ?? null)
    //                     : null,
    //                 'previous_experience_lnumber' => ($request->previous_experience[$index] === 'yes')
    //                     ? ($request->previous_experience_lnumber[$index] ?? null)
    //                     : null,
    //             ]);

    //         }
    //     }

    //     if ($isDraft) {

    //         return response()->json([
    //             'message' => 'Form saved as draft',
    //             'login_id' => $newApplicationId,
    //         ], 200);
    //     }


    // }


        public function updatePaymentStatus(Request $request)
    {

        // dd($request->all())
        $request->validate([
            'application_id' => 'required|string',
            'payment_status' => 'required|in:draft,pending,paid',
        ]);

        EA_Application_model::where('application_id', $request->application_id)
            ->update(['payment_status' => $request->payment_status]);

        return response()->json(['status' => 'updated']);
    }


      public function expiry_date_change(){

        $licensedates = DB::table('tnelb_license')->get();
        return view('user_login.license_datechange.index', compact('licensedates'));
    }
}
