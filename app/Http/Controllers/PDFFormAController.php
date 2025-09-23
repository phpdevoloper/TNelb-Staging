<?php

namespace App\Http\Controllers;

use App\Models\EA_Application_model;
use App\Models\ProprietorformA;
use App\Models\TnelbApplicantStaffDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Mpdf\Mpdf;
use Carbon\Carbon;

class PDFFormAController extends Controller
{
    public function generateaPDF($newApplicationId)
    {

        // return $newApplicationId;
        // exit;
        // Fetch form details
        $form = EA_Application_model::where('application_id', $newApplicationId)->first();
        $education = TnelbApplicantStaffDetail::where('application_id', $newApplicationId)->orderby('id', 'ASC')->get();
        $proprietor = ProprietorformA::where('application_id', $newApplicationId)->where('proprietor_flag', '1')->get();
        // $documents = Mst_documents::where('application_id', $newApplicationId)->first();
        // $payment = DB::table('payments')->where('application_id', $newApplicationId)->first();

        if (!$form) {
            return redirect()->back()->with('error', 'No records found!');
        }

        // Initialize mPDF
        // $mpdf = new Mpdf();
        $mpdf = new Mpdf(['default_font_size' => 10]);
        $mpdf->SetFont('helvetica', '', 10);
        $mpdf->WriteHTML('<style> 
            body { line-height: 0.8; } 
            p, td, th { line-height: 2.0; padding: 2px; }
             td, th { line-height: 2.0; padding: 2px; }
             th{font-size:13px;}
             h3, h4, p {
            margin: 2px 0; /* Reduces top & bottom margin */
            line-height: 1.5; /* Adjusts spacing between lines */
        }
            .tbl_center tr td{
            text-align:center;
            }
            .mt-2{

            margin-top:10px;
        }
        </style>', \Mpdf\HTMLParserMode::HEADER_CSS);


        $mpdf->SetTitle('TNELB Application License Form ' . $form->form_name);

        // Application Title
        $html = '
    <h3 style="text-align: center;">GOVERNMENT OF TAMILNADU</h3>
    <h4 style="text-align: center;">THE ELECTRICAL LICENSING BOARD</h4>
    <p style="text-align: center;">Thiru.Vi.Ka.Indl.Estate, Guindy, Chennai – 600032.</p>
';

$appl_type = trim($form->appl_type);




      if($appl_type === 'R')
      {

            $html .= '<h4 style="text-align: center;">Form "' . $form->form_name . '" Renewal  Application</h4>';
        } else {
            $html .= '<h4 style="text-align: center;">Form "' . $form->form_name . '" New Application</h4>';
        }

        $html .= '
    <p style="text-align: center;">Application for Electrical Contractor/s Licence-Grade "' . $form->license_name . '"</p>
    <p style="text-align: center;"><strong>Application No : ' . $newApplicationId . '</strong></p>
';

        // Applicant Details
        $html .= '
        <table width="80%"  style="border-collapse: collapse;">
            <tr>
                <td ></td>
                <td ></td>
                <td >
    ';


        $html .= '
                </td>
            </tr>
            <tr>
                
                <td><strong>1.Name of the applicant:</strong> </td>
                <td>' . $form->applicant_name . '</td>
            </tr>
            <tr>
                
                <td><strong>2.Business address:</strong> </td>
                <td> ' . $form->business_address . '</td>
            </tr>
         
        </table>';

        $html .= '<h4 class="mt-2">3. Proprietor Details</h4>
    <table border="1" width="100%" cellspacing="0" cellpadding="5" class="text-center tbl_center" >
        <tr>
            <th >Name and Address</th>
            <th>Age and Qualifications</th>
            <th>Father/s Husband/s Name</th>
            <th>Present business of the applicant</th>
            
            <th>Competency Certificate and Validity</th>
            <th>Presently Employed and Address</th>

            <th>If holding a competency certificate - Contractor Details </th>
            
        </tr>';

        if ($proprietor->isNotEmpty()) {

            foreach ($proprietor as $pro) {
                $c_validity = $pro->competency_certificate_validity
                    ? date('d-m-Y', strtotime($pro->competency_certificate_validity))
                    : '';

                $previous_experience_lnumber_validity = $pro->previous_experience_lnumber_validity
                    ? date('d-m-Y', strtotime($pro->previous_experience_lnumber_validity))
                    : '';
                $html .= '<tr>
                    <td>' . $pro->proprietor_name . ', ' . $pro->proprietor_address . '</td>
                    <td>' . $pro->age . ' ' . $pro->qualification . '</td>
                    <td>' . $pro->fathers_name . '</td>
                    <td>' . $pro->present_business . '</td>
                    <td>' . $pro->competency_certificate_number . '-' . $c_validity . '</td>
                    <td>' . $pro->presently_employed_name . '- ' . $pro->presently_employed_address . '</td>
                    <td>' . $pro->previous_experience_name . '- ' . $pro->previous_experience_address .  '- ' . $pro->previous_experience_lnumber . '- ' . $previous_experience_lnumber_validity . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="6" class="text-center">No proprietor records found</td></tr>';
        }

        $html .= '</table>'; //  Close Proprietor Table Properly

        $html .= '
    <table> 
        <tr>
            <td width="80%">
                <h4>4. Name and designation of authorised signatory (if any, in the case of a limited company):</h4>
            </td>
            <td width="20%">' . strtoupper($form->authorised_name_designation) . '</td>
        </tr>
    </table>';

        if ($form->authorised_name_designation === 'yes') {
            $html .= '
        <table width="50%" cellspacing="0" cellpadding="3" class="">
            <tr>
                <td></td>
                <td>Name:</td>
                <td>' . strtoupper($form->authorised_name) . '</td>
            </tr>
            <tr>
                <td></td>
                <td>Designation:</td>
                <td>' . strtoupper($form->authorised_designation) . '</td>
            </tr>
        </table>';
        }

        $html .= '
    <table> 
        <tr>
            <td width="80%">
                <h4>5. Whether any application for
                    Contractor/s licence was made
                    previously? If so, details thereof</h4>
            </td>
            <td width="20%">' . strtoupper($form->previous_contractor_license) . '</td>
        </tr>
    </table>';

        if ($form->previous_contractor_license === 'yes') {
            $formattedValidity = '';
            if (!empty($form->previous_application_validity)) {
                $formattedValidity = \Carbon\Carbon::parse($form->previous_application_validity)->format('d-m-Y');
            }

            $html .= '
        <table width="50%" cellspacing="0" cellpadding="3" class="">
            <tr>
                <td></td>
                <td>Previous License Number:</td>
                <td>' . strtoupper($form->previous_application_number) . '</td>
            </tr>
            <tr>
                <td></td>
                <td>Previous License Validity:</td>
                <td>' . $formattedValidity . '</td>
            </tr>
        </table>';
        }


        // $html .= "<pagebreak />";

        $html .= '<h4 class="mt-2">6. Staff Details</h4>
        <table border="1" width="100%" cellspacing="0" cellpadding="5" class="text-center tbl_center">
            <tr>
                <th>S.No</th>
                <th>Staff Name</th>
                <th>Staff Qualification</th>
                <th>Staff Category</th>
                <th>Competency Certificate Number</th>
                <th>Competency Certificate Validity</th>
                
            </tr>';

        $i = 1;
        foreach ($education as $edu) {
            $cc_validity = $edu->cc_validity
                ? date('d-m-Y', strtotime($edu->cc_validity))
                : '';
            $html .= '<tr >
                <td class="text-center">' . $i . '</td>
                <td class="text-center">' . $edu->staff_name . '</td>
                <td class="text-center">' . $edu->staff_qualification . '</td>
                
                <td class="text-center">' . $edu->staff_category . '</td>
                <td class="text-center">' . $edu->cc_number . '</td>
                <td class="text-center">' . $cc_validity . '</td>
                
            </tr>';
            $i++;
        }
        $html .= '</table>';

        $bank_validity = $form->bank_validity
            ? date('d-m-Y', strtotime($form->bank_validity))
            : '';

        $html .= '
        <h4 class="mt-2">7. Bank Solvency Certificate details</h4>
            <table  width="50%" cellspacing="0" cellpadding="3" class="">
               

                 <tr>
                   <td></td>
                    <td>Name of the Bank and Address:</td>
                    <td>' . $form->bank_address . '</td>
                </tr>

                  <tr>
                   <td></td>
                    <td>Validity Period:</td>
                    <td>' . $bank_validity . '</td>
                </tr>

                  <tr>
                   <td></td>
                    <td>Amount Rs:</td>
                    <td>' . $form->bank_amount . '</td>
                </tr>

                
            </table> <br><br>';


        $html .= '
           
                <table  width="80%" cellspacing="0" cellpadding="3" class="">
                   
    
                     <tr>
                       <td><strong> 8. Has the applicant or any of his/her
                staff referred to under item 6, been at
                any time convicted in any court of law
                or punished by any other authority for
                criminal offences </strong></td>
                        
                        <td>' . strtoupper($form->criminal_offence) . '</td>
                    </tr>
    
                     
    
                    
                </table> ';



        $html .= '
           
                <table  width="80%" cellspacing="0" cellpadding="3" class="">
                   
    
                     <tr>
                       <td><strong>9. (i). Whether consent letter, of the competency certificate holder are enclosed. (including for self)</strong></td>
                        
                        <td>' . strtoupper($form->consent_letter_enclose) . '</td>
                    </tr>
    
                       <tr>
                       <td><strong>(ii). Whether original booklet of competency certificate holders are enclosed? (including for self)</strong></td>
                        
                        <td>' . strtoupper($form->cc_holders_enclosed) . '</td>
                    </tr>
                     
    
                    
                </table> ';

        $html .= '
           
                <table  width="80%" cellspacing="0" cellpadding="3" class="">
                   
    
                     <tr>
                       <td><strong>10. (i). Whether purchase bill for all the
                                        instruments are enclosed in
                                        Original.</strong></td>
                        
                        <td>' . strtoupper($form->purchase_bill_enclose) . '</td>
                    </tr>
    
                       <tr>
                       <td><strong>(ii). Whether the test reports for
                                    instruments and deeds for possess
                                    of the instruments are enclosed in
                                    original?</strong></td>
                        
                        <td>' . strtoupper($form->test_reports_enclose) . '</td>
                    </tr>
                     
    
                    
                </table> ';









        $html .= '           
                    <table  width="80%" cellspacing="0" cellpadding="3" class="">
                    
        
                        <tr>
                        <td><strong> 11 (i). Whether specimen signature of
                            the Proprietor or of the authorised
                            signatory (in case of limited
                            company in triplicate is enclosed) </strong></td>
                            
                            <td>' . strtoupper($form->specimen_signature_enclose) . '</td>
                        </tr>
        
                        
        
                        
                    </table> ';





        $name_of_authorised_to_sign = $form->name_of_authorised_to_sign;

        // Check if it's a valid JSON before decoding
        if (is_string($name_of_authorised_to_sign) && $decoded = json_decode($name_of_authorised_to_sign, true)) {
            $name_of_authorised_to_sign = $decoded;
        } else {
            // Convert a comma-separated string into an array
            $name_of_authorised_to_sign = explode(',', $name_of_authorised_to_sign);
        }

      $html .= '<table width="100%" cellspacing="0" cellpadding="3">
            <tr>
                <td width="70%">
                    <strong>(ii). The name of the person(s) whom the applicant has authorized to sign, if any, on his/their behalf in case of Proprietor or Partnership concern</strong>
                </td>
                <td width="30%">';

if (!empty($name_of_authorised_to_sign) && $name_of_authorised_to_sign !== 'null') {
    if (is_array($name_of_authorised_to_sign)) {
        $names = [];
        foreach ($name_of_authorised_to_sign as $sign) {
            $signName = is_array($sign) ? strtoupper($sign['name'] ?? '') : strtoupper(trim($sign));
            $names[] = $signName;
        }
        $html .= implode('<br>', $names);
    } else {
        $html .= strtoupper(trim($name_of_authorised_to_sign));
    }
} else {
    $html .= '-';
}

$html .=    '</td>
            </tr>
        </table>';



        $html .= '
           
                    <table  width="80%" cellspacing="0" cellpadding="3" class="">
                       
        
                         <tr>
                           <td><strong> (iii). Whether the applicant enclosed
                                the specimen signature of the
                                above person/ persons in triplicate
                                in a separate sheet of paper </strong></td>
                            
                            <td>' . strtoupper($form->separate_sheet) . '</td>
                        </tr>
        
                         
        
                        
                    </table> ';


        try {
            $decryptedaadhaar = Crypt::decryptString($form->aadhaar);
            $maskaadhaar = str_repeat('X', strlen($decryptedaadhaar) - 4) . substr($decryptedaadhaar, -4);

            $decryptedpancard = Crypt::decryptString($form->pancard);
            $maskpancard = str_repeat('X', strlen($decryptedpancard) - 4) . substr($decryptedpancard, -4);

            $decryptedgst_number = Crypt::decryptString($form->gst_number);
            $maskgst_number = str_repeat('X', strlen($decryptedgst_number) - 4) . substr($decryptedgst_number, -4);
        } catch (\Exception $e) {
            $maskaadhaar = 'Invalid or corrupted AAdhaar';
            $maskpancard = 'Invalid or corrupted Pancard';
            $maskgst_number = 'Invalid or corrupted GST Number';
        }

     $html .= '
    <table width="80%" cellspacing="0" cellpadding="3" style="text-align:left;">
        <tr>
            <td>12.</td>
            <td><strong>i) Aadhaar Number</strong></td>
            <td style="text-align:right;">' . strtoupper($maskaadhaar) . '</td>
        </tr>
        <tr>
            <td></td>
            <td><strong>ii) PAN Card Number</strong></td>
            <td style="text-align:right;">' . strtoupper($maskpancard) . '</td>
        </tr>
        <tr>
            <td></td>
            <td><strong>iii) GST Number</strong></td>
            <td style="text-align:right;">' . strtoupper($maskgst_number) . '</td>
        </tr>
    </table>';


        // Declaration
        $html .= '
        <p class="mt-2">I/We hereby declare that the particulars stated above are correct to the best of my/our
            knowledge and belief.</p>
        <p class="mt-2">I/We hereby declare that I/We have in my/our possession a latest copy of the Indian
            Electricity Rules,
            1956 and that I/We fully understand the terms and conditions under which an Electrical
            Contractor/s licence is granted, breach of which will render the licence liable for cancellation.</p>
        <br>
        <br>
     
    
        <p><strong>Place:</strong> </p>


        <p><strong>Date:</strong> ' . Carbon::parse($form->updated_at)->format('d-m-Y') . '</p>

      
        
        <p style="text-align: right;"><strong>Signature of the Candidate</strong></p>';



        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output PDF
        return response($mpdf->Output('Application_Details_Form_A' . $newApplicationId . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
}
