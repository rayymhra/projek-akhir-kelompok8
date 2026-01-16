@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-users me-2"></i>Data Pasien</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pasien
        </a>
    </div>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama atau nomor telepon..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    @if(request('search'))
                        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Patients Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Tanggal Lahir</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>{{ $loop->iteration + (($patients->currentPage() - 1) * $patients->perPage()) }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>{{ Str::limit($patient->address, 30) }}</td>
                        <td>{{ $patient->birth_date->format('d/m/Y') }}</td>
                        <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('patients.edit', $patient) }}" 
                                   class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus pasien ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <a href="{{ route('transactions.create') }}?patient_id={{ $patient->id }}" 
                                   class="btn btn-success" title="Buat Transaksi">
                                    <i class="fas fa-plus"></i> Transaksi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data pasien</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection