<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'project_name',
        'project_date',
        'due_date',
        'supervisor_name',
        'progress',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}