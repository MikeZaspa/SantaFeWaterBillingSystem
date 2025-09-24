<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ConsumerAccount extends Authenticatable
{
    
   use HasFactory, SoftDeletes;
   
   protected $guard = 'consumer';

    protected $fillable = [
        'consumer_id',
        'username',
        'password',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password'
    ];

    public function consumer()
    {
        return $this->belongsTo(AdminConsumer::class, 'consumer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
}