<?php

namespace App\Http\Controllers;

use App\Consts\UserConst;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $jobOffers = JobOffer::whereHas('entries', function ($query) {
            $query->where('user_id', Auth::guard(UserConst::GUARD)->user()->id);
        })->get();

        return view('auth.user.dashboard', compact('jobOffers'));
    }
}
