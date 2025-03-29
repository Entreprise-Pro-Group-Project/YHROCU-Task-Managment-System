<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeLog;
use App\Models\Project;
use PDF; // from barryvdh/laravel-dompdf
use Illuminate\Support\Str;

class ActivityLogExportController extends Controller
{
    /**
     * Export Activity Logs to CSV for a specific project.
     */
    public function exportCsv($projectId)
    {
        // Retrieve the project to get its name for the header and filename.
        $project = Project::findOrFail($projectId);
        $projectName = $project->project_name;
        $projectNameSlug = Str::slug($projectName);

        // Filter logs for the specified project
        $logs = ChangeLog::with('user')
            ->when($projectId, function ($query) use ($projectId) {
                $query->where(function ($q) use ($projectId) {
                    // Logs for tasks that belong to the project
                    $q->where(function ($q1) use ($projectId) {
                        $q1->where('entity_type', 'task')
                           ->whereIn('entity_id', function($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('tasks')
                                        ->where('project_id', $projectId);
                           });
                    })
                    ->orWhere(function ($q2) use ($projectId) {
                        // Logs for project updates
                        $q2->where('entity_type', 'project')
                           ->where('entity_id', $projectId);
                    })
                    ->orWhere(function ($q3) use ($projectId) {
                        // Logs for task comments belonging to tasks in the project
                        $q3->where('entity_type', 'task_comment')
                           ->whereIn('entity_id', function($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('task_comments')
                                        ->whereIn('task_id', function($sq) use ($projectId) {
                                            $sq->select('id')
                                               ->from('tasks')
                                               ->where('project_id', $projectId);
                                        });
                           });
                    });
                });
            })
            ->latest()
            ->get();

        $fileName = 'activity_logs_project_' . $projectNameSlug . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($logs, $projectName) {
            $handle = fopen('php://output', 'w');

            // Write a title row with the project name.
            fputcsv($handle, ["Activity Logs for Project: $projectName"]);
            // Write an empty row to separate the title from headers.
            fputcsv($handle, []);
            // Write header row.
            fputcsv($handle, ['User', 'Action', 'Entity', 'Changes', 'Time']);

            // For each log, format and output the row data.
            foreach ($logs as $log) {
                $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                $userName   = $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown';
                $action     = $changesArray['action'] ?? 'updated';
                $entityType = $log->entity_type;
                $time       = $log->created_at->format('d M Y, h:i A');

                // Build a changes string by comparing before/after arrays.
                $before = $changesArray['before'] ?? [];
                $after  = $changesArray['after'] ?? [];
                $changesString = '';

                if (is_array($before) && is_array($after)) {
                    $allFields = array_unique(array_merge(array_keys($before), array_keys($after)));
                    foreach ($allFields as $field) {
                        $oldVal = $before[$field] ?? 'null';
                        $newVal = $after[$field] ?? 'null';
                        if (trim((string)$oldVal) !== trim((string)$newVal)) {
                            $changesString .= "$field: $newVal (was $oldVal); ";
                        } else {
                            $changesString .= "$field: $newVal; ";
                        }
                    }
                } else {
                    $changesString = json_encode($changesArray, JSON_PRETTY_PRINT);
                }

                fputcsv($handle, [
                    $userName,
                    ucfirst($action),
                    ucfirst($entityType) . ' #' . $log->entity_id,
                    $changesString,
                    $time
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Activity Logs to PDF for a specific project.
     */
    public function exportPdf($projectId)
    {
        // Retrieve the project to get its name for the view and filename
        $project = Project::findOrFail($projectId);
        $projectNameSlug = Str::slug($project->project_name);

        // Filter logs for the specified project
        $logs = ChangeLog::with('user')
            ->when($projectId, function ($query) use ($projectId) {
                $query->where(function ($q) use ($projectId) {
                    // Logs for tasks that belong to the project
                    $q->where(function ($q1) use ($projectId) {
                        $q1->where('entity_type', 'task')
                           ->whereIn('entity_id', function ($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('tasks')
                                        ->where('project_id', $projectId);
                           });
                    })
                    ->orWhere(function ($q2) use ($projectId) {
                        // Logs for project updates
                        $q2->where('entity_type', 'project')
                           ->where('entity_id', $projectId);
                    })
                    ->orWhere(function ($q3) use ($projectId) {
                        // Logs for task comments that belong to tasks in the project
                        $q3->where('entity_type', 'task_comment')
                           ->whereIn('entity_id', function ($subquery) use ($projectId) {
                               $subquery->select('id')
                                        ->from('task_comments')
                                        ->whereIn('task_id', function ($sq) use ($projectId) {
                                            $sq->select('id')
                                               ->from('tasks')
                                               ->where('project_id', $projectId);
                                        });
                           });
                    });
                });
            })
            ->latest()
            ->get();

        // Pass both logs and project name to the view
        $pdf = PDF::loadView('exports.logs-pdf', [
            'logs' => $logs,
            'projectName' => $project->project_name,
        ])->setPaper('a4', 'landscape');

        $fileName = 'activity_logs_project_' . $projectNameSlug . '.pdf';
        return $pdf->download($fileName);
    }
}
