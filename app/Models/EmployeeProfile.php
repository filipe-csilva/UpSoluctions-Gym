<?php

namespace App\Models;

use Database\Factories\EmployeeProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    /** @use HasFactory<EmployeeProfileFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'cpf', 'phone', 'gender', 'address', 'number', 'neighborhood', 'city', 'state', 'zip_code'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
