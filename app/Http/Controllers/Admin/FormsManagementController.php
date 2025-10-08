<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Admin\TnelbForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use Carbon\Carbon;

class FormsManagementController extends BaseController
{

    public function index(){
        return view('admincms.forms.forms');
    }

}
