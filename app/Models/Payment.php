<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'secretary_id',
        'amount_paid',
        'payment_method',
        'receipt_number',
        'payment_date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->receipt_number)) {
                $payment->receipt_number = 'RC' . date('Ymd') . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function secretary()
    {
        return $this->belongsTo(Secretary::class);
    }
}