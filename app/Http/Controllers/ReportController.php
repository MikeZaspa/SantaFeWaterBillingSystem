<?php

namespace App\Http\Controllers;

use App\Models\AccountantBilling;
use App\Models\AdminConsumer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaidBillsExport;

class ReportController extends Controller
{
    public function data(Request $request)
{
    try {
        $query = AccountantBilling::with('consumer')
            ->where('status', 'paid')
            ->select('accountant_billings.*');

        if ($request->has('month') && $request->month != '') {
            $month = Carbon::parse($request->month);
            $query->whereMonth('due_date', $month->month)
                  ->whereYear('due_date', $month->year);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('consumer_name', function($row) {
                return $row->consumer ? $row->consumer->first_name.' '.$row->consumer->last_name : 'N/A';
            })
            ->addColumn('meter_no', function($row) {
                return $row->meter_no ?: ($row->consumer ? $row->consumer->meter_number : 'N/A');
            })
            ->addColumn('status', function($row) {
                return '<span class="badge badge-paid">PAID</span>';
            })
            ->rawColumns(['status'])
            ->make(true);

    } catch (\Exception $e) {
        \Log::error('Report data error: '.$e->getMessage());
        return response()->json([
            'error' => 'Failed to load report data: '.$e->getMessage()
        ], 500);
    }
}

    public function export(Request $request)
    {
        $format = $request->format ?? 'excel';
        $month = $request->month ? Carbon::parse($request->month) : null;

        $query = AccountantBilling::with('consumer')
            ->where('status', 'paid')
            ->orderBy('due_date', 'desc');

        if ($month) {
            $query->whereMonth('due_date', $month->month)
                  ->whereYear('due_date', $month->year);
        }

        $data = $query->get()->map(function($item) {
            return [
                'ID' => $item->id,
                'Consumer' => $item->consumer ? $item->consumer->first_name.' '.$item->consumer->last_name : 'N/A',
                'Meter No.' => $item->meter_no ?: ($item->consumer ? $item->consumer->meter_number : 'N/A'),
                'Due Date' => $item->due_date ? $item->due_date->format('M d, Y') : 'N/A',
                'Consumption (m³)' => number_format($item->consumption, 2),
                'Payment Method' => $item->formatted_payment_method['name'] ?? ucfirst($item->payment_method),
                'Total Amount' => '₱' . number_format($item->total_amount, 2),
                'Amount Paid' => '₱' . number_format($item->amount_paid, 2),
                'Status' => 'Paid'
            ];
        });

        $filename = 'paid_bills_report_' . ($month ? $month->format('Y_m') : 'all_time');

        if ($format === 'pdf') {
            $pdf = PDF::loadView('exports.report', ['data' => $data]);
            return $pdf->download("$filename.pdf");
        } elseif ($format === 'csv') {
            return response()->streamDownload(function() use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, array_keys($data[0]));
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            }, "$filename.csv");
        } else {
            return Excel::download(new PaidBillsExport($data), "$filename.xlsx");
        }
    }
}