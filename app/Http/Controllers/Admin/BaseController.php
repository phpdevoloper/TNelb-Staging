<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BaseController extends LaravelController
{
    protected $user;
    protected $userId;
    protected $updatedBy;
    protected $userRole;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user       = Auth::user();
            $this->userId     = Auth::id();
            $this->updatedBy  = optional($this->user)->name ?? 'System';
            $this->userRole   = optional($this->user->role)->name ?? 'Guest';

            View::share('loggedInUser', $this->user);
            View::share('userRole', $this->userRole);

            return $next($request);
        });
    }
}
