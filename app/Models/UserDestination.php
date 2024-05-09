<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserDestination extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'destination_id',
        'destinationManageLabel',
    ];

    protected $table = 'user_destinations';
}
