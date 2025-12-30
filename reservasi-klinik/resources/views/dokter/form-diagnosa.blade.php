@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')
@section('page-title', 'Form Diagnosa Dokter')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card card-custom h-100 border-0 shadow-sm">
            <div class="card-header bg-light py-3">
                <h6 class="fw-bold mb-0 text-secondary">Data Pasien</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; font-size: 24px;">
                        {{ substr($reservation->patient->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold">{{ $reservation->patient->name }}</h5>
                    <p class="text-muted small">NIK: {{ $reservation->patient->nik }}</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="small text-muted fw-bold">Keluhan Utama</label>
                    <div class="alert alert-warning border-0 text-dark mt-1">
                        "{{ $reservation->symptoms }}"
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small text-muted fw-bold">Kontak</label>
                    <p class="mb-0 fw-bold">{{ $reservation->patient->phone }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-custom border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clipboard-pulse me-2"></i>Hasil Pemeriksaan</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('/dokter/periksa/'.$reservation->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Diagnosa Medis <span class="text-danger">*</span></label>
                        <textarea name="diagnosis" class="form-control" rows="3" placeholder="Contoh: Infeksi Saluran Pernapasan Akut (ISPA)" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Resep Obat / Tindakan <span class="text-danger">*</span></label>
                        <textarea name="resep_obat" class="form-control" rows="3" placeholder="Contoh: Paracetamol 3x1, Vitamin C 1x1, Istirahat cukup." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Catatan Tambahan (Opsional)</label>
                        <textarea name="admin_note" class="form-control" rows="2" placeholder="Catatan untuk pasien atau kontrol ulang..."></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('/dokter/dashboard') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-save me-2"></i> Simpan & Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection