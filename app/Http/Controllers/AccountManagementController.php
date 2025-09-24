<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminConsumer;
use App\Models\ConsumerAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountManagementController extends Controller
{
    // Get list of accounts for DataTable
    public function index()
    {
        $accounts = ConsumerAccount::with('consumer')
            ->select('consumer_accounts.*')
            ->latest();
            
        return datatables()->eloquent($accounts)->toJson();
    }

    // Get consumers without accounts
    public function data()
    {
        try {
            $consumers = AdminConsumer::select('id', 'first_name', 'middle_name', 'last_name', 'suffix', 'meter_no')
                ->whereDoesntHave('account')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
                
            return $consumers;
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch consumers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Store new account
    public function store(Request $request)
{
    try {
        $request->validate([
            'consumer_id' => 'required|exists:admin_consumers,id|unique:consumer_accounts,consumer_id',
            'username' => 'required|string|unique:consumer_accounts,username',
            'password' => 'required|string|min:8'
        ]);

        $account = ConsumerAccount::create([
            'consumer_id' => $request->consumer_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'created_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'data' => $account
        ]);

    } catch (\Exception $e) {
        \Log::error('Account creation failed: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to create account',
            'error' => $e->getMessage(),
            'validation_errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null
        ], 500);
    }
}

    public function edit($id)
    {
        try {
            $account = ConsumerAccount::with('consumer')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $account
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Account not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'password' => 'sometimes|string|min:8'
    ]);

    try {
        $account = ConsumerAccount::findOrFail($id);

        $updateData = [
            'updated_by' => Auth::id()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $account->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully',
            'data' => $account
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update account',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
{
    try {
        \Log::info("Attempting to delete account ID: $id");
        
        $account = ConsumerAccount::findOrFail($id);
        \Log::info("Account found:", $account->toArray());
        
        $account->delete();
        \Log::info("Account deleted successfully");

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Failed to delete account ID: $id - " . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete account',
            'error' => $e->getMessage()
        ], 500);
    }
}
}