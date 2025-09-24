<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdminConsumer extends Model
{
    use HasFactory;
   
   protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'suffix',
    'consumer_type',
    'meter_no',
    'contact_number',
    'address',
    'address_information', // Add this
    'connection_date', // Add this
    'consumer_type',
    'status'
];

    protected $casts = [
        'connection_date' => 'date',
        'consumer_type' => 'string',
        'status' => 'string'
    ];
  protected $attributes = [
    'status' => 'active'
];
     public function account(): HasOne
    {
        return $this->hasOne(ConsumerAccount::class, 'consumer_id');
    }
        // In Consumer model
    public function billings()
    {
        return $this->hasMany(AccountantBilling::class, 'consumer_id');
    }
    // Relationship with disconnections
    public function disconnections()
    {
        return $this->hasMany(Disconnection::class);
    }

   
}