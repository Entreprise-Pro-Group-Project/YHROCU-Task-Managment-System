<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Retrieve tasks assigned to the current user
        $tasks = Task::where('assigned_staff', Auth::user()->first_name)->get();


        // Pass the tasks variable to the view
        return view('staff.dashboard', compact('tasks'));
    }
}
