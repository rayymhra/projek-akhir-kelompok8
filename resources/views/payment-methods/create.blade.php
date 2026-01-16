@extends('layouts.app')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-plus-circle me-2"></i>Tambah Metode Pembayaran</h1>
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
                <h5 class="card-title mb-0">Form Metode Pembayaran</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payment-methods.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Metode *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Cash, Transfer, QRIS, DANA">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3"
                                  placeholder="Keterangan tambahan tentang metode pembayaran">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Metode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Metode Pembayaran Umum</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Cash</h6>
                            <small class="text-muted">Tunai</small>
                        </div>
                        <p class="mb-1 small">Pembayaran menggunakan uang tunai di lokasi</p>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Transfer Bank</h6>
                            <small class="text-muted">E-banking</small>
                        </div>
                        <p class="mb-1 small">Transfer melalui mobile/Internet banking</p>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">QRIS</h6>
                            <small class="text-muted">QR Code</small>
                        </div>
                        <p class="mb-1 small">Pembayaran dengan scan QR Code standar Indonesia</p>
                    </div>
                    
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">E-Wallet</h6>
                            <small class="text-muted">Dana/OVO/etc</small>
                        </div>
                        <p class="mb-1 small">Pembayaran melalui aplikasi e-wallet</p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle me-2"></i>Tips:</h6>
                    <ul class="mb-0 ps-3 small">
                        <li>Gunakan nama yang mudah dikenali</li>
                        <li>Tambahkan deskripsi jika diperlukan</li>
                        <li>Metode dapat diedit atau dihapus nanti</li>
                        <li>Pastikan metode sudah tersedia di klinik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection