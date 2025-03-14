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

    // helper function to check if the task is overdue

    public function getComputedStatusAttribute()
    {
        // If due date has passed and task isn’t completed, return "over due"
        if ($this->due_date < now() && $this->status !== 'completed') {
            return 'over due';
        }
        return $this->status;
    }


}