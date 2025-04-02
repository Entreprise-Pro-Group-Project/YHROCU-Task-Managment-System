<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeLog;
use App\Models\Project;
use App\Models\Task;
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
            
            // Write a title row with the project name
            fputcsv($handle, ["Activity Logs for Project: $projectName"]);
            fputcsv($handle, ["Generated on: " . now()->format('d M Y, h:i A')]);
            fputcsv($handle, []);
            
            // Write header row
            fputcsv($handle, [
                'Date & Time', 
                'User', 
                'Action', 
                'Entity Type', 
                'Entity Name/ID', 
                'Field', 
                'Old Value', 
                'New Value'
            ]);

            // Excluded fields from changes display
            $excludedFields = ['created_at', 'updated_at', 'deleted_at'];

            // For each log, format and output the row data
            foreach ($logs as $log) {
                $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                $action = $changesArray['action'] ?? 'updated';
                $before = $changesArray['before'] ?? [];
                $after = $changesArray['after'] ?? [];
                $entityType = $log->entity_type;
                
                // Get user information
                $userName = $log->user ? ($log->user->first_name . ' ' . $log->user->last_name) : 'Unknown';
                $userRole = $log->user ? $log->user->role : '';
                $userInfo = $userName . ($userRole ? " ($userRole)" : '');
                
                // Format date and time
                $dateTime = $log->created_at->format('d M Y, h:i A');
                
                // Get entity name based on entity type
                $entityName = $this->getEntityName($log, $before, $after);
                
                // Format action with entity type
                $formattedAction = ucfirst($action) . ' ' . ucfirst(str_replace('_', ' ', $entityType));
                
                // Handle different actions differently
                if ($action === 'created') {
                    // For created entities, show all fields as new
                    $afterFiltered = array_diff_key($after, array_flip($excludedFields));
                    
                    if (count($afterFiltered) > 0) {
                        foreach ($afterFiltered as $field => $value) {
                            fputcsv($handle, [
                                $dateTime,
                                $userInfo,
                                $formattedAction,
                                ucfirst(str_replace('_', ' ', $entityType)),
                                $entityName,
                                ucwords(str_replace('_', ' ', $field)),
                                'N/A',
                                $value ?: 'N/A'
                            ]);
                        }
                    } else {
                        // If no fields, just output a single row
                        fputcsv($handle, [
                            $dateTime,
                            $userInfo,
                            $formattedAction,
                            ucfirst(str_replace('_', ' ', $entityType)),
                            $entityName,
                            'N/A',
                            'N/A',
                            'N/A'
                        ]);
                    }
                } elseif ($action === 'updated') {
                    // For updated entities, compare before and after
                    $beforeFiltered = array_diff_key($before, array_flip($excludedFields));
                    $afterFiltered = array_diff_key($after, array_flip($excludedFields));
                    $diffs = [];
                    
                    foreach ($afterFiltered as $field => $newVal) {
                        $oldVal = $beforeFiltered[$field] ?? null;
                        // Compare trimmed strings to avoid whitespace issues
                        if (trim((string)$oldVal) !== trim((string)$newVal)) {
                            $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                        }
                    }
                    
                    if (count($diffs) > 0) {
                        foreach ($diffs as $field => $vals) {
                            fputcsv($handle, [
                                $dateTime,
                                $userInfo,
                                $formattedAction,
                                ucfirst(str_replace('_', ' ', $entityType)),
                                $entityName,
                                ucwords(str_replace('_', ' ', $field)),
                                $vals['before'] ?: 'N/A',
                                $vals['after'] ?: 'N/A'
                            ]);
                        }
                    } else {
                        // If no changes, just output a single row
                        fputcsv($handle, [
                            $dateTime,
                            $userInfo,
                            $formattedAction,
                            ucfirst(str_replace('_', ' ', $entityType)),
                            $entityName,
                            'No changes',
                            'N/A',
                            'N/A'
                        ]);
                    }
                } elseif ($action === 'deleted') {
                    // For deleted entities, show all fields as removed
                    $beforeFiltered = array_diff_key($before, array_flip($excludedFields));
                    
                    if (count($beforeFiltered) > 0) {
                        foreach ($beforeFiltered as $field => $value) {
                            fputcsv($handle, [
                                $dateTime,
                                $userInfo,
                                $formattedAction,
                                ucfirst(str_replace('_', ' ', $entityType)),
                                $entityName,
                                ucwords(str_replace('_', ' ', $field)),
                                $value ?: 'N/A',
                                'Deleted'
                            ]);
                        }
                    } else {
                        // If no fields, just output a single row
                        fputcsv($handle, [
                            $dateTime,
                            $userInfo,
                            $formattedAction,
                            ucfirst(str_replace('_', ' ', $entityType)),
                            $entityName,
                            'All data',
                            'Deleted',
                            'N/A'
                        ]);
                    }
                } else {
                    // For any other action, just output a single row
                    fputcsv($handle, [
                        $dateTime,
                        $userInfo,
                        $formattedAction,
                        ucfirst(str_replace('_', ' ', $entityType)),
                        $entityName,
                        'N/A',
                        'N/A',
                        'N/A'
                    ]);
                }
                
                // Add an empty row between different log entries for better readability
                if (!$log->is($logs->last())) {
                    fputcsv($handle, []);
                }
            }
            
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get a descriptive name for the entity based on its type and data.
     */
    private function getEntityName($log, $before, $after)
    {
        $entityType = $log->entity_type;
        $entityId = $log->entity_id;
        
        if ($entityType === 'task') {
            $taskName = $after['task_name'] ?? $before['task_name'] ?? null;
            
            if (!$taskName) {
                // Try to get task name from database if not in the changes
                $task = Task::find($entityId);
                $taskName = $task ? $task->task_name : null;
            }
            
            return $taskName ? "Task #$entityId: $taskName" : "Task #$entityId";
        } 
        elseif ($entityType === 'project') {
            $projectName = $after['project_name'] ?? $before['project_name'] ?? null;
            
            if (!$projectName) {
                // Try to get project name from database if not in the changes
                $project = Project::find($entityId);
                $projectName = $project ? $project->project_name : null;
            }
            
            return $projectName ? "Project #$entityId: $projectName" : "Project #$entityId";
        } 
        elseif ($entityType === 'task_comment') {
            $taskName = $after['task_name'] ?? null;
            $comment = $after['comment'] ?? null;
            
            if ($taskName) {
                return "Comment #$entityId on Task: $taskName";
            } elseif ($comment) {
                // Truncate long comments for display
                $truncatedComment = Str::limit($comment, 30);
                return "Comment #$entityId: \"$truncatedComment\"";
            } else {
                return "Comment #$entityId";
            }
        } 
        else {
            // For any other entity type
            return ucfirst(str_replace('_', ' ', $entityType)) . " #$entityId";
        }
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