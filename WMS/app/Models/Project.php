<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'project_description', 
        'project_date',
        'due_date',
        'supervisor_name',
        'progress',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected static function boot()
    {
        parent::boot();
        // Remove the updating event handler - it's handled by ProjectObserver
    }
}