@extends('layouts.app')

@section('title', 'Daftar Pasien')
@section('page-title', 'Verifikasi Reservasi Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary">Permintaan Masuk</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tgl & Jam</th>
                                <th>Nama Pasien</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($res->schedule->date)->format('d M Y') }}</div>
                                        <small class="text-muted">{{ substr($res->schedule->start_time, 0, 5) }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $res->patient->name }}</div>
                                        <small class="text-muted">NIK: {{ $res->patient->nik ?? '-' }}</small>
                                    </td>
                                    <td>{{ Str::limit($res->symptoms, 40) }}</td>
                                    <td>
                                        @if($res->status == 'menunggu')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @else
                                            <span class="badge bg-primary">Disetujui</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($res->status == 'menunggu')
                                            <button type="button" class="btn btn-sm btn-success me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalPeriksa{{ $res->id }}"
                                                    title="Periksa & Terima">
                                                <i class="bi bi-check-lg"></i> Periksa
                                            </button>

                                            <form action="{{ url('/dokter/reservasi/'.$res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menolak pasien ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Terverifikasi</button>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalPeriksa{{ $res->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title fw-bold">
                                                    <i class="bi bi-clipboard-check me-2"></i>Verifikasi Pasien
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <div class="modal-body p-4">
                                                <div class="alert alert-light border text-center mb-3">
                                                    <h5 class="fw-bold mb-0">{{ $res->patient->name }}</h5>
                                                    <small class="text-muted">Rencana: {{ \Carbon\Carbon::parse($res->schedule->date)->format('d M Y') }}</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="fw-bold small text-muted">Keluhan Pasien:</label>
                                                    <div class="p-3 bg-light rounded border">
                                                        {{ $res->symptoms }}
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="fw-bold small text-muted">Data Kontak:</label>
                                                    <p class="mb-0"><i class="bi bi-telephone me-2"></i> {{ $res->patient->phone ?? '-' }}</p>
                                                    <p class="mb-0"><i class="bi bi-geo-alt me-2"></i> {{ $res->patient->address ?? '-' }}</p>
                                                </div>

                                                <p class="small text-danger fst-italic mt-3">
                                                    *Dengan menyetujui, pasien akan masuk ke daftar "Siap Periksa".
                                                </p>
                                            </div>

                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                
                                                <form action="{{ url('/dokter/reservasi/'.$res->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button type="submit" class="btn btn-primary fw-bold px-4">
                                                        <i class="bi bi-check-circle me-2"></i>Ya, Setujui Jadwal
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                        Tidak ada permintaan reservasi baru.
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