<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'consumer_type',
        'meter_no',
        'previous_reading',
        'current_reading',
        'consumption',
        'reading_date'
    ];

     protected $casts = [
        'reading_date' => 'date',
        'previous_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'consumption' => 'decimal:2',
    ];
    public function consumer()
    {
        return $this->belongsTo(AdminConsumer::class);
    }
    public function disconnections()
    {
        return $this->hasMany(Disconnection::class);
    }
    
}