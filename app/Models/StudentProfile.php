<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
   protected $fillable = [ 'user_id', 'cpf', 'birth_date', 'phone', 'gender', 'address', 'number', 'neighborhood', 'city', 'state', 'zip_code', 'emergency_contact', 'emergency_phone', 'notes', ];

   protected function casts(): array {
    return [ 'birth_date' => 'date', ];
    }
   public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
