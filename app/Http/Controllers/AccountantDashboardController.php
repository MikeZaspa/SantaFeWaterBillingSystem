<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountantBilling;
use Illuminate\Support\Facades\DB;

class AccountantDashboardController extends Controller
{
    public function index()
    {
        // Get counts for different billing statuses
        $paidCount = AccountantBilling::where('status', 'paid')->count();
        $unpaidCount = AccountantBilling::where('status', 'unpaid')->count();
        $overdueCount = AccountantBilling::where('status', 'overdue')->count();
        
        // Use the correct column name that exists in your table
        $totalIncome = AccountantBilling::where('status', 'paid')->sum('total_amount'); // or whatever your amount column is named

        // Calculate monthly revenue data
        $monthlyRevenueData = AccountantBilling::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'paid')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Prepare data for the chart
        $monthlyLabels = [];
        $monthlyRevenue = [];
        
        // Get the last 12 months
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        
        for ($i = 11; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            
            if ($month < 1) {
                $month += 12;
                $year -= 1;
            }
            
            $monthName = date('M', mktime(0, 0, 0, $month, 1));
            $monthlyLabels[] = $monthName . ' ' . $year;
            
            // Find revenue for this month
            $revenue = $monthlyRevenueData->first(function($item) use ($month, $year) {
                return (int)$item->month === $month && (int)$item->year === $year;
            });
            
            $monthlyRevenue[] = $revenue ? $revenue->total : 0;
        }

        return view('auth.admin-accountant-dashboard', compact(
            'paidCount',
            'unpaidCount',
            'overdueCount',
            'totalIncome',
            'monthlyLabels',
            'monthlyRevenue'
        ));
    }
}