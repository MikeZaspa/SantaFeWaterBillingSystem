<?php

namespace App\Http\Controllers;

use App\Models\WaterRate;
use Illuminate\Http\Request;

class WaterRateController extends Controller
{
    public function index()
    {
        $rates = WaterRate::orderBy('type')->orderBy('range')->get();
        return view('auth.water-rates', compact('rates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:residential,commercial,institutional',
            'range' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0'
        ]);

        WaterRate::create($validated);

        return redirect()->route('water-rates.index')
            ->with('success', 'Water rate created successfully.');
    }

    public function edit(WaterRate $waterRate)
    {
        $rates = WaterRate::orderBy('type')->orderBy('range')->get();
        return view('auth.water-rates', compact('waterRate', 'rates'));
    }

    public function update(Request $request, WaterRate $waterRate)
    {
        $validated = $request->validate([
            'type' => 'required|in:residential,commercial,institutional',
            'range' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0'
        ]);

        $waterRate->update($validated);

        return redirect()->route('water-rates.index')
            ->with('success', 'Water rate updated successfully.');
    }

    public function destroy(WaterRate $waterRate)
    {
        $waterRate->delete();

        return redirect()->route('water-rates.index')
            ->with('success', 'Water rate deleted successfully.');
    }

public function calculateAmount($type, $consumption)
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
public function calculateBill(Request $request)
{
    $request->validate([
        'type' => 'required|in:residential,commercial,institutional',
        'consumption' => 'required|numeric|min:0'
    ]);
    
    try {
        $amount = $this->calculateAmount($request->type, $request->consumption);
        
        return response()->json([
            'success' => true,
            'amount' => $amount  // Changed from data.amount to just amount
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}
   
}