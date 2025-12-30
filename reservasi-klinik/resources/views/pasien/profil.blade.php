@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Akun')

@section('content')
<style>
    .profile-img-circle {
        width: 100px; height: 100px; background: #eef2ff; color: #0d6efd;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 35px; font-weight: bold; margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.1);
    }
</style>

<div class="row">
    
    @if(session('success'))
        <div class="col-12 mb-4">
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i> {{ session('success') }}
            </div>
        </div>
    @endif
    
    <div class="col-md-4 mb-4">
        <div class="card card-custom p-4 text-center h-100">
            <div class="card-body">
                <div class="profile-img-circle">
                    {{ substr(Auth::user()->patient->name ?? Auth::user()->name, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->patient->name ?? Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Pasien Aktif</span>
                </div>

                <div class="bg-light p-3 rounded-3 text-start">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Bergabung Sejak</small>
                    <span class="fw-bold text-dark"><i class="bi bi-calendar-check me-2"></i> {{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card card-custom p-4 mb-4">
            <div class="card-header bg-white border-0 px-0 pt-0 pb-3">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-gear me-2"></i>Edit Data Diri</h5>
            </div>
            
            <form action="{{ url('/pasien/profil') }}" method="POST">
                @csrf
                @method('POST') 

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-light border-0" value="{{ Auth::user()->patient->name ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">NIK (KTP)</label>
                        <input type="text" name="nik" class="form-control" value="{{ Auth::user()->patient->nik ?? '' }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control bg-light border-0" value="{{ Auth::user()->patient->phone ?? '' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">Tanggal Lahir</label>
                        <input type="date" name="birth_date" class="form-control bg-light border-0" value="{{ Auth::user()->patient->birth_date ?? '' }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted">Alamat Lengkap</label>
                        <textarea name="address" class="form-control bg-light border-0" rows="3">{{ Auth::user()->patient->address ?? '' }}</textarea>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-custom p-4">
            <div class="card-header bg-white border-0 px-0 pt-0 pb-3">
                <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h5>
            </div>
            <form action="{{ url('/pasien/ubah-password') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Password Baru</label>
                        <input type="password" name="password" class="form-control bg-light border-0">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted">Ulangi Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-light border-0">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-danger w-100 fw-bold">Ganti</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection