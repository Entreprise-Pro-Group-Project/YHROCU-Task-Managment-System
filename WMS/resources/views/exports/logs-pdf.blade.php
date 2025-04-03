<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Logs PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2, h3 {
            margin-bottom: 0.75rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Prevent columns from overflowing */
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        ul {
            margin: 0;
            padding-left: 1.2em;
        }
        .italic {
            font-style: italic;
        }
        .line-through {
            text-decoration: line-through;
        }
        .text-red {
            color: red;
        }
        .text-green {
            color: green;
        }
        .text-gray {
            color: #888;
        }
        .mb-1 {
            margin-bottom: 0.25rem;
        }
        .mt-1 {
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <!-- Display Project Name -->
    <h2>Activity Log</h2>
    @if(isset($projectName))
        <h3>Project: {{ $projectName }}</h3>
    @endif
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Entity</th>
                <th>Changes</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                @php
                    $changesArray = is_array($log->changes) ? $log->changes : json_decode($log->changes, true);
                    $action = $changesArray['action'] ?? 'updated';
                    $before = $changesArray['before'] ?? [];
                    $after  = $changesArray['after'] ?? [];
                    $entityType = $log->entity_type;
                    $time = \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A');

                    $userDisplay = 'Unknown';
                    if ($log->user) {
                        $userName = $log->user->first_name . ' ' . $log->user->last_name;
                        $role = $log->user->role ? ' (' . $log->user->role . ')' : '';
                        $userDisplay = $userName . $role;
                    }
                @endphp

                @if($entityType === 'task')
                    <tr>
                        <td>{{ $userDisplay }}</td>
                        <td>{{ ucfirst($action) }} Task</td>
                        <td>
                            @php
                                $taskName = $after['task_name'] ?? $before['task_name'] ?? 'Unknown Task';
                            @endphp
                            Task #{{ $log->entity_id }}: {{ $taskName }}
                        </td>
                        <td style="font-size: 11px;">
                            @php
                                $excluded = ['created_at','updated_at','deleted_at'];
                            @endphp
                            @if($action === 'created')
                                @php $afterFiltered = array_diff_key($after, array_flip($excluded)); @endphp
                                @if(count($afterFiltered))
                                    @foreach($afterFiltered as $field => $value)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            <span style="color: green; margin-left: 4px;">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No details</span>
                                @endif
                            @elseif($action === 'updated')
                                @php
                                    $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                    $afterFiltered  = array_diff_key($after, array_flip($excluded));
                                    $diffs = [];
                                    foreach ($afterFiltered as $field => $newVal) {
                                        $oldVal = $beforeFiltered[$field] ?? null;
                                        if (trim((string)$oldVal) !== trim((string)$newVal)) {
                                            $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                                        }
                                    }
                                @endphp
                                @if(count($diffs))
                                    @foreach($diffs as $field => $vals)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            @if(!is_null($vals['before']))
                                                <span style="color: red; text-decoration: line-through; margin-left: 4px;">{{ $vals['before'] }}</span>
                                            @endif
                                            <span style="color: green; margin-left: 4px;">{{ $vals['after'] ?? 'N/A' }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No key changes</span>
                                @endif
                            @elseif($action === 'deleted')
                                @php $beforeFiltered = array_diff_key($before, array_flip($excluded)); @endphp
                                @if(count($beforeFiltered))
                                    @foreach($beforeFiltered as $field => $value)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            <span style="color: red; text-decoration: line-through; margin-left: 4px;">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No details</span>
                                @endif
                            @else
                                <span class="text-gray italic">No changes available</span>
                            @endif
                        </td>
                        <td style="font-size: 11px; color: #555;">{{ $time }}</td>
                    </tr>
                @elseif($entityType === 'task_comment')
                    <tr>
                        <td>{{ $userDisplay }}</td>
                        <td>{{ ucfirst($action) }} Comment</td>
                        <td>
                            @if(isset($after['task_name']))
                                Comment for Task: {{ $after['task_name'] }}
                            @else
                                Comment #{{ $log->entity_id }}
                            @endif
                        </td>
                        <td style="font-size: 11px;">
                            @if(isset($after['comment']))
                                <div class="mb-1">
                                    <strong>Comment:</strong>
                                    <span style="color: green; margin-left: 4px;">{{ $after['comment'] }}</span>
                                </div>
                            @else
                                <span class="text-gray italic">No comment</span>
                            @endif
                            @if(isset($after['user_name']))
                                <div class="mb-1">
                                    <strong>Comment By:</strong>
                                    <span style="color: green; margin-left: 4px;">{{ $after['user_name'] }}</span>
                                </div>
                            @endif
                        </td>
                        <td style="font-size: 11px; color: #555;">{{ $time }}</td>
                    </tr>
                @elseif($entityType === 'project')
                    <tr>
                        <td>{{ $userDisplay }}</td>
                        <td>{{ ucfirst($action) }} Project</td>
                        <td>
                            @php
                                $projectNameDisplay = $after['project_name'] ?? $before['project_name'] ?? 'Unknown Project';
                            @endphp
                            Project #{{ $log->entity_id }}: {{ $projectNameDisplay }}
                        </td>
                        <td style="font-size: 11px;">
                            @php $excluded = ['created_at','updated_at','deleted_at']; @endphp
                            @if($action === 'created')
                                @php $afterFiltered = array_diff_key($after, array_flip($excluded)); @endphp
                                @if(count($afterFiltered))
                                    @foreach($afterFiltered as $field => $value)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            <span style="color: green; margin-left: 4px;">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No details</span>
                                @endif
                            @elseif($action === 'updated')
                                @php
                                    $beforeFiltered = array_diff_key($before, array_flip($excluded));
                                    $afterFiltered  = array_diff_key($after, array_flip($excluded));
                                    $diffs = [];
                                    foreach ($afterFiltered as $field => $newVal) {
                                        $oldVal = $beforeFiltered[$field] ?? null;
                                        if (trim((string)$oldVal) !== trim((string)$newVal)) {
                                            $diffs[$field] = ['before' => $oldVal, 'after' => $newVal];
                                        }
                                    }
                                @endphp
                                @if(count($diffs))
                                    @foreach($diffs as $field => $vals)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            @if(!is_null($vals['before']))
                                                <span style="color: red; text-decoration: line-through; margin-left: 4px;">{{ $vals['before'] }}</span>
                                            @endif
                                            <span style="color: green; margin-left: 4px;">{{ $vals['after'] ?? 'N/A' }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No key changes</span>
                                @endif
                            @elseif($action === 'deleted')
                                @php $beforeFiltered = array_diff_key($before, array_flip($excluded)); @endphp
                                @if(count($beforeFiltered))
                                    @foreach($beforeFiltered as $field => $value)
                                        <div class="mb-1">
                                            <strong>{{ $field }}:</strong>
                                            <span style="color: red; text-decoration: line-through; margin-left: 4px;">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-gray italic">No details</span>
                                @endif
                            @else
                                <span class="text-gray italic">No changes available</span>
                            @endif
                        </td>
                        <td style="font-size: 11px; color: #555;">{{ $time }}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $userDisplay }}</td>
                        <td>{{ ucfirst($action) }} {{ ucfirst($entityType) }}</td>
                        <td>{{ ucfirst($entityType) }} #{{ $log->entity_id }}</td>
                        <td style="font-size: 11px;">
                            <span class="text-gray italic">Details not available</span>
                        </td>
                        <td style="font-size: 11px; color: #555;">{{ $time }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" style="padding: 16px; text-align: center; color: #999;">
                        No activity yet
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
