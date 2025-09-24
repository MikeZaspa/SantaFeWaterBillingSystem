<?php

namespace App\Http\Controllers;

use App\Models\AdminConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminConsumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumers = AdminConsumer::all();
        return view('auth.admin-consumer', compact('consumers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // In your BillingController or relevant controller
public function create()
{
    $consumers = AdminConsumer::select('id', 'first_name', 'middle_name', 'last_name', 'suffix', 'meter_no', 'consumer_type')
                             ->orderBy('last_name')
                             ->orderBy('first_name')
                             ->get();
    
    return response()->json($consumers);
}

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:20',
            'contact_number' => 'required|string|max:20|regex:/^09\d{9}$/',
            'meter_no' => 'required|string|unique:admin_consumers|max:50|regex:/^[\d-]+$/',
            'address' => 'required|string|max:500',
            'address_information' => 'nullable|string|max:1000',
            'connection_date' => 'required|date',
            'consumer_type' => 'required|in:residential,commercial,institutional',
            'status' => 'required|in:active,inactive,disconnected,cut',
        ]);

        // Set default connection date if empty
        if (empty($validated['connection_date'])) {
            $validated['connection_date'] = Carbon::now()->format('Y-m-d');
        }

        $consumer = AdminConsumer::create($validated);
         
        return response()->json([
            'message' => 'Consumer created successfully',
            'consumer' => $consumer
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(AdminConsumer $adminConsumer)
    {
        return response()->json($adminConsumer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $adminConsumer = AdminConsumer::findOrFail($id);
        return response()->json($adminConsumer);
        
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, AdminConsumer $adminConsumer)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:10',
        'contact_number' => 'required|string|max:20',
        'meter_no' => 'required|string|max:50|unique:admin_consumers,meter_no,'.$adminConsumer->id,
        'address' => 'required|string',
        'address_information' => 'nullable|string',
        'connection_date' => 'required|date',
        'consumer_type' => 'required|string',
        'status' => 'required|string'
    ]);

    $adminConsumer->update($validated);

    return response()->json([
        'message' => 'Consumer updated successfully',
        'consumer' => $adminConsumer
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminConsumer $adminConsumer)
    {
        // Store the ID being deleted
        $deletedId = $adminConsumer->id;
        
        // Delete the record
        $adminConsumer->delete();
        
        // Get all remaining consumers ordered by current ID
        $remainingConsumers = AdminConsumer::orderBy('id')->get();
        
        // Renumber the IDs
        $newId = 1;
        foreach ($remainingConsumers as $consumer) {
            if ($consumer->id != $newId) {
                $consumer->id = $newId;
                $consumer->save();
            }
            $newId++;
        }
        
        // Reset auto-increment value
        \DB::statement('ALTER TABLE admin_consumers AUTO_INCREMENT = ' . ($newId));
        
        return response()->json([
            'message' => 'Consumer deleted successfully'
        ]);
    }
    
}