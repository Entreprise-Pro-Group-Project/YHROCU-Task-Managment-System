<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Retrieve tasks assigned to the current user
        $tasks = Task::where('assigned_staff', auth()->user()->first_name)->get();


        // Pass the tasks variable to the view
        return view('staff.dashboard', compact('tasks'));
    }
}
