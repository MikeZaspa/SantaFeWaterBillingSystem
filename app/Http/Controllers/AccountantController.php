<?php

namespace App\Http\Controllers;

use App\Models\AccountantBilling;
use App\Models\AdminConsumer;
use App\Models\WaterRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AccountantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin-accountant-consumer');
    }
    /**z
     * Get billings data for DataTables
     */
    public function getBillings(Request $request)
{
    $query = AccountantBilling::with(['consumer' => function($query) {
        $query->select('id', 'first_name', 'last_name');
    }])->orderBy('created_at', 'desc');

    // Apply filters
    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }

    if ($request->has('month') && $request->month) {
        $query->whereMonth('due_date', Carbon::parse($request->month)->month)
              ->whereYear('due_date', Carbon::parse($request->month)->year);
    }

    return datatables()->eloquent($query)
        ->addIndexColumn()
        ->addColumn('consumer_name', function($billing) {
            return $billing->consumer ? $billing->consumer->first_name . ' ' . $billing->consumer->last_name : 'N/A';
        })
        ->editColumn('due_date', function($billing) {
            return $billing->due_date ? $billing->due_date->format('M d, Y') : '';
        })
        ->editColumn('total_amount', function($billing) {
            return '₱' . number_format($billing->total_amount, 2);
        })
        ->editColumn('status', function($billing) {
            return $billing->status; // Make sure this returns the status string
        })
        ->addColumn('actions', function($billing) {
            return $billing->id; // This will be handled by frontend JavaScript
        })
        ->rawColumns(['status', 'actions'])
        ->toJson();
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'consumer_id' => 'required|exists:admin_consumers,id',
        'current_reading' => 'required|numeric|min:0',
        'payment_method' => 'nullable|string|in:cash,gcash,maya',
        'due_date' => 'required|date',
        'status' => 'required|in:paid,unpaid,overdue',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        $consumer = AdminConsumer::findOrFail($request->consumer_id);

        // 🔹 Prevent duplicate billing (same consumer, same month/year)
        $existingBilling = AccountantBilling::where('consumer_id', $consumer->id)
            ->whereMonth('due_date', Carbon::parse($request->due_date)->month)
            ->whereYear('due_date', Carbon::parse($request->due_date)->year)
            ->first();

        if ($existingBilling) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'duplicate' => ['A billing record already exists.']
                ]
            ], 422);
        }

        // 🔹 Validate reading
        $previousReading = AccountantBilling::where('consumer_id', $consumer->id)
            ->latest()
            ->value('current_reading') ?? 0;

        if ($request->current_reading < $previousReading) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'reading' => ['Current reading cannot be less than the previous reading (' . $previousReading . ').']
                ]
            ], 422);
        }

        // 🔹 Calculate consumption & total
        $consumption = $request->current_reading - $previousReading;
        $totalAmount = $this->calculateWaterBill($consumer->consumer_type, $consumption);

        $billing = AccountantBilling::create([
            'consumer_id' => $consumer->id,
            'consumer_type' => $consumer->consumer_type,
            'meter_no' => $consumer->meter_no,
            'due_date' => $request->due_date,
            'previous_reading' => $previousReading,
            'current_reading' => $request->current_reading,
            'consumption' => $consumption,
            'payment_method' => $request->payment_method,
            'total_amount' => $totalAmount,
            'status' => $request->status,
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Billing record created successfully',
            'data' => $billing
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error creating billing: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $billing = AccountantBilling::with('consumer')->findOrFail($id);
        return response()->json($billing);
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    try {
        $billing = AccountantBilling::with('consumer')->findOrFail($id);
        
        // Format the billing data for frontend
        $formattedBilling = [
            'id' => $billing->id,
            'previous_reading' => (float)$billing->previous_reading,
            'current_reading' => (float)$billing->current_reading,
            'consumption' => (float)$billing->consumption,
            'total_amount' => (float)$billing->total_amount,
            'due_date' => $billing->due_date->format('Y-m-d'),
            'status' => $billing->status,
        ];

        // Format the consumer data
        $formattedConsumer = [
            'id' => $billing->consumer->id,
            'first_name' => $billing->consumer->first_name,
            'middle_name' => $billing->consumer->middle_name,
            'last_name' => $billing->consumer->last_name,
            'suffix' => $billing->consumer->suffix,
            'consumer_type' => $billing->consumer->consumer_type,
            'meter_no' => $billing->consumer->meter_no,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'billing' => $formattedBilling,
                'consumer' => $formattedConsumer
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load billing data: ' . $e->getMessage()
        ], 404);
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'current_reading' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $billing = AccountantBilling::findOrFail($id);
            $consumer = $billing->consumer;

            $previousReading = $billing->previous_reading;
            $currentReading = $request->current_reading;
            
            // Validate current reading is not less than previous
            if ($currentReading < $previousReading) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current reading cannot be less than previous reading'
                ], 422);
            }

            $consumption = $currentReading - $previousReading;

            // Recalculate total amount if reading changed
            if ($currentReading != $billing->current_reading) {
                $totalAmount = $this->calculateWaterBill($consumer->consumer_type, $consumption);
            } else {
                $totalAmount = $billing->total_amount;
            }

            $billing->update([
                'due_date' => $request->due_date,
                'current_reading' => $currentReading,
                'consumption' => $consumption,
                'total_amount' => $totalAmount,
                'status' => $request->status,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Billing updated successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating billing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $billing = AccountantBilling::findOrFail($id);
            $billing->delete();

            return response()->json([
                'success' => true,
                'message' => 'Billing deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting billing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get last reading for a consumer
     */
    public function getLastReading($consumerId)
    {
        $lastReading = AccountantBilling::where('consumer_id', $consumerId)
            ->latest()
            ->first(['previous_reading', 'current_reading']);

        return response()->json([
            'success' => true,
            'data' => $lastReading ?: [
                'previous_reading' => 0,
                'current_reading' => 0
            ]
        ]);
    }

    /**
     * Calculate water bill based on consumption and rates
     */
    public function calculateWaterBill($type, $consumption)
{
    $rates = WaterRate::where('type', $type)
              ->orderBy('range')
              ->get();

    if ($rates->isEmpty()) {
        throw new \Exception("No water rates defined for {$type} type");
    }

    $totalAmount = 0;
    $remainingConsumption = $consumption;

    foreach ($rates as $rate) {
        if (str_contains($rate->range, '+')) {
            // Handle open-ended range (e.g., "31+")
            $rangeConsumption = $remainingConsumption;
        } else {
            // Handle normal ranges (e.g., "0-10", "11-20")
            $rangeParts = explode('-', $rate->range);
            $min = (int)$rangeParts[0];
            $max = (int)$rangeParts[1];
            $rangeConsumption = min($remainingConsumption, $max - $min + 1);
        }

        $totalAmount += $rangeConsumption * $rate->amount;
        $remainingConsumption -= $rangeConsumption;

        if ($remainingConsumption <= 0) break;
    }

    // Add posos charge if residential
    if ($type === 'residential') {
        $pososCharge = floor($consumption / 11) * 2;
        $totalAmount += $pososCharge;
    }

    return round($totalAmount, 2);
}
public function getBillingDetails($id)
{
    try {
        $billing = AccountantBilling::with('consumer')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load billing data: ' . $e->getMessage()
        ], 404);
    }
}
public function getReceiptData($id)
{
    try {
        $billing = AccountantBilling::with('consumer')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $billing
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load receipt data: ' . $e->getMessage()
        ], 404);
    }
}
public function calculatePenalty($dueDate, $paymentDate = null)
{
    $due = Carbon::parse($dueDate);
    $now = $paymentDate ? Carbon::parse($paymentDate) : Carbon::now();
    
    if ($now->greaterThan($due)) {
        return 10.00; // ₱10 penalty
    }
    
    return 0.00;
}
}