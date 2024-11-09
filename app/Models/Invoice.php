<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'time_paid',
        'total'
    ];

    public function reservation(): BelongsTo{
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
