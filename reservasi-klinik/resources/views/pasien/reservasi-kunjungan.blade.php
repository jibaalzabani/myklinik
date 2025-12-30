@extends('layouts.app')

@section('title', 'Buat Janji Temu')
@section('page-title', 'Pendaftaran Pasien')

@section('content')
<style>
    /* Style Tambahan Khusus Halaman Ini */
    .form-header { background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%); color: white; padding: 20px; border-radius: 15px 15px 0 0; }
    .step-icon { width: 35px; height: 35px; background: #eef2ff; color: #0d6efd; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
    .info-card { background: #fff; border: 1px solid #eef2ff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8 mb-4">
        
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card card-custom h-100">
            <div class="form-header">
                <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Formulir Pendaftaran</h5>
                <p class="mb-0 opacity-75 small">Lengkapi data di bawah ini untuk bertemu dokter.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('/pasien/reservasi') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Rencana Tanggal Berobat</label>
                            <input type="date" name="date" class="form-control form-control-lg bg-light border-0" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Pilih Dokter & Jadwal Praktek</label>
                            <select name="schedule_id" class="form-select form-select-lg bg-light border-0" required>
                                <option value="">Cari Dokter / Spesialis</option>

                                @forelse($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">
                                        {{ $schedule->doctor->name }} | {{ $schedule->doctor->specialization }} 
                                        ({{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }} : 
                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }})
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada jadwal dokter tersedia.</option>
                                @endforelse
                            </select>
                            <div class="form-text text-primary">
                                <small><i class="bi bi-info-circle"></i> Jadwal praktek (Jam Mulai - Jam Selesai) sudah tertera.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Keluhan / Gejala</label>
                            <textarea name="symptoms" class="form-control bg-light border-0" rows="4" placeholder="Contoh: Demam tinggi sejak 3 hari lalu, disertai batuk kering..." required></textarea>
                        </div>
                    </div>

                    <hr class="my-4 dashed">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm rounded-3">
                            <i class="bi bi-send-fill me-2"></i> Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card info-card p-4 mb-4">
            <h6 class="fw-bold mb-4 text-primary text-uppercase" style="letter-spacing: 1px;">Panduan Singkat</h6>
            
            <div class="d-flex align-items-center mb-4">
                <div class="step-icon">1</div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Pilih Jadwal</h6>
                    <small class="text-muted">Pilih dokter & jam praktek.</small>
                </div>
            </div>

            <div class="d-flex align-items-center mb-4">
                <div class="step-icon">2</div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Isi Keluhan</h6>
                    <small class="text-muted">Jelaskan kondisi Anda.</small>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="step-icon">3</div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Datang ke Klinik</h6>
                    <small class="text-muted">Datang 15 menit lebih awal.</small>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 text-center rounded-4 text-white" style="background: linear-gradient(45deg, #ff357a, #fff172);">
            <div class="mb-2 fs-1">🚑</div>
            <h6 class="fw-bold text-dark">Gawat Darurat?</h6>
            <p class="text-dark small opacity-75">Jangan daftar online! Segera ke UGD.</p>
            <a href="#" class="btn btn-light w-100 fw-bold rounded-pill text-danger shadow-sm">Call 119</a>
        </div>

    </div>
</div>
@endsection