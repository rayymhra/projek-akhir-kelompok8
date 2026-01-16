@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>Laporan Keuangan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-outline-primary me-2" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="period" class="form-label">Periode Laporan</label>
                    <select name="period" id="period" class="form-select" onchange="toggleDateInputs()">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                
                <div class="col-md-3" id="daily-input" style="display: {{ $period == 'daily' ? 'block' : 'none' }}">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                </div>
                
                <div class="col-md-3" id="monthly-input" style="display: {{ $period == 'monthly' ? 'block' : 'none' }}">
                    <label for="month" class="form-label">Bulan</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Transaksi</h6>
                        <h2 class="mb-0">{{ $summary->total_transactions ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-exchange-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pemasukan</h6>
                        <h2 class="mb-0">Rp {{ number_format($summary->total_income ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Rata-rata per Transaksi</h6>
                        @if(($summary->total_transactions ?? 0) > 0)
                            <h2 class="mb-0">Rp {{ number_format(($summary->total_income ?? 0) / ($summary->total_transactions ?? 1), 0, ',', '.') }}</h2>
                        @else
                            <h2 class="mb-0">Rp 0</h2>
                        @endif
                    </div>
                    <i class="fas fa-calculator fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods Chart -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Metode Pembayaran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <th class="text-end">Jumlah Transaksi</th>
                                <th class="text-end">Total (Rp)</th>
                                <th class="text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAll = $summary->total_income ?? 0;
                            @endphp
                            @foreach($paymentSummary as $payment)
                            <tr>
                                <td>{{ $payment->name }}</td>
                                <td class="text-end">{{ $payment->count }}</td>
                                <td class="text-end">Rp {{ number_format($payment->total, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    @if($totalAll > 0)
                                        {{ number_format(($payment->total / $totalAll) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Pembayaran</h5>
            </div>
            <div class="card-body">
                <canvas id="paymentChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Transactions -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            Detail Transaksi 
            @if($period == 'daily')
                ({{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }})
            @else
                ({{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }})
            @endif
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Deskripsi</th>
                        <th>Metode</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->patient->name }}</td>
                        <td>{{ Str::limit($transaction->description, 40) }}</td>
                        <td>{{ $transaction->paymentMethod->name }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transactions->isNotEmpty())
                <tfoot class="table-success">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">Total Pemasukan:</td>
                        <td class="text-end fw-bold">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style media="print">
    .sidebar, .btn-toolbar, .card-header button, .filter-form {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    h1, h2, h5 {
        color: #000 !important;
    }
</style>
@endsection

@push('scripts')
<script>
    function toggleDateInputs() {
        const period = document.getElementById('period').value;
        document.getElementById('daily-input').style.display = period === 'daily' ? 'block' : 'none';
        document.getElementById('monthly-input').style.display = period === 'monthly' ? 'block' : 'none';
    }
    
    // Initialize Chart
    document.addEventListener('DOMContentLoaded', function() {
        const paymentData = @json($paymentSummary);
        const labels = paymentData.map(p => p.name);
        const data = paymentData.map(p => p.total);
        const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
        
        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.raw.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush