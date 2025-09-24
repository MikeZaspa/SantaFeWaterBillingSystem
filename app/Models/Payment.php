<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'amount',
        'change_amount',
        'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function billing()
{
    return $this->belongsTo(AccountantBilling::class);
}
    public function payments()
{
    return $this->hasMany(Payment::class);
}

}