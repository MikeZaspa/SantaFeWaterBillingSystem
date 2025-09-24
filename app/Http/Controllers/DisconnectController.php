<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Disconnection;
use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisconnectController extends Controller
{
    public function index()
    {
        $disconnections = Disconnection::with(['consumer', 'bill', 'disconnectedBy', 'reconnectedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($disconnections);
    }

    public function disconnect(Request $request, $billingId)
{
    $request->validate([
        'reason' => 'required|string|max:500',
        'disconnection_date' => 'required|date'
    ]);

    try {
        $billing = Billing::with('consumer')->findOrFail($billingId);

        // If user is not authenticated, use a default user ID (e.g., 1 for admin)
        $userId = Auth::check() ? Auth::id() : 1;

        $disconnection = Disconnection::create([
            'bill_id' => $billing->id,
            'consumer_id' => $billing->consumer_id,
            'disconnection_date' => $request->disconnection_date,
            'reason' => $request->reason,
            'status' => 'disconnected',
            'disconnected_by' => $userId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Consumer disconnected successfully',
            'data' => $disconnection
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to disconnect consumer: ' . $e->getMessage()
        ], 500);
    }
}

    public function reconnect(Request $request, $disconnectionId)
    {
        $request->validate([
            'reconnection_date' => 'required|date'
        ]);

        try {
            $disconnection = Disconnection::findOrFail($disconnectionId);

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please log in again.'
                ], 401);
            }

            $userId = Auth::id();

            $disconnection->update([
                'status' => 'reconnected',
                'reconnection_date' => $request->reconnection_date,
                'reconnected_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consumer reconnected successfully',
                'data' => $disconnection
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reconnect consumer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDisconnectionStatus($consumerId)
    {
        $disconnection = Disconnection::where('consumer_id', $consumerId)
            ->where('status', 'disconnected')
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'is_disconnected' => !is_null($disconnection),
            'disconnection' => $disconnection
        ]);
    }
}