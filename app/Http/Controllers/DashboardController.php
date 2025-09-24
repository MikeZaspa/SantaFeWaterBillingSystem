<?php
namespace App\Http\Controllers;

use App\Models\AdminConsumer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalConsumers = AdminConsumer::count();
        $activeConsumers = AdminConsumer::where('status', 'active')->count();
        $inactiveConsumers = AdminConsumer::where('status', 'inactive')->count();
        
        // Get monthly consumer growth data (this is a simplified example)
        $monthlyGrowth = AdminConsumer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count')
            ->toArray();

        return view('auth.admin-dashboard', compact(
            'totalConsumers',
            'activeConsumers',
            'inactiveConsumers',
            'monthlyGrowth'
        ));
    }
}