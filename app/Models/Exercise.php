<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['name', 'description', 'muscle_group', 'equipment', 'instructions', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }
}
