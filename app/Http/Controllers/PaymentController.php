<?php

namespace App\Http\Controllers;

use App\Models\EA_Application_model;
use App\Models\Mst_documents;
use App\Models\Mst_education;
use App\Models\Mst_experience;
use App\Models\Mst_Form_s_w;
use App\Models\Payment; // ✅ Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'login_id'        => 'required',
            'application_id'  => 'required',
            'transaction_id'  => 'required',
            'amount'          => 'required',
            'payment_mode'    => 'required',
        ]);

        // var_dump($request->application_id);die;
        if ($request->application_id) {
            $form = Mst_Form_s_w::where('application_id', $validated['application_id'])->first();
        }
        
        // else {
            
        //     $form = EA_Application_model::where('application_id', $validated['application_id'])->first();
        // }

        if (!$form) {
            return response()->json([
                'status' => 404,
                'message' => 'Form details not found!',
            ]);
        }

        $payment = Payment::updateOrCreate(
            [
                'login_id'        => $validated['login_id'],
                'application_id'  => $validated['application_id'],
            ],
            [
                'transaction_id'  => $validated['transaction_id'],
                'payment_status'  => 'success',
                'amount'          => $validated['amount'],
                'form_name'       => $form->form_name,
                'license_name'    => $form->license_name,
                'payment_mode'    => $validated['payment_mode'],
            ]
        );

        if ($payment) {
            if (in_array($request->form_name, ['S', 'W', 'WH'])) {

                Mst_Form_s_w::where('application_id', $validated['application_id'])
                ->update([
                    'payment_status' => 'payment', // e.g., 'payment', 'draft', etc.
                ]);
            }else {
                EA_Application_model::where('application_id', $validated['application_id'])
                ->update([
                    'payment_status' => 'payment', // e.g., 'payment', 'draft', etc.
                ]);
            }
        }

        return response()->json([
            'status'  => 200,
            'message' => 'Payment created successfully!',
            'data'    => $payment
        ]);
    }
}
