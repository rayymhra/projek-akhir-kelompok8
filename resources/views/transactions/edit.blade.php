@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-edit me-2"></i>Edit Transaksi</h1>
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
                <h5 class="card-title mb-0">Edit Data Transaksi</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patient_id" class="form-label">Pasien *</label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                        {{ old('patient_id', $transaction->patient_id) == $patient->id ? 'selected' : '' }}>
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
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" 
                                        {{ old('payment_method_id', $transaction->payment_method_id) == $method->id ? 'selected' : '' }}>
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
                                  id="description" name="description" rows="3" required>{{ old('description', $transaction->description) }}</textarea>
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
                                       value="{{ old('total', $transaction->total) }}" required>
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="lunas" {{ old('status', $transaction->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
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
                               value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d\TH:i')) }}" required>
                        @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>ID Transaksi:</h6>
                    <p class="fs-5">{{ $transaction->id }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Dibuat Oleh:</h6>
                    <p>{{ $transaction->user->name }} ({{ $transaction->user->role }})</p>
                </div>
                
                <div class="mb-3">
                    <h6>Tanggal Dibuat:</h6>
                    <p>{{ $transaction->created_at->format('d F Y H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6>Terakhir Diupdate:</h6>
                    <p>{{ $transaction->updated_at->format('d F Y H:i') }}</p>
                </div>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Catatan:</h6>
                    <p class="mb-0">Ubah status menjadi "Lunas" setelah pembayaran diterima.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format currency input
        document.getElementById('total').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value === '') value = '0';
            e.target.value = parseInt(value);
        });
    });
</script>
@endpush