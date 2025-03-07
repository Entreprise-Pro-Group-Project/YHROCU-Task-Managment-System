<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_name',
        'project_id',
        'parent_id',
        'assigned_staff',
        'due_date',
        'status',
        'comment',
    ];

    // Relationship with the project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship to parent task
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Relationship to sub-tasks
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}