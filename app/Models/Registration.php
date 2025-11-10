<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 
        'language_level_id', 
        'secretary_id',
        'start_date', 
        'end_date',
        'status'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function languageLevel()
    {
        return $this->belongsTo(LanguageLevel::class);
    }

    public function secretary()
    {
        return $this->belongsTo(Secretary::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Calcul du total payé
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount_paid');
    }

    // Calcul du reste à payer
    public function getRemainingAmountAttribute()
    {
        return $this->languageLevel->price - $this->total_paid;
    }

    // Vérifier si c'est soldé
    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }

    // Jours restants
    public function getDaysRemainingAttribute()
    {
        $today = Carbon::today();
        $endDate = Carbon::parse($this->end_date);
        
        if ($today->gt($endDate)) {
            return 0;
        }
        
        return $today->diffInDays($endDate);
    }

    // Pourcentage de progression du paiement
    public function getPaymentProgressAttribute()
    {
        if ($this->languageLevel->price == 0) {
            return 100;
        }
        
        return min(100, ($this->total_paid / $this->languageLevel->price) * 100);
    }
}