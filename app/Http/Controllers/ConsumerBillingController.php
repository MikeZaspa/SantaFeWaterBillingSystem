<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumerBillingController extends Controller
{
    public function getCurrentBilling($consumerId)
    {
        // Verify the authenticated consumer matches the requested consumer
        if (auth('consumer')->id() != $consumerId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Get the latest unpaid billing for the consumer
        $billing = Billing::with('consumer')
            ->where('consumer_id', $consumerId)
            ->whereIn('status', ['unpaid', 'paid','overdue'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($billing) {
            return response()->json([
                'success' => true,
                'data' => $billing
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No current billing found'
        ]);
    }
}