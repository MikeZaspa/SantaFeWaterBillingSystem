<?php

namespace App\Http\Controllers;

use App\Models\OnlinePayment;
use App\Models\AccountantBilling;
use App\Models\AdminConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OnlinePaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|exists:accountant_billings,id',
            'payment_method' => 'required|in:gcash,maya',
            'reference_number' => 'required|string|max:255',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $bill = AccountantBilling::findOrFail($request->bill_id);
            
            // Check if bill is already paid
            if ($bill->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This bill has already been paid.'
                ], 422);
            }

            // Check if there's already a pending payment for this bill
            $existingPendingPayment = OnlinePayment::where('bill_id', $request->bill_id)
                ->where('status', 'pending')
                ->first();
                
            if ($existingPendingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'There is already a pending payment for this bill. Please wait for verification.'
                ], 422);
            }

            // Upload proof image
            $imagePath = $request->file('proof_image')->store('payment-proofs', 'public');

            // Create online payment record
            $payment = OnlinePayment::create([
                'bill_id' => $request->bill_id,
                'consumer_id' => Auth::guard('consumer')->id(),
                'payment_method' => $request->payment_method,
                'amount' => $bill->total_amount,
                'reference_number' => $request->reference_number,
                'proof_image' => $imagePath,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment submitted successfully. Waiting for verification.',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $search = $request->get('search', '');
        
        $payments = OnlinePayment::with(['bill', 'adminConsumer', 'verifier'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                return $query->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('adminConsumer', function($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('meter_no', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'payments' => $payments
        ]);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        try {
            $payment = OnlinePayment::with('bill')->findOrFail($id);
            
            if ($payment->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment has already been processed.'
                ], 422);
            }

            // Update payment status
            $payment->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id()
            ]);

            // If payment is verified, update the billing status
            if ($request->status === 'verified') {
                $payment->bill->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);
                
                $message = 'Payment verified successfully.';
            } else {
                $message = 'Payment rejected.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process verification: ' . $e->getMessage()
            ], 500);
        }
    }

   public function show($id)
{
    $payment = OnlinePayment::with([
        'adminConsumer', 
        'bill.consumer',  // Load bill with its consumer
        'verifier'
    ])->findOrFail($id);
    
    return response()->json([
        'success' => true,
        'data' => $payment
    ]);
}

   public function datatable(Request $request)
    {
        $query = OnlinePayment::with(['bill.consumer', 'adminConsumer']);

        // Handle status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Handle search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchTerm = $request->search['value'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('reference_number', 'like', "%{$searchTerm}%")
                  ->orWhere('bill_id', 'like', "%{$searchTerm}%")
                  ->orWhere('payment_method', 'like', "%{$searchTerm}%")
                  ->orWhere('amount', 'like', "%{$searchTerm}%")
                  ->orWhereHas('adminConsumer', function($q2) use ($searchTerm) {
                      $q2->where('first_name', 'like', "%{$searchTerm}%")
                         ->orWhere('last_name', 'like', "%{$searchTerm}%")
                         ->orWhere('meter_no', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('bill.consumer', function($q3) use ($searchTerm) {
                      $q3->where('first_name', 'like', "%{$searchTerm}%")
                         ->orWhere('last_name', 'like', "%{$searchTerm}%")
                         ->orWhere('meter_no', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Get total records count
        $totalRecords = OnlinePayment::count();
        
        // Get filtered count (before pagination)
        $filteredQuery = clone $query;
        $filteredCount = $filteredQuery->count();

        // Handle ordering
        if ($request->has('order') && count($request->order) > 0) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];
            
            // Map column index to column name (updated for new column structure)
            $columns = [
                0 => 'id', 
                1 => 'admin_consumers.first_name', // Handle consumer name ordering
                2 => 'admin_consumers.meter_no',   // Handle meter number ordering
                3 => 'amount', 
                4 => 'payment_method', 
                5 => 'reference_number', 
                6 => 'created_at', 
                7 => 'status',
                8 => 'actions'
            ];
            
            if (isset($columns[$orderColumnIndex])) {
                $orderColumn = $columns[$orderColumnIndex];
                
                // Handle relationship ordering for consumer data
                if (strpos($orderColumn, 'admin_consumers.') === 0) {
                    $relationColumn = str_replace('admin_consumers.', '', $orderColumn);
                    
                    $query->leftJoin('admin_consumers', 'online_payments.consumer_id', '=', 'admin_consumers.id')
                          ->orderBy("admin_consumers.{$relationColumn}", $orderDirection)
                          ->select('online_payments.*');
                } else {
                    $query->orderBy($orderColumn, $orderDirection);
                }
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Handle pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $data = $query->skip($start)->take($length)->get();

        // Format the data for DataTables
        $formattedData = $data->map(function($payment) {
            // Try to get consumer data from multiple possible sources
            $consumerData = null;
            
            // First try adminConsumer relationship
            if ($payment->adminConsumer) {
                $consumerData = [
                    'first_name' => $payment->adminConsumer->first_name,
                    'last_name' => $payment->adminConsumer->last_name,
                    'meter_no' => $payment->adminConsumer->meter_no
                ];
            } 
            // Then try bill->consumer relationship
            elseif ($payment->bill && $payment->bill->consumer) {
                $consumerData = [
                    'first_name' => $payment->bill->consumer->first_name,
                    'last_name' => $payment->bill->consumer->last_name,
                    'meter_no' => $payment->bill->consumer->meter_no
                ];
            }
            // Fallback to empty data
            else {
                $consumerData = [
                    'first_name' => 'N/A',
                    'last_name' => 'N/A',
                    'meter_no' => 'N/A'
                ];
            }

            return [
                'id' => $payment->id,
                'admin_consumer' => $consumerData,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'reference_number' => $payment->reference_number,
                'created_at' => $payment->created_at->toDateTimeString(),
                'status' => $payment->status,
                'proof_image' => $payment->proof_image,
                'admin_notes' => $payment->admin_notes,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => $formattedData
        ]);
    }


    // Remove duplicate method
    public function submitPayment(Request $request)
    {
        // This is a duplicate of store() method, so remove it or keep only one
        return $this->store($request);
    }

    
}