<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function awaiting_approval()
    {
        if (auth()->user()->approved) {
            return redirect('home');
        }

        return view('auth.awaiting_approval');
    }
}
