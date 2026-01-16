@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-exchange-alt me-2"></i>Riwayat Transaksi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Transaksi Baru
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Nama Pasien</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nama pasien..." value="{{ request('search') }}">
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" 
                           value="{{ request('start_date') }}">
                </div>
                
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" 
                           value="{{ request('end_date') }}">
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        
                        @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Reset Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Pasien</th>
                        <th>Deskripsi</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->patient->name }}</td>
                        <td>{{ Str::limit($transaction->description, 40) }}</td>
                        <td class="fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>{{ $transaction->paymentMethod->name }}</td>
                        <td>
                            @if($transaction->status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $transaction->user->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    @if($transactions->isNotEmpty())
                    <tr class="table-info">
                        <td colspan="4" class="text-end fw-bold">Total:</td>
                        <td class="fw-bold">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</td>
                        <td colspan="4"></td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} 
                dari {{ $transactions->total() }} transaksi
            </div>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Transaksi</h6>
                        <h2 class="mb-0">{{ $transactions->total() }}</h2>
                    </div>
                    <i class="fas fa-exchange-alt fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Lunas</h6>
                        <h2 class="mb-0">{{ $transactions->where('status', 'lunas')->count() }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pending</h6>
                        <h2 class="mb-0">{{ $transactions->where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Pemasukan</h6>
                        <h4 class="mb-0">Rp {{ number_format($transactions->where('status', 'lunas')->sum('total'), 0, ',', '.') }}</h4>
                    </div>
                    <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection