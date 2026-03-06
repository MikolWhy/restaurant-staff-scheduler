<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// This allows us to use the HasMany relationship in the Staff model.
use Illimunate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    # protected so it can only be accessed within the model or its subclasses.
    protected $table = 'staff';

    // $fillable is an array of the fields that can be mass assigned.
    protected $fillable = ['name', 'role', 'phone_number'];

    public function shifts(): HasMany
    {
        # A single staff member can have multiple shifts, references the Shift model.
        return $this->hasMany(Shift::class);
    }
}
