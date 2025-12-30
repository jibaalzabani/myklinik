@extends('layouts.app')

@section('title', 'Riwayat Periksa')
@section('page-title', 'Rekam Medis Pasien')

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card card-custom p-3 mb-4">
            <form action="" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama pasien atau diagnosis..." value="{{ request('search') }}">
                    <button class="btn btn-primary fw-bold" type="submit">Cari Data</button>
                </div>
            </form>
        </div>

        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-journal-medical me-2"></i>Daftar Pemeriksaan Selesai</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Nama Pasien</th>
                                <th style="width: 20%;">Diagnosis</th>
                                <th style="width: 20%;">Resep / Tindakan</th>
                                <th style="width: 20%;">Catatan Dokter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedReservations as $res)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($res->schedule->date)->format('d M Y') }}</div>
                                        <small class="text-muted">{{ substr($res->schedule->start_time, 0, 5) }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $res->patient->name }}</div>
                                        <small class="text-muted">Keluhan: {{ Str::limit($res->symptoms, 20) }}</small>
                                    </td>
                                    
                                    <td>
                                        @if($res->diagnosis)
                                            <div class="alert alert-info py-1 px-2 mb-0 small border-0 text-dark">
                                                {{ $res->diagnosis }}
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">- Belum diisi -</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $res->resep_obat ?? '-' }}
                                    </td>

                                    <td>
                                        @if($res->admin_note)
                                            <span class="text-muted small fst-italic">"{{ $res->admin_note }}"</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x fs-1 d-block mb-2 opacity-25"></i>
                                        Belum ada riwayat pemeriksaan yang selesai.
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