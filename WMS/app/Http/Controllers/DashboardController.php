<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();
        if ($user->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        } 
        elseif ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }
}