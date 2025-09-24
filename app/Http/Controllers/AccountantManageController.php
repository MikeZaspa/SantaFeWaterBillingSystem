<?php

namespace App\Http\Controllers;

use App\Models\Accountant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountantManageController extends Controller
{
    public function index() 
    {
        $accountants = Accountant::all();
        return view('auth.admin-accountant', compact('accountants'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:admin_accountants,username|alpha_dash|min:3|max:20',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'contact_number' => 'required|string|max:11|regex:/^09\d{9}$/',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $accountant = Accountant::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'status' => $request->status
            ]);

            return response()->json([
                'message' => 'Accountant created successfully',
                'data' => $accountant
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create accountant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $accountant = Accountant::findOrFail($id);
            return response()->json($accountant);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Accountant not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $accountant = Accountant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => 'required|alpha_dash|min:3|max:20|unique:admin_accountants,username,' . $id,
            'password' => 'nullable|min:8',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:10',
            'contact_number' => 'required|string|max:11|regex:/^09\d{9}$/',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $updateData = [
                'username' => $request->username,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'status' => $request->status
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $accountant->update($updateData);

            return response()->json([
                'message' => 'Accountant updated successfully',
                'data' => $accountant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update accountant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $accountant = Accountant::findOrFail($id);
            $accountant->delete();

            return response()->json([
                'message' => 'Accountant deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete accountant',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}