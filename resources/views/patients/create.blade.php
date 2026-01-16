@extends('layouts.app')

@section('title', 'Tambah Pasien Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-user-plus me-2"></i>Tambah Pasien Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Data Pasien</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required 
                               placeholder="Masukkan nama lengkap pasien">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap *</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required
                                  placeholder="Masukkan alamat lengkap pasien">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Pasien
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
                    <h6><i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Pastikan data diisi dengan benar</li>
                        <li>Nomor telepon harus aktif</li>
                        <li>Alamat diisi selengkap mungkin</li>
                        <li>Data pasien dapat diedit nanti jika diperlukan</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Total Pasien Saat Ini:</h6>
                    <p class="fs-4">{{ \App\Models\Patient::count() }} pasien</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Set default date to 30 years ago
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('birth_date').value) {
            const today = new Date();
            const thirtyYearsAgo = new Date(today.getFullYear() - 30, today.getMonth(), today.getDate());
            document.getElementById('birth_date').value = thirtyYearsAgo.toISOString().split('T')[0];
        }
        
        // Format phone number input
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (!value.startsWith('0')) {
                    value = '0' + value;
                }
            }
            e.target.value = value;
        });
    });
</script>
@endpush