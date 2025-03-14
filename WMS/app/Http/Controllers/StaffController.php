<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function dashboard(Request $request)
    {
        // Start by filtering tasks assigned to the current user
        $tasksQuery = Task::where('assigned_staff', Auth::user()->first_name);

        // Get the selected status from the query parameter, defaulting to 'all'
        $status = $request->query('status', 'all');

        if ($status !== 'all') {
            if ($status === 'overdue') {
                // Filter tasks that are overdue and not completed.
                $tasksQuery->where('due_date', '<', now())
                           ->where('status', '!=', 'completed');
            } else {
                // Filter by the provided status
                $tasksQuery->where('status', $status);
            }
        }

        $tasks = $tasksQuery->get();

        // Pass the tasks variable to the view
        return view('staff.dashboard', compact('tasks'));
    }
}
