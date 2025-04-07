<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id',
        'changed_by',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function user()
    {
        // Tell Eloquent the foreign key is 'changed_by'
        return $this->belongsTo(User::class, 'changed_by');
    }
}
