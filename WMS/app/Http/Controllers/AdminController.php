<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Display the admin dashboard with all projects
    public function dashboard()
    {
        $projects = \App\Models\Project::all(); // Example: Fetch all projects
        return view('admin.dashboard', compact('projects'));
    }
}