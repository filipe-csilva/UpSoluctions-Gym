<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
