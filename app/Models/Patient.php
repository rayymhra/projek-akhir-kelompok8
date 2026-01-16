<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}