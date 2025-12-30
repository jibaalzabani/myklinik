@extends('layouts.app')

@section('title', 'Jadwal Saya')
@section('page-title', 'Kelola Jadwal Praktik')

@section('content')
<div class="row">
    
    @if(session('success'))
        <div class="col-12 mb-3">
            <div class="alert alert-success border-0 shadow-sm"><i class="bi bi-check-circle me-2"></i> {{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="col-12 mb-3">
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="col-md-4 mb-4">
        <div class="card card-custom sticky-top" style="top: 90px; z-index: 1;">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Tambah Jadwal Baru</h6>
            </div>
            <div class="card-body">
                <form action="{{ url('/dokter/jadwal') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Tanggal Praktek</label>
                        <input type="date" name="date" class="form-control" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-save me-2"></i> Simpan Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-list-ul me-2"></i>Daftar Jadwal Saya</h6>
                <span class="badge bg-light text-secondary border">{{ count($schedules) }} Jadwal</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Jam Praktek</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                        @if($schedule->date == date('Y-m-d'))
                                            <span class="badge bg-info ms-2 text-white" style="font-size: 0.6rem;">HARI INI</span>
                                        @endif
                                    </td>
                                    <td>{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                                    <td>
                                        @if($schedule->is_available)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tutup</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-warning me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $schedule->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ url('/dokter/jadwal/'.$schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title fw-bold">Edit Jadwal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ url('/dokter/jadwal/'.$schedule->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Tanggal</label>
                                                        <input type="date" name="date" class="form-control" value="{{ $schedule->date }}" required>
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-bold">Mulai</label>
                                                            <input type="time" name="start_time" class="form-control" value="{{ $schedule->start_time }}" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-bold">Selesai</label>
                                                            <input type="time" name="end_time" class="form-control" value="{{ $schedule->end_time }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-check form-switch bg-light p-3 rounded">
                                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_available" id="status{{ $schedule->id }}" {{ $schedule->is_available ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold" for="status{{ $schedule->id }}">Jadwal Tersedia (Aktif)</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                                        Anda belum membuat jadwal praktek.
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