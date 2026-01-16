<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Patient;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $transactions = Transaction::with(['patient', 'paymentMethod', 'user'])
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('transaction_date', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('transaction_date', '<=', $end_date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('transactions.index', compact('transactions', 'search', 'status', 'start_date', 'end_date'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        
        return view('transactions.create', compact('patients', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'description' => 'required|string',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,pending',
            'transaction_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        $patients = Patient::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();
        
        return view('transactions.edit', compact('transaction', 'patients', 'paymentMethods'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'description' => 'required|string',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,pending',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}