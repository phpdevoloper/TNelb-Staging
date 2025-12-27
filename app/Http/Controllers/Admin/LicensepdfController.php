<?php

namespace App\Http\Controllers\Admin;

use App\Models\TnelbApplicantPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class LicensepdfController extends Controller
{
    public function generatePDF($application_id)
    {

        // Fetch application details
        $application = DB::table('tnelb_application_tbl')
        ->where('application_id', $application_id)
        ->first();

        // var_dump($application->applicants_address);die;


        $applicant_photo = TnelbApplicantPhoto::where('application_id', $application_id)->first();


        if ($application && $application->appl_type === 'R') {
            // Renewal application → use tnelb_renewal_license
            $applicant = DB::table('tnelb_application_tbl')
                ->join('tnelb_renewal_license', 'tnelb_renewal_license.application_id', '=', 'tnelb_application_tbl.application_id')
                ->where('tnelb_application_tbl.application_id', $application_id)
                ->select(
                    'tnelb_application_tbl.application_id',
                    'tnelb_application_tbl.applicant_name AS name',
                    'tnelb_application_tbl.fathers_name',
                    'tnelb_application_tbl.applicants_address',
                    'tnelb_application_tbl.d_o_b',
                    'tnelb_application_tbl.age',
                    'tnelb_application_tbl.license_name',
                    'tnelb_application_tbl.form_name',
                    'tnelb_renewal_license.license_number',
                    'tnelb_renewal_license.issued_by',
                    'tnelb_renewal_license.issued_at',
                    'tnelb_renewal_license.expires_at'
                )
                ->first();
        } else {
            // New application → use tnelb_license
            $applicant = DB::table('tnelb_application_tbl')
                ->join('tnelb_license', 'tnelb_license.application_id', '=', 'tnelb_application_tbl.application_id')
                ->where('tnelb_application_tbl.application_id', $application_id)
                ->select(
                    'tnelb_application_tbl.application_id',
                    'tnelb_application_tbl.applicant_name AS name',
                    'tnelb_application_tbl.fathers_name',
                    'tnelb_application_tbl.applicants_address',
                    'tnelb_application_tbl.d_o_b',
                    'tnelb_application_tbl.age',
                    'tnelb_application_tbl.license_name',
                    'tnelb_application_tbl.form_name',
                    'tnelb_license.license_number',
                    'tnelb_license.issued_by',
                    'tnelb_license.issued_at',
                    'tnelb_license.expires_at'
                )
                ->first();
        }
     
    
        if (!$applicant) {
            return back()->with('error', 'Application not found.');
        }
    
        // Fetch Payment Details
        $payment = DB::table('payments')->where('application_id', $application_id)->first();
    
        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [70.80, 110.55],
            'orientation' => 'L',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        $mpdf->SetTitle('TNELB Application License ' . $applicant->license_name);

        
        $mpdf->WriteHTML('<style>
            @page {
                size: 110.55mm 70.80mm;   /* CR100 landscape */
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                width: 110.55mm;
                height: 70.80mm;
                font-family: helvetica;
                overflow: hidden;
            }

            .card {
                width: 110.55mm;
                height: 70.80mm;
                border: 0.4mm solid #000;
                box-sizing: border-box;
            }

            .header {
                height: 11mm;
                color: #003366;
                text-align: center;
                font-size: 10.5pt;
                font-weight: bold;
                padding: 2mm;
                box-sizing: border-box;
            }

            .content {
                padding: 3mm;
                font-size: 7pt;
                box-sizing: border-box;
            }

            .photo {
                width: 22mm;
                height: 22mm;
                border: 0.3mm solid #000;
                box-sizing: border-box;
                overflow: hidden;
            }

            

           .info-table {
                font-size: 9pt;
                border-collapse: collapse;
            }

            .info-table td {
                padding: 1mm;
                vertical-align: top;
            }

            .info-table .lbl {
                width: 30mm;
                font-weight: bold;
            }

            .info-table .colon {
                width: 3mm;
                text-align: center;
            }

            .info-table .val {
                width: auto;
            }

            .footer {
                margin-top: 2mm;   /* ✅ SAFE */
                text-align: center;
                font-size: 6pt;
            }
            </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
                
        $photoPath = !empty($applicant_photo->upload_path) ? public_path($applicant_photo->upload_path): null;

        

        $html = '
        <div class="card">

            <!-- HEADER -->
            <div class="header">
                TAMIL NADU ELECTRICAL LICENCING BOARD<br>
                Thiru Vi. Ka. Indl. Estate, Guindy, Chennai - 600 032.
            </div>

            <!-- BODY -->
            <div class="content">

               <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <!-- LEFT : DETAILS -->
                        <td width="50%" valign="top">

                            <table class="info-table">
                                <tr>
                                    <td class="lbl">C.No</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->license_number.'</td>
                                </tr>
                                 <tr>
                                    <td class="lbl">Validity</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.format_date($applicant->issued_at). '<small class="lbl"> To </small>'. format_date($applicant->expires_at).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">D.O.I</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.date('d M Y', strtotime($applicant->issued_at)).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Name</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->name.'</td>
                                </tr>
                            </table>

                        </td>

                        <!-- RIGHT : PHOTO -->
                        <td width="30%" align="center" valign="top">
                            <div class="photo">
                                '.($photoPath ? '<img src="'.$photoPath.'" style="width:22mm; height:22mm; object-fit:cover;">' : '').'
                            </div>
                        </td>
                    </tr>
                </table>

            </div>

            <!-- FOOTER -->
            <div class="footer">
                Issued by TNELB | Tamil Nadu
            </div>

        </div>
        ';
    
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
    
        // Output PDF
        return response($mpdf->Output('Application_Details.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }


    public function generateFormaPDF($application_id)
    {
        // Fetch application details

        // dd($application_id);
        // exit;
        $application = DB::table('tnelb_ea_applications')->where('application_id', $application_id)->first();

        $appltype = trim($application->appl_type);
       
        if($appltype === 'N'){
                $applicant = DB::table('tnelb_license')
            ->join('tnelb_ea_applications', 'tnelb_license.application_id', '=', 'tnelb_ea_applications.application_id')
            ->where('tnelb_license.application_id', $application_id)
            ->select(
                'tnelb_license.application_id',
                'tnelb_license.issued_by',
                'tnelb_license.issued_at',
                'tnelb_license.expires_at',
                'tnelb_ea_applications.applicant_name AS name',
                // 'tnelb_applicant_formA.fathers_name',
                // 'tnelb_applicant_formA.applicants_address',
                // 'tnelb_applicant_formA.d_o_b',
                // 'tnelb_applicant_formA.age',
                'tnelb_ea_applications.license_name',
                'tnelb_ea_applications.form_name',
                'tnelb_license.license_number'
            )
            ->first();

            $staffDetails = DB::table('tnelb_applicant_formA_staffdetails')
            ->where('application_id', $application_id)
            ->orderby('id')
            // ->where('staff_flag', 1)
            ->get();

        }else{
    $applicant = DB::table('tnelb_renewal_license')
            ->join('tnelb_ea_applications', 'tnelb_renewal_license.application_id', '=', 'tnelb_ea_applications.application_id')
            ->where('tnelb_renewal_license.application_id', $application_id)
            ->select(
                'tnelb_renewal_license.application_id',
                'tnelb_renewal_license.issued_by',
                'tnelb_renewal_license.issued_at',
                'tnelb_renewal_license.expires_at',
                'tnelb_ea_applications.applicant_name AS name',
                // 'tnelb_applicant_formA.fathers_name',
                // 'tnelb_applicant_formA.applicants_address',
                // 'tnelb_applicant_formA.d_o_b',
                // 'tnelb_applicant_formA.age',
                'tnelb_ea_applications.license_name',
                'tnelb_ea_applications.form_name',
                'tnelb_renewal_license.license_number'
            )
            ->first();

            $staffDetails = DB::table('tnelb_applicant_formA_staffdetails')
            ->where('application_id', $application_id)
            ->orderby('id')
            // ->where('staff_flag', 1)
            ->get();

        }
    

    
        if (!$applicant) {
            return back()->with('error', 'Application not found.');
        }
    
        
        $payment = DB::table('payments')->where('application_id', $application_id)->first();
    
        // Initialize mPDF
        $mpdf = new Mpdf(['default_font_size' => 10]);
        $mpdf->SetTitle('TNELB Application License ' . $applicant->license_name);

        
        $mpdf->WriteHTML('<style>
        body {  }
        p, td, th { padding: 0px; }
        p 
        .tbl_center { text-align: center; }
        .mt-2 { margin-top: 5; }
        table { border-collapse: collapse; width: 100%; }
        th, td {  padding: 8px; text-align:left; }
        .highlight { font-weight: bold; color: white; background-color: green; padding: 5px; text-align:center; font-size:16px; }
        .photo-container { text-align: right; padding-right: 10px; }
        .photo-container img { width: 132px; height: 170px; border: 1px solid #000; object-fit: cover; display: block; }
        .highlight_text{color:green;}
        .staff_tbl td{text-align:center;}
    </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
    
        // Start building the PDF content
        $html = '
        <h3 style="text-align: center;" class="">GOVERNMENT OF TAMILNADU</h3>
        <h4 style="text-align: center;" class="">THE ELECTRICAL LICENSING BOARD</h4>
        <p style="text-align: center;">Thiru.Vi.Ka.Indl.Estate, Guindy, Chennai – 600032.</p>
        <h4 style="text-align: center;" class="highlight_text"> Form ' . $applicant->form_name . ' License "' . $applicant->license_name . '"</h4>
        <h3  style="text-align: center;"><strong>License for Contractor Certificate</strong></h3>
        <h3 style="text-align: center;" class=""> License Number : <span class = "highlight_text">' . $applicant->license_number . '</span></h3>';
    
     if($appltype === 'N') 
     {
        $apply_type= "Fresh Application";
     }else{
        $apply_type= "Renewal Application";
     }

    
        $html .= '
        <h4 class="mt-2 highlight"> License Summary</h4>
        <table>
            <tr><th class="">Applicantion ID</th><td>' . $applicant->application_id . '</td></tr>
            <tr><th class="">Name of Electrical Contractor/s <br> licence  applied for </th><td>' . $applicant->name . '</td></tr>
            <tr><th class="">License Name</th><td>' . $applicant->license_name . '</td></tr>
            <tr><th class="">License Type</th><td>' . $apply_type . '</td></tr>
            <tr><th class="">Issued By</th><td>' . $applicant->issued_by . '</td></tr>
            <tr><th class="">Issued At</th><td>' . date('d-m-Y', strtotime($applicant->issued_at)) . '</td></tr>
            <tr><th class="">Expired At</th><td>' . date('d-m-Y', strtotime($applicant->expires_at)) . '</td></tr>
        </table>';

       $html .= '
<h4 class="mt-2 highlight">Details of Staff appointed under this Contractor License</h4>
<table class="staff_tbl" border="1">
    <tr>
        <th>S.No</th>
        <th>Staff Name</th>
        <th>Qualification </th>
        <th>Category </th>
        <th>Competency Certificate Number & Validity </th>
    </tr>';

if ($staffDetails->count() > 0) {
    $i = 1;
    foreach ($staffDetails as $staff) {
        $html .= '
        <tr>
            <td>' . $i++ . '</td>
            <td>' . strtoupper($staff->staff_name) . '</td>
            <td>' . strtoupper($staff->staff_qualification) . '</td>
            <td>' . strtoupper($staff->staff_category) . '</td>
            <td>' . $staff->cc_number . ', ' . (!empty($staff->cc_validity) ? date('d-m-Y', strtotime($staff->cc_validity)) : 'N/A') . '</td>

        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center;">No staff found</td></tr>';
}

$html .= '</table>';

    
        
        // $html .= '<h4 class="mt-2 highlight"> Payment Details</h4>
        // <table class="tbl_center bank">
        //     <tr>
        //         <th class="">Bank Name</th>
        //         <th class="">Mode of Payment</th>
        //         <th class="">Amount</th>
        //         <th class="">Payment Date</th>
        //         <th class="">Transaction ID</th>
        //     </tr>
        //     <tr>
        //         <td>State Bank of India</td>
        //         <td>UPI</td>
        //         <td>' . ($payment->amount ?? 'N/A') . '</td>
        //         <td>25-02-2025</td>
        //         <td>' . ($payment->transaction_id ?? 'N/A') . '</td>
        //     </tr>
        // </table>';
    
        // Declaration
        $html .= '
        <br>
       
        <p><strong>Date:</strong> ' . date('d-m-Y', strtotime($applicant->issued_at)) . '</p>';
    
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
    
        // Output PDF
        return response($mpdf->Output('License_A_approval.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    


    public function generateFormaPDF1($application_id)
    {
         $application = DB::table('tnelb_ea_applications')
        ->where('application_id', $application_id)
        ->first();
      
       $appl_type = preg_replace('/\s+/', '', $application->appl_type);

        if ($application && $appl_type === 'R') {
            // Renewal application → use tnelb_renewal_license
            $applicant = DB::table('tnelb_ea_applications')
                ->join('tnelb_renewal_license', 'tnelb_renewal_license.application_id', '=', 'tnelb_ea_applications.application_id')
                ->where('tnelb_ea_applications.application_id', $application_id)
                ->select(
                   'tnelb_renewal_license.application_id',
                'tnelb_renewal_license.issued_by',
                'tnelb_renewal_license.issued_at',
                'tnelb_renewal_license.expires_at',
                'tnelb_ea_applications.applicant_name AS name',
                // 'tnelb_applicant_formA.fathers_name',
                // 'tnelb_applicant_formA.applicants_address',
                // 'tnelb_applicant_formA.d_o_b',
                // 'tnelb_applicant_formA.age',
                'tnelb_ea_applications.license_name',
                'tnelb_ea_applications.form_name',
                'tnelb_renewal_license.license_number'
                )
                ->first();
        } else {
        // Fetch application details
        $applicant = DB::table('tnelb_license')
            ->join('tnelb_ea_applications', 'tnelb_license.application_id', '=', 'tnelb_ea_applications.application_id')
            ->where('tnelb_license.application_id', $application_id)
            ->select(
                'tnelb_license.application_id',
                'tnelb_license.issued_by',
                'tnelb_license.issued_at',
                'tnelb_license.expires_at',
                'tnelb_ea_applications.applicant_name AS name',
                // 'tnelb_applicant_formA.fathers_name',
                // 'tnelb_applicant_formA.applicants_address',
                // 'tnelb_applicant_formA.d_o_b',
                // 'tnelb_applicant_formA.age',
                'tnelb_ea_applications.license_name',
                'tnelb_ea_applications.form_name',
                'tnelb_license.license_number'
            )
            ->first();
        }
    
        if (!$applicant) {
            return back()->with('error', 'Application not found.');
        }
    
        
        $payment = DB::table('payments')->where('application_id', $application_id)->first();
    
        // Initialize mPDF
        $mpdf = new Mpdf(['default_font_size' => 10]);
        $mpdf->SetTitle('TNELB Application License ' . $applicant->license_name);

        
        $mpdf->WriteHTML('<style>
        body { line-height: 1.5; }
        p, td, th { padding: 5px; }
        .tbl_center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; }
        .highlight { font-weight: bold; color: white; background-color: green; padding: 5px; }
        .photo-container { text-align: right; padding-right: 10px; }
        .photo-container img { width: 132px; height: 170px; border: 1px solid #000; object-fit: cover; display: block; }
    </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
    
        // Start building the PDF content
        $html = '
        <h3 style="text-align: center;" class="">GOVERNMENT OF TAMILNADU</h3>
        <h4 style="text-align: center;" class="">THE ELECTRICAL LICENSING BOARD</h4>
        <p style="text-align: center;">Thiru.Vi.Ka.Indl.Estate, Guindy, Chennai – 600032.</p>
        <h4 style="text-align: center;" class=""> Form ' . $applicant->form_name . ' License "' . $applicant->license_name . '"</h4>
        <p style="text-align: center;">License for Contractor Certificate</p>
        <h3 style="text-align: center;" class="">' . $applicant->license_number . '</h3>';
    
    

    
        $html .= '
        <h4 class="mt-2 "> License Summary</h4>
        <table>
            <tr><th class="highlight">Applicant ID</th><td>' . $applicant->application_id . '</td></tr>
            <tr><th class="highlight">Name</th><td>' . $applicant->name . '</td></tr>
            <tr><th class="highlight">License Name</th><td>' . $applicant->license_name . '</td></tr>
            <tr><th class="highlight">Issued By</th><td>' . $applicant->issued_by . '</td></tr>
            <tr><th class="highlight">Issued At</th><td>' . $applicant->issued_at . '</td></tr>
            <tr><th class="highlight">Expired At</th><td>' . $applicant->expires_at . '</td></tr>
        </table>';
    
        
        $html .= '<h4 class="mt-2 "> Payment Details</h4>
        <table class="tbl_center">
            <tr>
                <th class="highlight">Bank Name</th>
                <th class="highlight">Mode of Payment</th>
                <th class="highlight">Amount</th>
                <th class="highlight">Payment Date</th>
                <th class="highlight">Transaction ID</th>
            </tr>
            <tr>
                <td>State Bank of India</td>
                <td>UPI</td>
                <td>' . ($payment->amount ?? 'N/A') . '</td>
                <td>25-02-2025</td>
                <td>' . ($payment->transaction_id ?? 'N/A') . '</td>
            </tr>
        </table>';
    
        // Declaration
        $html .= '
        <br>
        <p><strong>Place:</strong> Chennai</p>
        <p><strong>Date:</strong> ' . date('d-m-Y') . '</p>';
    
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
    
        // Output PDF
        return response($mpdf->Output('Application_Details.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
    
}
