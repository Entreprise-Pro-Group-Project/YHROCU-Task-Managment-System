<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    // Display the supervisor dashboard with all projects and filter tasks by status if provided
    public function dashboard(Request $request)
    {
        // Retrieve the selected status from the query parameter, defaulting to 'all'
        $status = $request->query('status', 'all');

        // Optionally, if you want to update overdue tasks automatically, you could uncomment the following:
        /*
        Task::where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'overdue')
            ->update(['status' => 'overdue']);
        */

        // Load projects with their tasks. When a filter other than 'all' is applied,
        // we constrain the eager-loaded tasks to only include those matching the selected status.
        $projects = Project::with(['tasks' => function ($query) use ($status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }])->get();

        // Pass both projects and the current status to the view
        return view('supervisor.dashboard', compact('projects', 'status'));
    }
}
