<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlinePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'consumer_id',
        'payment_method',
        'amount',
        'reference_number',
        'proof_image',
        'status',
        'admin_notes',
        'verified_at',
        'verified_by'
    ];

    // Relationship with AdminConsumer (direct)
    public function adminConsumer()
    {
        return $this->belongsTo(AdminConsumer::class, 'consumer_id');
    }

    // Relationship with Bill (AccountantBilling)
    public function bill()
    {
        return $this->belongsTo(AccountantBilling::class, 'bill_id');
    }

    // Relationship with Verifier (Admin/User)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    // Alias for bill relationship
    public function accountantBilling()
    {
        return $this->belongsTo(AccountantBilling::class, 'bill_id');
    }
}