<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\AdminConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BillingController extends Controller
{
   public function index()
{
    if(request()->ajax()) {
        $billings = Billing::with('consumer')
            ->orderBy('reading_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json(['data' => $billings]);
    }
    
    return view('auth.admin-plumber-consumer');
}
    public function create()
    {
        $consumers = AdminConsumer::where('status', 'active')
            ->select(['id', 'first_name', 'middle_name', 'last_name', 'suffix', 'meter_no', 'consumer_type'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
            
        return response()->json($consumers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consumer_id' => 'required|exists:admin_consumers,id',
            'meter_no' => 'required|string|max:50',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|min:0|gt:previous_reading',
            'reading_date' => 'required|date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $validated = $validator->validated();
        $consumer = AdminConsumer::findOrFail($validated['consumer_id']);

        // Check for existing reading for this consumer in the same month/year
        $existingReading = Billing::where('consumer_id', $validated['consumer_id'])
            ->whereYear('reading_date', date('Y', strtotime($validated['reading_date'])))
            ->whereMonth('reading_date', date('m', strtotime($validated['reading_date'])))
            ->first();

        if ($existingReading) {
            return response()->json([
                'message' => 'This consumer already has a reading for this billing period ('.date('F Y', strtotime($validated['reading_date'])).')',
                'existing_reading' => $existingReading,
                'errors' => ['reading_date' => ['Duplicate reading for this period']]
            ], 422);
        }

        try {
            $billing = Billing::create([
                'consumer_id' => $validated['consumer_id'],
                'consumer_type' => $consumer->consumer_type,
                'meter_no' => $validated['meter_no'],
                'previous_reading' => $validated['previous_reading'],
                'current_reading' => $validated['current_reading'],
                'consumption' => $validated['current_reading'] - $validated['previous_reading'],
                'reading_date' => $validated['reading_date'],
            ]);

            return response()->json([
                'message' => 'Billing record created successfully.',
                'billing' => $billing->load('consumer')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating billing record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Billing $billing)
    {
        return response()->json($billing->load('consumer'));
    }

   public function edit(Billing $billing)
{
    $consumers = AdminConsumer::where('status', 'active')
        ->select(['id', 'first_name', 'middle_name', 'last_name', 'suffix', 'meter_no', 'consumer_type'])
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->get();

    return response()->json([
        'billing' => $billing->load('consumer'),
        'consumers' => $consumers,
        'consumer' => $billing->consumer // Add this line
    ]);
}

    public function update(Request $request, Billing $billing)
    {
        $validator = Validator::make($request->all(), [
            'consumer_id' => 'required|exists:admin_consumers,id',
            'meter_no' => 'required|string|max:50',
            'previous_reading' => 'required|numeric|min:0',
            'current_reading' => 'required|numeric|min:0|gt:previous_reading',
            'reading_date' => 'required|date|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        $validated = $validator->validated();
        $consumer = AdminConsumer::findOrFail($validated['consumer_id']);

        try {
            $billing->update([
                'consumer_id' => $validated['consumer_id'],
                'meter_no' => $validated['meter_no'],
                'previous_reading' => $validated['previous_reading'],
                'current_reading' => $validated['current_reading'],
                'consumption' => $validated['current_reading'] - $validated['previous_reading'],
                'reading_date' => $validated['reading_date'],
            ]);

            return response()->json([
                'message' => 'Billing record updated successfully.',
                'billing' => $billing->fresh()->load('consumer')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating billing record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Billing $billing)
    {
        try {
            $billing->delete();
            return response()->json(['message' => 'Billing record deleted successfully.']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting billing record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLastReading($consumerId)
{
    $lastReading = Billing::where('consumer_id', $consumerId)
        ->orderBy('reading_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->first();

    return response()->json([
        'last_reading' => $lastReading ? [
            'previous_reading' => $lastReading->previous_reading,
            'current_reading' => $lastReading->current_reading,
            'reading_date' => $lastReading->reading_date->format('Y-m-d')
        ] : null
    ]);
}

public function disconnect(Request $request, $billingId)
    {
        try {
            // Find the billing record
            $billing = Billing::findOrFail($billingId);
            
            // Validate if consumer exists
            $consumer = AdminConsumer::find($billing->consumer_id);
            if (!$consumer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consumer not found'
                ], 404);
            }

            // Check if consumer is already disconnected
            if ($consumer->status === 'disconnected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Consumer is already disconnected'
                ], 400);
            }

            // Create disconnection record
            $disconnection = Disconnection::create([
                'consumer_id' => $billing->consumer_id,
                'billing_id' => $billing->id,
                'disconnection_date' => now(),
                'reason' => 'Non-payment or system disconnection',
                'status' => 'disconnected',
                'disconnected_by' => auth()->id() ?? 1 // Fallback to admin ID if no auth
            ]);

            // Update consumer status
            $consumer->update([
                'status' => 'disconnected',
                'disconnection_date' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Consumer disconnected successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Disconnection error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to disconnect consumer: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add this method to get consumers for dropdown
    public function getConsumers()
    {
        $consumers = AdminConsumer::where('status', 'active')
            ->orderBy('first_name')
            ->get();
            
        return response()->json($consumers);
    }
}