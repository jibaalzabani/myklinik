@extends('layouts.app')

@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard Dokter')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-custom bg-primary text-white p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0">{{ $pasienHariIni }}</h2>
                    <span class="small opacity-75">Pasien Hari Ini</span>
                </div>
                <i class="bi bi-people-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-custom bg-warning text-dark p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0">{{ $menungguKonfirmasi }}</h2>
                    <span class="small opacity-75">Menunggu Konfirmasi</span>
                </div>
                <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-custom bg-success text-white p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0">{{ $jadwalAktif }}</h2>
                    <span class="small opacity-75">Jadwal Aktif</span>
                </div>
                <i class="bi bi-calendar-check fs-1 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-calendar-event me-2"></i>Jadwal Praktek Hari Ini</h5>
                <span class="badge bg-light text-dark border">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Jam</th>
                                <th>Nama Pasien</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaysReservations as $res)
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        {{ substr($res->schedule->start_time, 0, 5) }} - {{ substr($res->schedule->end_time, 0, 5) }}
                                    </td>
                                    <td>{{ $res->patient->name }}</td>
                                    <td>{{ Str::limit($res->symptoms, 30) }}</td>
                                    <td>
                                        @if($res->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($res->status == 'disetujui')
                                            <span class="badge bg-success">Siap Periksa</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $res->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/dokter/periksa/'.$res->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        Periksa
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Tidak ada jadwal pasien hari ini.</td>
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