<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        if ($this->quantity <= 0) return false;
        if ($this->expired_at && \Carbon\Carbon::parse($this->expired_at)->isPast()) return false;
        return true;
    }
}
