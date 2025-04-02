<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Display the admin dashboard with all projects and filter tasks by status if provided
    public function dashboard(Request $request)
    {
        // Update tasks in the database:
        // If a task's due_date is in the past, its status isn't "completed" or already "overdue",
        // then update its status to "over due".
        Task::where('due_date', '<', now())
            ->where('status', '<>', 'completed')
            ->where('status', '<>', 'over due')
            ->update(['status' => 'over due']);

        // Retrieve the status filter from the query parameter, defaulting to 'all'
        $status = $request->query('status', 'all');

        // Load projects and eager load tasks filtered by the status if not 'all'
        $projects = Project::with(['tasks' => function ($query) use ($status) {
            if ($status !== 'all') {
                if ($status === 'overdue') {
                    // Filter tasks with status "overdue"
                    $query->where('status', 'over due');
                } else {
                    $query->where('status', $status);
                }
            }
        }])->get();

        return view('admin.dashboard', compact('projects', 'status'));
    }
}
