<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'destinationName',
        'destinationPostCode',
        'destinationLocation',
        'destinationStreetAdress',
        'destinationBuildingName',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_destinations', 'destination_id', 'user_id');
    }

    public function user_destinations() {
        return $this->hasMany(UserDestination::class);
    }
}
