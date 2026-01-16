@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stat-card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Transaksi Hari Ini</h6>
                        <h2 class="card-title">{{ $totalTransactions }}</h2>
                    </div>
                    <div class="bg-primary rounded-circle p-3">
                        <i class="fas fa-exchange-alt fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Pemasukan Hari Ini</h6>
                        <h2 class="card-title">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                    </div>
                    <div class="bg-success rounded-circle p-3">
                        <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Metode Pembayaran Terpopuler</h6>
                        @if($paymentMethods->isNotEmpty())
                            <h2 class="card-title">{{ $paymentMethods->first()->name }}</h2>
                        @else
                            <h2 class="card-title">-</h2>
                        @endif
                    </div>
                    <div class="bg-info rounded-circle p-3">
                        <i class="fas fa-chart-pie fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('patients.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pasien
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('transactions.create') }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-plus-circle me-2"></i>Transaksi Baru
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('transactions.index') }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-history me-2"></i>Riwayat Transaksi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Transaksi Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pasien</th>
                                <th>Deskripsi</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->patient->name }}</td>
                                <td>{{ Str::limit($transaction->description, 30) }}</td>
                                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td>{{ $transaction->paymentMethod->name }}</td>
                                <td>
                                    @if($transaction->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection