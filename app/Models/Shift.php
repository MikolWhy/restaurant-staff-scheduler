<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    protected $fillable = [
        'staff_id',
        'day',
        'start_time',
        'end_time',
        'role',
    ];

    public function staff(): BelongsTo
    {
        # A single shift can only belong to one staff member, references the Staff model.
        # returns the assigned staff or null if unassigned.
        return $this->belongsTo(Staff::class);
    }
}