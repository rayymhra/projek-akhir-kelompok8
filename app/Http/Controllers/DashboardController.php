<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
        
        $totalTransactions = Transaction::whereDate('created_at', $today)->count();
        $totalIncome = Transaction::whereDate('created_at', $today)
            ->where('status', 'lunas')
            ->sum('total');
        
        $paymentMethods = Transaction::select('payment_methods.name', DB::raw('COUNT(*) as count'))
            ->join('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')
            ->whereDate('transactions.created_at', $today)
            ->groupBy('payment_methods.name')
            ->orderBy('count', 'desc')
            ->get();

        $recentTransactions = Transaction::with(['patient', 'paymentMethod', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalTransactions',
            'totalIncome',
            'paymentMethods',
            'recentTransactions'
        ));
    }
}