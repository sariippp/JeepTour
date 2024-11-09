<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'city',
        'count',
        'price',
        'date'
    ];

    public function invoice() : HasOne {
        return $this->hasOne(Invoice::class, 'reservation_id');
    }

    public function session() : BelongsTo {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function jeeps() : BelongsToMany {
        return $this->belongsToMany(Jeep::class, 'reserved_jeep', 'jeep_id', 'reservation_id')
                    ->withTimestamps();
    }
}
