<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}



