<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'secretary_id',
        'description',
        'status',
        'response'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function secretary()
    {
        return $this->belongsTo(Secretary::class);
    }
}