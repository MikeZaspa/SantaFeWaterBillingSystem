<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disconnection extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'consumer_id',
        'disconnection_date',
        'reason',
        'status',
        'disconnected_by',
        'reconnection_date',
        'reconnected_by'
    ];

    protected $casts = [
        'disconnection_date' => 'date',
        'reconnection_date' => 'date',
    ];

    public function consumer()
    {
        return $this->belongsTo(AdminConsumer::class, 'consumer_id');
    }

    public function bill()
    {
        return $this->belongsTo(Billing::class, 'bill_id');
    }

    public function disconnectedBy()
    {
        return $this->belongsTo(User::class, 'disconnected_by');
    }

    public function reconnectedBy()
    {
        return $this->belongsTo(User::class, 'reconnected_by');
    }

}