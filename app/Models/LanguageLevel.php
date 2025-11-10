<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'duration_days'];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getActiveStudentsCountAttribute()
    {
        return $this->registrations()->where('status', 'active')->count();
    }
}