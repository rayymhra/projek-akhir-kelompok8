<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'payment_method_id',
        'user_id',
        'description',
        'total',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}