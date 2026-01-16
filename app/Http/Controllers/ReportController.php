<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->format('Y-m'));

        if ($period === 'daily') {
            $transactions = Transaction::with(['patient', 'paymentMethod'])
                ->whereDate('transaction_date', $date)
                ->where('status', 'lunas')
                ->orderBy('transaction_date')
                ->get();

            $summary = Transaction::select(
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total) as total_income')
                )
                ->whereDate('transaction_date', $date)
                ->where('status', 'lunas')
                ->first();

            $paymentSummary = Transaction::select(
                    'payment_methods.name',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total) as total')
                )
                ->join('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')
                ->whereDate('transaction_date', $date)
                ->where('status', 'lunas')
                ->groupBy('payment_methods.name')
                ->get();

        } else {
            $transactions = Transaction::with(['patient', 'paymentMethod'])
                ->whereYear('transaction_date', substr($month, 0, 4))
                ->whereMonth('transaction_date', substr($month, 5, 2))
                ->where('status', 'lunas')
                ->orderBy('transaction_date')
                ->get();

            $summary = Transaction::select(
                    DB::raw('COUNT(*) as total_transactions'),
                    DB::raw('SUM(total) as total_income')
                )
                ->whereYear('transaction_date', substr($month, 0, 4))
                ->whereMonth('transaction_date', substr($month, 5, 2))
                ->where('status', 'lunas')
                ->first();

            $paymentSummary = Transaction::select(
                    'payment_methods.name',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total) as total')
                )
                ->join('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')
                ->whereYear('transaction_date', substr($month, 0, 4))
                ->whereMonth('transaction_date', substr($month, 5, 2))
                ->where('status', 'lunas')
                ->groupBy('payment_methods.name')
                ->get();
        }

        return view('reports.index', compact(
            'period',
            'date',
            'month',
            'transactions',
            'summary',
            'paymentSummary'
        ));
    }
}