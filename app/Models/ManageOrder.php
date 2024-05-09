<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'good_id',
        'destination_id',
        'quantity',
    ];

    /**
     * The orders that belong to the ManageOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'manage_order');
    }

    /**
     * Get all of the comments for the ManageOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function goods(): HasMany
    {
        return $this->hasMany(Good::class);
    }
}
