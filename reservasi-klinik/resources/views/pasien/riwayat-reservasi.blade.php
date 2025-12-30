@extends('layouts.app')

@section('title', 'Riwayat Berobat')
@section('page-title', 'Riwayat Kunjungan Saya')

@section('content')
<div class="row">
    <div class="col-md-12">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card card-custom p-3 mb-4">
            <form action="" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama dokter, keluhan, atau diagnosis..." value="{{ request('search') }}">
                    <button class="btn btn-primary fw-bold" type="submit">Cari Riwayat</button>
                </div>
            </form>
        </div>

        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-clock-history me-2"></i>Daftar Kunjungan Saya
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Dokter & Poli</th>
                                <th>Keluhan</th>
                                <th style="width: 20%;">Hasil Diagnosis</th>
                                <th style="width: 20%;">Catatan Dokter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($res->schedule->date)->format('d M Y') }}</div>
                                        <small class="text-muted">{{ substr($res->schedule->start_time, 0, 5) }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $res->schedule->doctor->name }}</div>
                                        <span class="badge bg-light text-primary border border-primary text-uppercase" style="font-size: 0.65rem;">
                                            {{ $res->schedule->doctor->specialization }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted fst-italic">"{{ Str::limit($res->symptoms, 30) }}"</small>
                                    </td>
                                    
                                    <td>
                                        @if($res->diagnosis)
                                            <div class="alert alert-info py-1 px-2 mb-0 small border-0 text-dark">
                                                {{ $res->diagnosis }}
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($res->admin_note)
                                            <span class="text-muted small">{{ $res->admin_note }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($res->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($res->status == 'disetujui')
                                            <span class="badge bg-primary">Disetujui</span>
                                        @elseif($res->status == 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Batal/Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Kosong" width="60" class="mb-3 opacity-50">
                                        <p class="text-muted fw-bold mb-1">Belum ada riwayat kunjungan.</p>
                                        <a href="{{ url('/pasien/reservasi') }}" class="btn btn-sm btn-link text-decoration-none">
                                            Buat janji sekarang &rarr;
                                        </a>
                                    </td>
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