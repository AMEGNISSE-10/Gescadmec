<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function needs()
    {
        return $this->hasMany(Need::class);
    }

    public function getActiveRegistrationAttribute()
    {
        return $this->registrations()->where('status', 'active')->first();
    }
}