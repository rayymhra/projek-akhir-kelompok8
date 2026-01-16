@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-exchange-alt me-2"></i>Transaksi Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Transaksi</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transactions.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patient_id" class="form-label">Pasien *</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                <option value="">Pilih Pasien</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                        {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ $patient->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="payment_method_id" class="form-label">Metode Pembayaran *</label>
                            <select class="form-select @error('payment_method_id') is-invalid @enderror" 
                                    id="payment_method_id" name="payment_method_id" required>
                                <option value="">Pilih Metode</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" 
                                        {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Layanan *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required
                                  placeholder="Contoh: Konsultasi dokter umum, obat-obatan, dll.">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total" class="form-label">Total Biaya *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('total') is-invalid @enderror" 
                                       id="total" name="total" min="0" step="100" 
                                       value="{{ old('total') }}" required>
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Tanggal Transaksi *</label>
                        <input type="datetime-local" class="form-control @error('transaction_date') is-invalid @enderror" 
                               id="transaction_date" name="transaction_date" 
                               value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Petunjuk:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Pastikan data pasien sudah terdaftar</li>
                        <li>Isi deskripsi layanan secara lengkap</li>
                        <li>Status "Lunas" untuk pembayaran yang sudah diterima</li>
                        <li>Status "Pending" untuk pembayaran yang belum lunas</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Total Pasien:</h6>
                    <p class="fs-4">{{ $patients->count() }} pasien</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set default transaction time to now
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('transaction_date').value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            document.getElementById('transaction_date').value = 
                `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    });
</script>
@endpush