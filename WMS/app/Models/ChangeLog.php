<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'changed_by',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array', // Automatically decodes JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
