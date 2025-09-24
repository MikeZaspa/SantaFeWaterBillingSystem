<?php

namespace App\Http\Controllers;

use App\Models\Plumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PlumberController extends Controller
{
    public function index() 
    {
        $plumbers = Plumber::all();
        return view('auth.admin-plumber', compact('plumbers'));
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'suffix'          => 'nullable|string|max:20',
            'contact_number'  => 'required|string|regex:/^09\d{9}$/|max:11',
            'address'         => 'required|string|max:500',
            'username'        => 'required|string|max:255|unique:admin_plumbers,username',
            'password'        => 'required|string|min:8|confirmed',
            'status'          => 'required|in:active,inactive',
        ]);

        // Set null for empty optional fields
        $validated['middle_name'] = $validated['middle_name'] ?? null;
        $validated['suffix']      = $validated['suffix'] ?? null;

        // Hash password before saving
        $validated['password'] = Hash::make($validated['password']);

        $plumber = Plumber::create($validated);

        return response()->json([
            'message' => 'Plumber created successfully',
            'plumber' => $plumber
        ]);
    }

    public function edit($id) 
    {
        $plumber = Plumber::findOrFail($id);
        return response()->json($plumber);
    }

    public function update(Request $request, $id) 
    {
        $plumber = Plumber::findOrFail($id);

        $validated = $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'suffix'          => 'nullable|string|max:20',
            'contact_number'  => 'required|string|regex:/^09\d{9}$/|max:11',
            'address'         => 'required|string|max:500',
            'username'        => 'required|string|max:255|unique:admin_plumbers,username,' . $id,
            'password'        => 'nullable|string|min:8|confirmed',
            'status'          => 'required|in:active,inactive',
        ]);

        $validated['middle_name'] = $validated['middle_name'] ?? null;
        $validated['suffix']      = $validated['suffix'] ?? null;

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $plumber->update($validated);

        return response()->json([
            'message' => 'Plumber updated successfully',
            'plumber' => $plumber
        ]);
    }

    public function destroy($id)
    {
        $plumber = Plumber::findOrFail($id);
        $plumber->forceDelete(); // permanent delete

        return response()->json([
            'message' => 'Plumber permanently deleted'
        ]);
    }
}
