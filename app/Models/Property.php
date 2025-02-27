<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'max_occupants',
        'price_per_night',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
