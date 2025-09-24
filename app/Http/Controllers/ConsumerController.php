<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumerAccount;

class ConsumerController extends Controller
{
    // Your controller methods here
    public function history()
    {
        $consumer = auth()->guard('consumer')->user();
        $history = []; // Add your logic to get consumer history
        
        return view('auth.consumer-history', compact('consumer', 'history'));
    }
}