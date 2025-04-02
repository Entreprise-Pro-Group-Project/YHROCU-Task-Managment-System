<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_name',
        'task_description',
        'project_id',
        'parent_id',
        'assigned_staff',
        'assigned_date',
        'due_date',
        'status',
        'comment',
    ];


    protected $attributes = [
        'status' => 'assigned',
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

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    // helper function to check if the task is overdue

    public function getComputedStatusAttribute()
    {
        // If due date has passed and task isn't completed, return "over due"
        if ($this->due_date < now() && $this->status !== 'completed') {
            return 'over due';
        }
        return $this->status;
    }

    protected static function boot()
    {
        parent::boot();
        // Remove the existing change tracking code here - it's handled by TaskObserver
    }
}