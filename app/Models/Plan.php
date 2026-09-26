<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = ['name', 'description', 'duration_months', 'installments', 'price', 'promotion_type', 'promotion_value', 'promotion_start_date', 'promotion_end_date', 'active'];

    protected function casts(): array
    {
        return ['installments' => 'integer', 'price' => 'decimal:2', 'promotion_value' => 'decimal:2', 'promotion_start_date' => 'date', 'promotion_end_date' => 'date', 'active' => 'boolean'];
    }

    public function isPromotionActive(?Carbon $date = null): bool
    {
        if (! $this->promotion_type || $this->promotion_value === null) {
            return false;
        }

        $date ??= today();

        return (! $this->promotion_start_date || $this->promotion_start_date->lte($date))
            && (! $this->promotion_end_date || $this->promotion_end_date->gte($date));
    }

    public function promotionalPrice(?Carbon $date = null): float
    {
        $price = (float) $this->price;

        if (! $this->isPromotionActive($date)) {
            return $price;
        }

        $discount = $this->promotion_type === 'percentage'
            ? $price * ((float) $this->promotion_value / 100)
            : (float) $this->promotion_value;

        return round(max(0, $price - $discount), 2);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
