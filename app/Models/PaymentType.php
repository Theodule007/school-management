<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentType extends Model
{
        protected $fillable = [
        'name',
        'periodicity',
        'active'
    ];

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }
}
