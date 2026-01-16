@extends('layouts.app')

@section('title', 'Edit Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Metode Pembayaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Metode Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payment-methods.update', $paymentMethod) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Metode *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $paymentMethod->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Metode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik Metode</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Total Transaksi dengan Metode Ini:</h6>
                    <div class="display-4">{{ $paymentMethod->transactions->count() }}</div>
                </div>
                
                <div class="mb-4">
                    <h6>Total Pemasukan:</h6>
                    <div class="display-5 text-success">
                        Rp {{ number_format($paymentMethod->transactions->sum('total'), 0, ',', '.') }}
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Perhatian:</h6>
                    <p class="mb-0">
                        Mengubah nama metode akan mempengaruhi semua transaksi yang menggunakan metode ini.
                        Pastikan perubahan sudah benar.
                    </p>
                </div>
                
                <div class="mt-3">
                    <h6>Transaksi Terbaru:</h6>
                    <ul class="list-group list-group-flush">
                        @foreach($paymentMethod->transactions()->latest()->take(3)->get() as $transaction)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ $transaction->patient->name }}</span>
                                <span class="fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                            </div>
                            <small class="text-muted">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection