<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Good extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'manageGoodsId',
        'goodsTitle',
        'goodsInventory',
    ];
    

    public function UserGood() {
        return $this->hasMany(UserGood::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_goods', 'good_id', 'user_id');
    }
    
    // public function manage_order(): BelongsTo
    // {
    //     return $this->belongsTo(ManageOrder::class);
    // }
}
