<?php

use App\Http\Controllers\Admin\LicenceManagementController;
use App\Http\Controllers\Admin\LicensepdfController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\EA_RenewalController;
use App\Http\Controllers\FormAController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormPController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PDFFormAController;
use App\Http\Controllers\PDFRenewalController;
use App\Http\Controllers\RegisterController;
use App\Models\Mst_documents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mews\Captcha\Facades\Captcha as FacadesCaptcha;
use Mews\Captcha\Facades\Captcha;

// ------------------------ Public Pages ------------------------



Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/home', [IndexController::class, 'index'])->name('index');
Route::get('/about', [IndexController::class, 'about'])->name('about');
Route::get('/vision', [IndexController::class, 'vision'])->name('vision');
Route::get('/mission', [IndexController::class, 'mission'])->name('mission');
Route::get('/future-scenario', [IndexController::class, 'future_scenario'])->name('future_scenario');
Route::get('/members', [IndexController::class, 'members'])->name('members');
Route::get('/rules', [IndexController::class, 'rules'])->name('rules');
Route::get('/services-and-standards', [IndexController::class, 'services_and_standards'])->name('services-and-standards');
Route::get('/complaints', [IndexController::class, 'complaints'])->name('complaints');
Route::get('/contact', [IndexController::class, 'contact'])->name('contact');


Route::get('/WebsitePolicies', [IndexController::class, 'WebsitePolicies'])->name('WebsitePolicies');
Route::get('/help', [IndexController::class, 'help'])->name('help');
Route::get('/feedback', [IndexController::class, 'feedback'])->name('feedback');

// ------------------------ Auth: Register/Login ------------------------

Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/loginpage', [RegisterController::class, 'login'])->name('loginpage');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'check'])->name('login.check');
Route::post('/login/verify', [LoginController::class, 'verifyOtp'])->name('login.verify');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ------------------------ Finalize Login (redirect after OTP) ------------------------

Route::get('/finalize-login', function () {
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Session expired. Please login again.');
    }
    return redirect()->route('dashboard');
})->name('finalize.login');

// ------------------------ Dashboard (Protected) ------------------------

