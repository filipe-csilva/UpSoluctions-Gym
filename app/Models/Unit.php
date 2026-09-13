<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'phone', 'email', 'address', 'number', 'neighborhood', 'city', 'state', 'zip_code', 'active'])]
class Unit extends Model
{
    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'manager_unit');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
