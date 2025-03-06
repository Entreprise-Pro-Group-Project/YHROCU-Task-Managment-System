<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    // Display the supervisor dashboard with all projects
    public function dashboard()
    {
        $projects = \App\Models\Project::all(); // Example: Fetch all projects
        return view('supervisor.dashboard', compact('projects'));
    }
}