Route::get('/dashboard', [LoginController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware('auth.dashboard');

// ------------------------ Captcha ------------------------

Route::get('captcha/image', function () {
    return FacadesCaptcha::create();
});

Route::get('/reload-captcha', [LoginController::class, 'reloadCaptcha']);

// ------------------------ Protected Routes (Logged-in Users Only) ------------------------

Route::middleware(['auth'])->group(function () {
    Route::get('/user_login', [RegisterController::class, 'user_login'])->name('user_login');
    Route::get('/apply-form-s', [RegisterController::class, 'apply_form_s'])->name('apply-form-s');
    Route::get('/apply-form-w', [RegisterController::class, 'apply_form_w'])->name('apply-form-w');
    Route::get('/apply-form-wh', [RegisterController::class, 'apply_form_wh'])->name('apply-form-wh');
    Route::get('/apply_form_p', [FormPController::class, 'apply_form_p'])->name('apply_form_p');
    Route::get('/apply-form-a', [RegisterController::class, 'apply_form_a'])->name('apply-form-a');
    Route::get('/successpage', [RegisterController::class, 'successpage'])->name('successpage');
    Route::get('/editApplication/{application_id}', [FormController::class, 'editApplication'])->name('edit-application');
    Route::get('/editApplication_p/{application_id}', [FormPController::class, 'editApplication'])->name('edit-application_p');
    Route::get('/renew_form/{application_id}', [RegisterController::class, 'renew_form'])->name('renew_form');
    Route::get('/renew-form_ea/{application_id}', [EA_RenewalController::class, 'renew_form_ea'])->name('renew-form_ea');
    Route::get('/document/{type}/{filename}', [FormController::class, 'showEncryptedDocument'])->name('document.show');
    Route::post('/delete_education', [FormController::class, 'delete_education'])->name('delete_education');
    Route::post('/delete_experience', [FormController::class, 'delete_experience'])->name('delete_experience');
    Route::post('/delete_institute', [FormPController::class, 'delete_institute'])->name('delete_institute');
    
    // ------------------------------------form A------------
    // Route::get('/renew_form/{application_id}', [EA_RenewalController::class, 'renew_form'])->name('renew_form');
    Route::get('/renew-form_ea/{application_id}', [EA_RenewalController::class, 'renew_form_ea'])->name('renew-form_ea');
    
    // ------------draft form-----------------------------
    Route::get('/apply-form-a_draft/{application_id}', [EA_RenewalController::class, 'edit'])->name('apply-form-a_draft');
    
    Route::get('/apply-form-a_renewal_draft/{application_id}', [EA_RenewalController::class, 'edit_renewaldraft'])->name('apply-form-a_renewal_draft');
    

    //New Modified Routes added here 
    Route::get('forms/new_application/{form_code}', [FormController::class, 'new_application'])->name('forms.new_application');
    
    Route::post('licences/getPaymentDetails', [LicenceManagementController::class, 'getPaymentDetails'])->name('licences.getPaymentDetails');
    Route::post('/licences/getFormInstruction', [LicenceManagementController::class, 'getFormInstruction'])->name('licences.getFormInstruction');

    
});

// ------------------------ Form Submit & PDF Routes ------------------------
Route::post('/form/store', [FormController::class, 'store'])->name('form.store');
Route::post('/form/draft_submit/{appl_id}', [FormController::class, 'draft_submit'])->name('form.draft_submit');
Route::post('/form/draft_update/{appl_id}', [FormController::class, 'draft_update'])->name('form.draft-update');
Route::post('/form/draft_submit', [FormController::class, 'draft_submit'])->name('form.draft_submit');
Route::post('/form/draft_renewal_submit/{appl_id}', [FormController::class, 'draft_renewal_submit'])
    ->name('form.draft_renewal_submit');

Route::post('/form/update/{appl_id}', [FormController::class, 'update'])->name('form.update');
Route::post('/form_p/update', [FormPController::class, 'update'])->name('form_p.update');
Route::post('/forma/store', [FormAController::class, 'store'])->name('forma.store');


Route::post('/forma/storerenewal', [FormAController::class, 'storerenewal'])->name('forma.storerenewal');

Route::put('/forma/update/{appl_id}', [FormAController::class, 'update'])->name('forma.update');

Route::get('/generate-pdf/{login_id}', [PDFController::class, 'generatePDF'])->name('generate.pdf');
Route::get('/generateTamilPDF/{login_id}', [PDFController::class, 'generateTamilPDF'])->name('generate.tamil.pdf');

Route::get('/generatea-pdf/{login_id}', [PDFFormAController::class, 'generateaPDF'])->name('generatea.pdf');
Route::get('/generateaTamilPDF/{login_id}', [PDFFormAController::class, 'generateaTamilPDF'])->name('generatea.tamil.pdf');

// ------------------------ Dynamic Form Access ------------------------

Route::get('/apply-form/{form_name}/{application_id}', [RegisterController::class, 'apply_form'])->name('apply-form');
Route::get('/payment-receipt/{loginId}', [PDFController::class, 'downloadPaymentReceipt'])->name('paymentreceipt.pdf');




Route::get('/index', [LicenseController::class, 'index'])->name('previous_licenses');
Route::post('/verifylicense', [LicenseController::class, 'verifylicense'])->name('verifylicense');

Route::post('/verifylicenseformAcc', [LicenseController::class, 'verifylicenseformAcc'])->name('verifylicenseformAcc');


Route::post('/verifylicenseformAea', [LicenseController::class, 'verifylicenseformAea'])->name('verifylicenseformAea');

// ----------------------Renuwal PDF------------------------

Route::get('/generaterenewal-pdf/{login_id}', [PDFRenewalController::class, 'generaterenewalPDF'])->name('generaterenewal.pdf');
Route::get('/generaterenewalTamilPDF/{login_id}', [PDFRenewalController::class, 'generaterenewalTamilPDF'])->name('generaterenewal.tamil.pdf');
Route::get('/generatePDFFormP/{login_id}', [PDFController::class, 'generatePDFFormP'])->name('generatePDFFormP.pdf');

// Route::get('/admin/generate-pdf/{application_id}', [LicensepdfController::class, 'generatePDF'])->name('generate.pdf');

// ----------------------Payment------------------------

Route::post('/payment/updatePayment', [PaymentController::class, 'updatePayment'])->name('payment.updatePayment');
Route::post('/payment/updatePaymentFormP', [PaymentController::class, 'updatePaymentFormP'])->name('payment.updatePaymentFormP');

//Testing Pages 

Route::get('/index_bk', function () {
    return view('user_login.index_bk');
});


// -----------verify------------------------------------

Route::post('/verifylicenseformAccc', [LicenseController::class, 'verifylicenseformAccc'])->name('verifylicenseformAccc');


Route::post('/verifylicenseformAea_appl', [LicenseController::class, 'verifylicenseformAea_appl'])->name('verifylicenseformAea_appl');


Route::post('/verifylicensecc_slicense', [LicenseController::class, 'verifylicensecc_slicense'])->name('verifylicensecc_slicense');


// -----------update -payment status after payment initiation------------

Route::post('/update-payment-status', [FormAController::class, 'updatePaymentStatus']);


// ---------------instructions------------------

Route::get('/get-form-instructions', [FormAController::class, 'getFormInstructions']);

// ---------expiry date change-----------------



Route::get('/expiry_date_change', [FormAController::class, 'expiry_date_change'])->name('expiry_date_change');



Route::get('/get-license-expiry/{license_number}', function ($license_number) {
    $license = DB::table('tnelb_license')
        ->where('license_number', $license_number)
        ->select('expires_at')
        ->first();
// dd($license);
    if ($license) {
        return response()->json(['expires_at' => $license->expires_at]);
    }
    return response()->json(['expires_at' => null], 404);
});


// -----------------

Route::post('/update-license-expiry', function (Illuminate\Http\Request $request) {
    $request->validate([
        'license_number' => 'required|string',
        'expires_at'     => 'required|date',
    ]);

    $updated = DB::table('tnelb_license')
        ->where('license_number', $request->license_number)
        ->update([
            'expires_at' => $request->expires_at,
            'updated_at'  => now()
        ]);

    if ($updated) {
        return response()->json(['status' => 'success', 'message' => 'Expiry date updated successfully']);
    }
    return response()->json(['status' => 'error', 'message' => 'License not found'], 404);
})->name('update-license-expiry');


Route::post('/updateCurrentDate', function (Illuminate\Http\Request $request) {
    $request->validate([
        'current_date' => 'required|date',
    ]);

    $updated = DB::table('tnelb_license')
        ->where('license_number', 'LCS000001')
        ->update([
            'sample_date' => $request->current_date,
        ]);

    if ($updated) {
        return response()->json(['status' => 'success', 'message' => 'date changed successfully']);
    }
    return response()->json(['status' => 'error', 'message' => 'License not found'], 404);
})->name('updateCurrentDate');




// -----newsboarccontent---------------------------
Route::get('/noticeboardcontent/{news_id}', [LoginController::class, 'noticeboardcontent'])->name('noticeboardcontent');


// --------------------------------propertior-------------

// Route::delete('/proprietor/delete/{id}', [PropertiorController::class, 'deleteProprietor']);

// Route::post('/proprietor/update/{id}', [PropertiorController::class, 'updateProprietor'])->name('admin.proprietor.update');

Route::get('/form/get-form-cost', [FormController::class, 'getFormCost'])->name('getFormCost');


//Form P submit Routes
Route::post('/form_p/store', [FormPController::class, 'store'])->name('form_p.store');




