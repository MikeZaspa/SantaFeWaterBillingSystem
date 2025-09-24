<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\AccountantBilling;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with('billing')->latest()->paginate(10);
        return response()->json($payments);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'billing_id' => 'required|exists:accountant_billings,id', // Changed to accountant_billings
            'amount' => 'required|numeric|min:0.01',
            'change_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        // Calculate change if not provided
        if (!isset($validated['change_amount'])) {
            $billing = AccountantBilling::find($validated['billing_id']);
            $validated['change_amount'] = max(0, $validated['amount'] - $billing->total_amount);
        }

        $payment = Payment::create($validated);

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => $payment->load('billing')
        ], 201);
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        return response()->json($payment->load('billing'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'billing_id' => 'sometimes|required|exists:billings,id',
            'amount' => 'sometimes|required|numeric|min:0.01',
            'change_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'sometimes|required|date',
        ]);

        $payment->update($validated);

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => $payment->load('billing')
        ]);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ]);
    }

    /**
     * Process payment from the modal
     */
    public function processPayment(Request $request)
{
    $validated = $request->validate([
        'billing_id' => 'required|exists:accountant_billings,id',
        'payment_amount' => 'required|numeric|min:0.01',
        'payment_date' => 'required|date|before_or_equal:today',
    ]);

    try {
        DB::beginTransaction();

        $billing = AccountantBilling::lockForUpdate()->findOrFail($validated['billing_id']); 
        // lockForUpdate prevents race conditions

        // ✅ Prevent duplicate payment
        if ($billing->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This bill has already been paid.'
            ], 400);
        }

        // ✅ Prevent underpayment
        if ($validated['payment_amount'] < $billing->total_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Payment cannot be less than the total amount due (₱' . number_format($billing->total_amount, 2) . ')'
            ], 400);
        }

        $payment = Payment::create([
            'billing_id' => $validated['billing_id'],
            'amount' => $validated['payment_amount'],
            'change_amount' => max(0, $validated['payment_amount'] - $billing->total_amount),
            'payment_date' => $validated['payment_date'],
        ]);

        // ✅ Mark billing as paid
        $billing->update(['status' => 'paid']);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'payment' => $payment,
            'billing' => $billing
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Payment processing failed: ' . $e->getMessage()
        ], 500);
    } 
}

}