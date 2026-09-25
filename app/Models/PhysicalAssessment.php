<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalAssessment extends Model
{
    protected $fillable = ['student_id', 'teacher_id', 'assessment_date', 'height', 'weight', 'body_fat', 'muscle_mass', 'bmi', 'waist', 'abdomen', 'hip', 'chest', 'arm', 'thigh', 'notes'];

    protected function casts(): array
    {
        return ['assessment_date' => 'date'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
