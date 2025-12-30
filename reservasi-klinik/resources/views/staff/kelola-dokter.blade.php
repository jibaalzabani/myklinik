@extends('layouts.app')

@section('title', 'Kelola Dokter')
@section('page-title', 'Manajemen Data Dokter')

@section('content')
<div class="row">
    <div class="col-12">
        
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-custom">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge me-2"></i>Daftar Dokter Klinik</h6>
                <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Dokter
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Dokter</th>
                                <th>Spesialisasi</th>
                                <th>Kontak</th>
                                <th>Email Login</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctors as $doc)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $doc->name }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $doc->specialization }}</span></td>
                                    <td>{{ $doc->phone }}</td>
                                    <td>{{ $doc->user->email ?? '-' }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ url('/staff/dokter/'.$doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokter ini? Semua jadwal dan riwayatnya akan ikut terhapus!');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Dokter Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/staff/dokter') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: dr. Budi Santoso, Sp.A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Spesialisasi</label>
                        <select name="specialization" class="form-select" required>
                            <option value="Umum">Dokter Umum</option>
                            <option value="Gigi">Dokter Gigi</option>
                            <option value="Anak">Spesialis Anak</option>
                            <option value="Penyakit Dalam">Penyakit Dalam</option>
                            <option value="Bedah">Bedah</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor HP</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email (Untuk Login)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection