<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Secretary extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function needs()
    {
        return $this->hasMany(Need::class);
    }
}