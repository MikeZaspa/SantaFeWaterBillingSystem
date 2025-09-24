<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineBilling extends Model
{
    use HasFactory;

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
        'previous_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function consumer()
    {
        return $this->belongsTo(AdminConsumer::class);
    }
}