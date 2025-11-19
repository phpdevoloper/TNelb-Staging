<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormPController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('web');
    }

    public function apply_form_p()
    {
        if (!Auth::check()) {
            return redirect()->route('logout');
        }
        $authUser = Auth::user();

        $user = [
            'user_id' => $authUser->login_id,
            'salutation' => $authUser->salutation,
            'applicant_name' => $authUser->first_name.' '.$authUser->last_name,
        ];

        return view('user_login.apply-form-p', compact('user'));
    }
}
