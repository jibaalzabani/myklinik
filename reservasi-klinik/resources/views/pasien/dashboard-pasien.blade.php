@extends('layouts.app')

@section('title', 'Dashboard Pasien')
@section('page-title', 'Beranda Pasien')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="alert alert-primary border-0 text-white" style="background: linear-gradient(45deg, #0d6efd, #0043a8);">
            <h4 class="fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
            <p class="mb-0">Jangan lupa cek kesehatanmu secara rutin. Kami siap melayani.</p>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card card-custom p-4 text-center">
            <h1 class="text-primary fw-bold display-4">{{ $menunggu }}</h1>
            <p class="text-muted">Menunggu Konfirmasi</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4 text-center">
            <h1 class="text-success fw-bold display-4">{{ $selesai }}</h1>
            <p class="text-muted">Kunjungan Selesai</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom p-4 text-center">
            <a href="{{ url('/pasien/reservasi') }}" class="btn btn-primary btn-lg w-100 h-100 d-flex align-items-center justify-content-center">
                <i class="bi bi-plus-circle me-2"></i> Daftar Baru
            </a>
        </div>
    </div>
</div>
@endsection