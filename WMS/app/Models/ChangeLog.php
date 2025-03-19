<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type', 
        'entity_id', 
        'changed_by', 
        'changes'
    ];

    protected $casts = [
        'changes' => 'array', // Automatically convert JSON to an array
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}