@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Panel Admin Klinik')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-custom p-3 border-start border-4 border-primary h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold">Total Pasien</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ $totalPasien }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-people text-primary fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-custom p-3 border-start border-4 border-success h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold">Dokter Aktif</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $totalDokter }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-person-badge text-success fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-custom p-3 border-start border-4 border-warning h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold">Perlu Verifikasi</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ $reservasiPending }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-bell text-warning fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-custom p-3 border-start border-4 border-info h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold">Total Reservasi</h6>
                    <h3 class="fw-bold text-info mb-0">{{ $totalReservasi }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                    <i class="bi bi-journal-medical text-info fs-3"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-check-circle me-2"></i>Verifikasi Reservasi Baru</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Pasien</th>
                                <th>Dokter Tujuan</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingReservations as $res)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($res->schedule->date)->format('d M Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ substr($res->schedule->start_time, 0, 5) }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $res->patient->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $res->patient->phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border border-primary">
                                            {{ $res->schedule->doctor->name }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $res->schedule->doctor->specialization }}</small>
                                    </td>
                                    <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                                    <td class="text-end pe-4">
                                        <form action="{{ url('/staff/reservasi/'.$res->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button class="btn btn-sm btn-success me-1" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                        </form>

                                        <form action="{{ url('/staff/reservasi/'.$res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak reservasi ini?');">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button class="btn btn-sm btn-danger" title="Tolak"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-check fs-1 d-block mb-2 opacity-25"></i>
                                        Tidak ada reservasi yang perlu diverifikasi saat ini.
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