<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'phone', 'role'];

    // Мастер-классы, которые ведет этот пользователь
    public function myMasterClasses(): HasMany
    {
        return $this->hasMany(MasterClass::class, 'user_id');
    }

    // Мастер-классы, на которые записался этот пользователь
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(MasterClass::class, 'bookings');
    }
}
