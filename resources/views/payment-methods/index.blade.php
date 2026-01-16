@extends('layouts.app')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-credit-card me-2"></i>Metode Pembayaran</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Metode
        </a>
    </div>
</div>

<div class="row">
    @foreach($paymentMethods as $method)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title">{{ $method->name }}</h5>
                        @if($method->description)
                            <p class="card-text text-muted">{{ $method->description }}</p>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('payment-methods.edit', $method) }}">
                                    <i class="fas fa-edit me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('payment-methods.destroy', $method) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" 
                                            onclick="return confirm('Hapus metode pembayaran ini?')">
                                        <i class="fas fa-trash me-2"></i> Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Dibuat:</small>
                        <small>{{ $method->created_at->format('d/m/Y') }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Jumlah Transaksi:</small>
                        <small>{{ $method->transactions->count() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($paymentMethods->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
        <h5>Belum ada metode pembayaran</h5>
        <p class="text-muted">Tambahkan metode pembayaran yang tersedia di klinik</p>
        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Metode Pertama
        </a>
    </div>
</div>
@endif

<!-- Add some default methods if none exist -->
@if($paymentMethods->isEmpty() && auth()->user()->isAdmin())
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Metode Pembayaran Default</h5>
    </div>
    <div class="card-body">
        <p>Klik tombol di bawah untuk menambahkan metode pembayaran default:</p>
        <form action="{{ route('payment-methods.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <input type="hidden" name="name" value="Cash">
                    <button type="submit" class="btn btn-outline-success w-100 mb-2">
                        <i class="fas fa-money-bill-wave me-1"></i> Cash
                    </button>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="name" value="Transfer">
                    <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-university me-1"></i> Transfer
                    </button>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="name" value="QRIS">
                    <button type="submit" class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-qrcode me-1"></i> QRIS
                    </button>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="name" value="DANA">
                    <button type="submit" class="btn btn-outline-warning w-100 mb-2">
                        <i class="fas fa-wallet me-1"></i> DANA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection