<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public static $autoCnt = 1;

    protected $fillable = [
        'user_id',
        'order_name',
        'status',
        'delivery_date',
        'estimate_delivery_date',
    ];

    /**
     * Get all of the comments for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function manage_order(): HasMany
    {
        return $this->hasMany(ManageOrder::class);
    }

    /**
     * The users that belong to the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
