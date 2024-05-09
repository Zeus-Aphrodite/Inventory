<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinationpagenumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rowNumber',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
