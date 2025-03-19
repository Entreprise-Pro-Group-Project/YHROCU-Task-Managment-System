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

        static::updating(function ($project) {
            $original = $project->getOriginal(); // Get old values
            $changes = $project->getDirty(); // Get changed values

            if (!empty($changes)) {
                ChangeLog::create([
                    'entity_type' => 'project',
                    'entity_id' => $project->id,
                    'changed_by' => Auth::id(),
                    'changes' => json_encode([
                        'before' => $original,
                        'after' => $changes,
                    ]),
                ]);
            }
        });
    }
}