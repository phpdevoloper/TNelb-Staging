<?php

namespace App\Http\Controllers\Admin;

use App\Models\TnelbApplicantPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class LicensepdfController extends Controller
{
    public function __construct()
    {
        
    }

    public function getLicenceDoc($application_id)
    {
        // Fetch application details
        $application = DB::table('tnelb_application_tbl')
        ->where('application_id', $application_id)
        ->first();

        

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
        $mpdf = new Mpdf(['default_font_size' => 10]);
        $mpdf->SetTitle('TNELB Application License ' . $applicant->license_name);

        
        $mpdf->WriteHTML('<style>
        body { line-height: 1.5; }
        p, td, th { padding: 5px; }
        .tbl_center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 8px; }
        .highlight { font-weight: bold; color: black; background-color: #ddbe12; padding: 5px; }
        .photo-container { text-align: right; padding-right: 10px; }
        .photo-container img { width: 132px; height: 170px; border: 1px solid #000; object-fit: cover; display: block; }
    </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
    
        // Start building the PDF content
        $html = '
        <h3 style="text-align: center;" class="">GOVERNMENT OF TAMILNADU</h3>
        <h4 style="text-align: center;" class="">THE ELECTRICAL LICENSING BOARD</h4>
        <p style="text-align: center;">Thiru.Vi.Ka.Indl.Estate, Guindy, Chennai – 600032.</p>
        <h4 style="text-align: center;" class="">' . $applicant->form_name . ' License "' . $applicant->license_name . '"</h4>
        <p style="text-align: center;">License for Supervisor Competency Certificate</p>
        <h3 style="text-align: center;" class="">' . $applicant->license_number . '</h3>';
 

    
        $html .= '
        <h4 class="mt-2 "> License Summary</h4>
        <table>
            <tr><th class="highlight">Applicant ID</th><td>' . $applicant->application_id . '</td></tr>
            <tr><th class="highlight">Name</th><td>' . $applicant->name . '</td></tr>
            <tr><th class="highlight">License Name</th><td>' . $applicant->license_name . '</td></tr>
            <tr><th class="highlight">Issued By</th><td>' . $applicant->issued_by . '</td></tr>
            <tr><th class="highlight">Issued On</th><td>' . format_date($applicant->issued_at) . '</td></tr>
            <tr><th class="highlight">Expired On</th><td>' . format_date($applicant->expires_at) . '</td></tr>
        </table>';
    
        // Payment Details
        $html .= '<h4 class="mt-2 "> Payment Details</h4>
        <table class="tbl_center">
            <tr>
                <th class="highlight">Bank Name</th>
                <th class="highlight">Mode of Payment</th>
                <th class="highlight">Payment Date</th>
                <th class="highlight">Transaction ID</th>
            </tr>
            <tr>
                <td>State Bank of India</td>
                <td>UPI</td>
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

    public function generatePDF($application_id)
    {
        $application = DB::table('tnelb_application_tbl')
        ->where('application_id', $application_id)
        ->first();
        $applicant_photo = TnelbApplicantPhoto::where('application_id', $application_id)->first();
        if ($application && $application->appl_type === 'R') {
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

        if($applicant->license_name == 'B'){
            $certificate_name = 'Electrician';
            $content_text = 'இச்சான்று பெற்றவர் நடுத்தர மற்றும் குறைந்த மின்னழுத்த மின்னமைப்பு பணிகளை உரிமம் பெற்றுரியின் ஒப்பந்தக்காரரின் கீழ் மேற்கொள்ளலாம் அல்லது நடுத்தர மற்றும் குறைந்த அழுத்த நிறுவனத்தின் இயக்குதல் மற்றும் பராமரிப்பு பணிகளை அந்நிறுவனத்தில் அங்கீகாரத்துடன் மேற்கொள்ளலாம்.'; 
        }else if($applicant->license_name == 'H'){
             $certificate_name = 'WIREMAN HELPER';
            $content_text = 'இச்சான்று பெற்றவர் நடுத்தர மற்றும் குறைந்த மின்னழுத்த அமைப்பு பணிகளை மேற்கோள்வதில் உரிமம் பெற்ற மின் ஒப்பந்தக்காரரிடம் மின் கம்பியாளருக்கு உதவியாளராக பணிபுரியலாம். அல்லது நடுத்தர மற்றும் குறைந்த அழுத்த நிறுவனத்தின் இயக்குதல் மற்றும் பராமரிப்பு பணியில் மின்கம்பியாளருக்கு உதவியாளராக நிறுவனத்தின் அங்கீகாரத்துடன் மேற்கொள்ளலாம்.'; 
        }else{
            $certificate_name = '';
            $content_text = 'இச்சான்றிதழ் பெற்றவர், உரிமம் பெற்ற மின் ஒப்பந்தக்காரரின் கீழ் உயர் மின்னழுத்த (H.V) மற்றும் நடுத்தர மின்னழுத்த (M.V) மின் நிறுவல் பணிகளை மேற்பார்வை செய்ய அனுமதிக்கப்படுகிறார்; அல்லது இந்திய மின்சார விதிகள், 1956 இன் விதி 3 இன் கீழ் அங்கீகரிக்கப்பட்ட நபராக பணியாற்ற அனுமதிக்கப்படுகிறார்.';
        }


         $certificateRow = '';

        if (!empty($certificate_name)) {
            $certificateRow = '
                <tr>
                    <td class="lbl">தே.சான்று எண்</td>
                    <td class="colon">:</td>
                    <td class="val">'.$certificate_name.'</td>
                </tr>
            ';
        }

        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => [80.80, 120.55],
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
                width: 120.55mm;
                height: 80.80mm;
                font-family: helvetica;
                overflow: hidden;
            }
            .card {
                width: 120.55mm;
                height: 80.80mm;
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
                width: 25mm;
                font-weight: bold;
            }

            .info-table .colon {
                width: 2mm;
                text-align: center;
            }
            .footer {
                margin-top: 10mm;   
                text-align: center;
                font-size: 6pt;
            }
            </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
                
        $photoPath = !empty($applicant_photo->upload_path) ? public_path($applicant_photo->upload_path): null;

        $qrValue = 'TNELB QR TESTING'; 

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
                        <td width="70%" valign="top">

                            <table class="info-table">
                                <tr>
                                    <td class="lbl">C.No</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->license_number.'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">D.O.I</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.date('d M Y', strtotime($applicant->issued_at)).'</td>
                                </tr>
                                 <tr>
                                    <td class="lbl">Validity</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.format_date($applicant->issued_at). '<small style="font-weight: bold;"> To </small>'. format_date($applicant->expires_at).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Name</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->name.'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">F/H Name</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->fathers_name.'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Date of Birth</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.date('d M Y', strtotime($applicant->d_o_b)).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Address</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->applicants_address.'</td>
                                </tr>
                            </table>

                        </td>

                        <!-- RIGHT : PHOTO -->
                        <td width="30%" valign="top">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <!-- PHOTO ROW -->
                                <tr>
                                    <td align="center">
                                        <div class="photo">
                                            '.($photoPath
                                                ? '<img src="'.$photoPath.'" style="width:22mm; height:22mm; object-fit:cover;">'
                                                : '').'
                                        </div>
                                    </td>
                                </tr>

                                <!-- SPACE BETWEEN PHOTO & QR -->
                                <tr>
                                    <td height="3mm"></td>
                                </tr>

                                <!-- QR ROW -->
                                <tr>
                                    <td align="center">
                                        <barcode code="'.$qrValue.'" type="QR" size="0.6" error="M" />
                                    </td>
                                </tr>

                                <!-- BOTTOM SAFE SPACE -->
                                <tr>
                                    <td height="4mm"></td>
                                </tr>
                            </table>
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
    
        $mpdf->WriteHTML($html);
        $mpdf->AddPage('L');
        $backHtml = '
            <div class="card">

                <div class="content" style="font-size:9.5pt; line-height:1.4;">

                    <div style="text-align:right; font-size:7pt; margin-bottom:2mm;">
                        Visit us at : www.tnelb.gov.in
                    </div>

                    <div style="margin-top:4mm;">
                        This Certificate holder is permitted to supervise
                        <strong>H.V and M.V. Electrical installation works</strong>
                        under licensed contractor or to work as authorised person
                        under rule 3 of Indian Electricity Rule 1956.
                    </div>

                    <br><br><br><br>

                    <!-- SIGNATURE AREA -->
                    <table width="100%" style="margin-top:15mm;">
                        <tr>
                            <td width="45%" style="text-align:left;">
                                <div style="height:12mm;"></div>
                                <strong>Secretary</strong>
                            </td>

                            <td width="55%" style="text-align:right;">
                                <div style="height:12mm;"></div>
                                <strong>President</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>';
        $mpdf->WriteHTML($backHtml);
        return response($mpdf->Output('Application_Details.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function generateLicenceTamil($application_id)
    {
        $application = DB::table('tnelb_application_tbl')
        ->where('application_id', $application_id)
        ->first();
        $applicant_photo = TnelbApplicantPhoto::where('application_id', $application_id)->first();
        if ($application && $application->appl_type === 'R') {
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

        if($applicant->license_name == 'B'){
            $certificate_name = 'Electrician';
            $content_text = 'இச்சான்று பெற்றவர் நடுத்தர மற்றும் குறைந்த மின்னழுத்த மின்னமைப்பு பணிகளை உரிமம் பெற்றுரியின் ஒப்பந்தக்காரரின் கீழ் மேற்கொள்ளலாம் அல்லது நடுத்தர மற்றும் குறைந்த அழுத்த நிறுவனத்தின் இயக்குதல் மற்றும் பராமரிப்பு பணிகளை அந்நிறுவனத்தில் அங்கீகாரத்துடன் மேற்கொள்ளலாம்.'; 
        }else if($applicant->license_name == 'H'){
             $certificate_name = 'WIREMAN HELPER';
            $content_text = 'இச்சான்று பெற்றவர் நடுத்தர மற்றும் குறைந்த மின்னழுத்த அமைப்பு பணிகளை மேற்கோள்வதில் உரிமம் பெற்ற மின் ஒப்பந்தக்காரரிடம் மின் கம்பியாளருக்கு உதவியாளராக பணிபுரியலாம். அல்லது நடுத்தர மற்றும் குறைந்த அழுத்த நிறுவனத்தின் இயக்குதல் மற்றும் பராமரிப்பு பணியில் மின்கம்பியாளருக்கு உதவியாளராக நிறுவனத்தின் அங்கீகாரத்துடன் மேற்கொள்ளலாம்.'; 
        }else{
            $certificate_name = '';
            $content_text = 'இச்சான்றிதழ் பெற்றவர், உரிமம் பெற்ற மின் ஒப்பந்தக்காரரின் கீழ் உயர் மின்னழுத்த (H.V) மற்றும் நடுத்தர மின்னழுத்த (M.V) மின் நிறுவல் பணிகளை மேற்பார்வை செய்ய அனுமதிக்கப்படுகிறார்; அல்லது இந்திய மின்சார விதிகள், 1956 இன் விதி 3 இன் கீழ் அங்கீகரிக்கப்பட்ட நபராக பணியாற்ற அனுமதிக்கப்படுகிறார்.';
        }

        $certificateRow = '';

        if (!empty($certificate_name)) {
            $certificateRow = '
                <tr>
                    <td class="lbl">தே.சான்று எண்</td>
                    <td class="colon">:</td>
                    <td class="val">'.$certificate_name.'</td>
                </tr>
            ';
        }

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => array_merge($fontData, [
                'notosanstamil' => [
                    'R' => 'NotoSansTamil-Regular.ttf',
                ]
            ]),
            'default_font' => 'notosanstamil',
            'format' => [80.80, 120.55],
            'orientation' => 'L',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);


        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;


        $mpdf->SetTitle('TNELB Application License ' . $applicant->license_name);
        $mpdf->WriteHTML('<style>
            @page {
                size: 120.55mm 80.80mm;   /* CR100 landscape */
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                width: 120.55mm;
                height: 80.80mm;
                font-family: notosanstamil;
                overflow: hidden;
            }
            .card {
                width: 120.55mm;
                height: 80.80mm;
                border: 0.4mm solid #000;
                box-sizing: border-box;
            }
            .header {
                height: 11mm;
                color: #003366;
                text-align: center;
                font-size: 12.5pt;
                font-weight: bold;
                padding: 1mm;
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
                width: 25mm;
                font-weight: bold;
            }

            .info-table .colon {
                width: 2mm;
                text-align: center;
            }
            .footer {
                margin-top: 2mm;   /* ✅ SAFE */
                text-align: center;
                font-size: 6pt;
            }
            </style>', \Mpdf\HTMLParserMode::HEADER_CSS);
                
        $photoPath = !empty($applicant_photo->upload_path) ? public_path($applicant_photo->upload_path): null;

        $qrValue = 'sdfdgsdg'; 

        $html = '
        <div class="card">

            <!-- HEADER -->
            <div class="header">
                தமிழ்நாடு மின் உரிமம் வழங்கும் வாரியம்<br>
                திரு.வி.க. தொழிற்பேட்டை, கிண்டி, சென்னை – 32.
            </div>

            <!-- BODY -->
            <div class="content">

               <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <!-- LEFT : DETAILS -->
                        <td width="70%" valign="top">

                            <table class="info-table">
                                <tr>
                                    <td class="lbl">த. சா. எண்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->license_number.'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">வ.நாள்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.date('d M Y', strtotime($applicant->issued_at)).'</td>
                                </tr>
                                 <tr>
                                    <td class="lbl">செ.கா</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.format_date($applicant->issued_at). '<small style="font-weight: bold;"> To </small>'. format_date($applicant->expires_at).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">பெயர்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->name.'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">த / க பெயர்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->fathers_name.'</td>
                                </tr>
                                 <tr>
                                    <td class="lbl">பிறந்த நாள்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.date('d M Y', strtotime($applicant->d_o_b)).'</td>
                                </tr>
                                <tr>
                                    <td class="lbl">விலாசம்</td>
                                    <td class="colon">:</td>
                                    <td class="val">'.$applicant->applicants_address.'</td>
                                </tr>'
                                .$certificateRow.'
                            </table>

                        </td>

                        <!-- RIGHT : PHOTO -->
                        <td width="30%" valign="top">
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <!-- PHOTO ROW -->
                                <tr>
                                    <td align="center">
                                        <div class="photo">
                                            '.($photoPath
                                                ? '<img src="'.$photoPath.'" style="width:22mm; height:22mm; object-fit:cover;">'
                                                : '').'
                                        </div>
                                    </td>
                                </tr>

                                <!-- SPACE BETWEEN PHOTO & QR -->
                                <tr>
                                    <td height="3mm"></td>
                                </tr>

                                <!-- QR ROW -->
                                <tr>
                                    <td align="center">
                                        <barcode code="'.$qrValue.'" type="QR" size="0.6" error="M" />
                                    </td>
                                </tr>

                                <!-- BOTTOM SAFE SPACE -->
                                <tr>
                                    <td height="3mm"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>

            <!-- FOOTER -->
            <div class="footer">
                Issued by TNELB | தமிழ்நாடு
            </div>

        </div>
        ';
    
        $mpdf->WriteHTML($html);
        $mpdf->AddPage('L');
        $backHtml = '
            <div class="card">

                <div class="content" style="font-size:9.5pt; line-height:1.4;">

                    <div style="text-align:right; font-size:7pt; margin-bottom:2mm;">
                        Visit us at : www.tnelb.gov.in
                    </div>

                    <div style="margin-top:4mm; text-align: justify;">
                        ' . $content_text . '   
                    </div>

                    <br><br><br><br>

                    <!-- SIGNATURE AREA -->
                    <table width="100%" style="margin-top:6mm;">
                        <tr>
                            <td width="45%" style="text-align:left;">
                                <div style="height:12mm;"></div>
                                <strong>செயலாளர்</strong>
                            </td>

                            <td width="55%" style="text-align:right;">
                                <div style="height:12mm;"></div>
                                <strong>தலைவர்</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>';
        $mpdf->WriteHTML($backHtml);
        return response($mpdf->Output('Application_Details.pdf', 'I'))->header('Content-Type', 'application/pdf');
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
