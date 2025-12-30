@extends('layouts.app')

@section('title', 'Data Pasien')
@section('page-title', 'Database Pasien')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-people me-2"></i>Daftar Pasien Terdaftar</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Pasien</th>
                                <th>NIK</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Kontak</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $p)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $p->name }}</div>
                                        <small class="text-muted">{{ $p->address }}</small>
                                    </td>
                                    <td>{{ $p->nik }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->birth_date)->format('d M Y') }}</td>
                                    <td>
                                        @if($p->gender == 'L') <span class="badge bg-primary">Laki-laki</span>
                                        @else <span class="badge bg-danger">Perempuan</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->phone }}</td>
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">Belum ada pasien terdaftar.</td>
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