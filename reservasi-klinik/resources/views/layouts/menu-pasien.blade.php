<a href="{{ url('/pasien/dashboard') }}" class="nav-link {{ Request::is('pasien/dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ url('/pasien/reservasi') }}" class="nav-link {{ Request::is('pasien/reservasi') ? 'active' : '' }}">
    <i class="bi bi-calendar-plus"></i> Buat Janji
</a>
<a href="{{ url('/pasien/riwayat') }}" class="nav-link {{ Request::is('pasien/riwayat') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i> Riwayat
</a>
<a href="{{ url('/pasien/profil') }}" class="nav-link {{ Request::is('pasien/profil') ? 'active' : '' }}">
    <i class="bi bi-person-circle"></i> Profil Saya
</a>