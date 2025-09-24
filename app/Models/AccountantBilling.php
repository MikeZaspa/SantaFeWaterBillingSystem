<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountantBilling extends Model
{
    use HasFactory;
    protected $table = 'accountant_billings';

    protected $fillable = [
        'consumer_id',
        'consumer_type',
        'meter_no',
        'due_date',
        'previous_reading',
        'current_reading',
        'consumption',
        'total_amount',
        'status'
    ];

   protected $casts = [
    'due_date' => 'date',
    'previous_reading' => 'float',
    'current_reading' => 'float',
    'consumption' => 'float',
    'total_amount' => 'float',
];

    public function consumer()
    {
        return $this->belongsTo(AdminConsumer::class);
    }
 public function onlinePayments()
    {
        return $this->hasMany(OnlinePayment::class, 'bill_id');
    }

    // Add scope for unpaid bills
    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid');
    }

    // Add scope for pending bills
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
}