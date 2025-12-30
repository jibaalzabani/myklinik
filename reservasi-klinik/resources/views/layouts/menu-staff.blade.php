<a href="{{ url('/staff/dashboard') }}" class="nav-link {{ Request::is('staff/dashboard') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i> Dashboard Admin
</a>
<a href="{{ url('/staff/dokter') }}" class="nav-link {{ Request::is('staff/dokter') ? 'active' : '' }}">
    <i class="bi bi-person-badge"></i> Kelola Dokter
</a>
<a href="{{ url('/staff/pasien') }}" class="nav-link {{ Request::is('staff/pasien') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Data Pasien
</a>