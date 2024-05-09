<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_name',
        'furigana_name',
        'phone_number',
        'post_code_prefix',
        'post_code_subfix',
        'location',
        'street_adress',
        'building_name',
        'user_role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function UserGood() {
        return $this->hasMany(UserGood::class);
    }

    public function user_destinations() {
        return $this->hasMany(UserDestination::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Good::class, 'user_goods', 'user_id', 'good_id');
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'user_destinations', 'user_id', 'destination_id');
    }

    public function destinationpagenumber(): HasOne
    {
        return $this->hasOne(Destinationpagenumber::class);
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
