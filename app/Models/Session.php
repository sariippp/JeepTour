<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'passenger_count'
    ];

    public function reservations() : HasMany {
        return $this->hasMany(Reservation::class, 'session_id');
    }
}
