<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jeep extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'number_plate',
        'total_pasenger'
    ];

    public function owner() : BelongsTo {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function reservations() : BelongsToMany {
        return $this->belongsToMany(Reservation::class, 'reserve_jeep', 'jeep_id', 'reservation_id')
                    ->withTimestamps();
    }
}
