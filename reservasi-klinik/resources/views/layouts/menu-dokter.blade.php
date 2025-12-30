<small class="text-muted ms-3 mb-2 text-uppercase fw-bold" style="font-size: 0.7rem;">Menu Dokter</small>

<a href="{{ url('/dokter/dashboard') }}" class="nav-link {{ Request::is('dokter/dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-fill"></i> Dashboard
</a>

<a href="{{ url('/dokter/jadwal') }}" class="nav-link {{ Request::is('dokter/jadwal') ? 'active' : '' }}">
    <i class="bi bi-calendar-week"></i> Jadwal Saya
</a>

<a href="{{ url('/dokter/pasien') }}" class="nav-link {{ Request::is('dokter/pasien') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i> Daftar Pasien
</a>

<a href="{{ url('/dokter/riwayat') }}" class="nav-link {{ Request::is('dokter/riwayat') ? 'active' : '' }}">
    <i class="bi bi-file-medical"></i> Riwayat Periksa
</a>