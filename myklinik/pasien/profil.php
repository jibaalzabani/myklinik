<?php
session_start();

// --- SIMULASI DATA USER (Nanti diganti dengan ambil dari Database) ---
// Contoh: $data_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pasien WHERE id = ..."));
$data_user = [
    'nama' => 'Budi Santoso',
    'email' => 'budi.santoso@email.com',
    'no_hp' => '081234567890',
    'tgl_lahir' => '1995-08-17',
    'gender' => 'Laki-laki',
    'alamat' => 'Jl. Merdeka No. 45, Bandung',
    'member_since' => 'Januari 2024'
];

// Logika sederhana untuk simulasi simpan data
$sukses = false;
if (isset($_POST['simpan'])) {
    // Di sini nanti proses UPDATE ke database
    $data_user['nama'] = $_POST['nama']; // Update tampilan dengan input baru
    $sukses = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - My Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7ff; }
        
        /* Gaya Kartu yang konsisten */
        .card-custom {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: none;
            margin-bottom: 20px;
        }
        .profile-img-box {
            width: 120px;
            height: 120px;
            background: #e3ebff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 40px;
            color: #1e5dfb;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .btn-primary-custom {
            background: #1e5dfb;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-primary-custom:hover {
            background: #174ac9;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard-pasien.php">My Klinik</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard-pasien.php">Kembali ke Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-custom p-4 text-center">
                <div class="profile-img-box">
                    <?= substr($data_user['nama'], 0, 1) ?>
                </div>
                <h4 class="fw-bold mb-1"><?= $data_user['nama'] ?></h4>
                <p class="text-muted">Pasien Umum</p>
                <hr>
                <div class="d-flex justify-content-between text-start px-3">
                    <small class="text-muted">Terdaftar Sejak</small>
                    <small class="fw-bold"><?= $data_user['member_since'] ?></small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            
            <?php if($sukses): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Data berhasil diperbarui!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card card-custom p-4">
                <h4 class="mb-4 fw-bold">Edit Profil</h4>
                
                <form method="POST" action="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $data_user['nama'] ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $data_user['email'] ?>" readonly style="background-color: #f0f0f0;">
                            <small class="text-muted" style="font-size: 11px;">Email tidak dapat diubah</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor HP / WhatsApp</label>
                            <input type="number" name="no_hp" class="form-control" value="<?= $data_user['no_hp'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="<?= $data_user['tgl_lahir'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="Laki-laki" <?= $data_user['gender'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= $data_user['gender'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3"><?= $data_user['alamat'] ?></textarea>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="dashboard-pasien.php" class="btn btn-light me-2">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary btn-primary-custom">Simpan Perubahan</button>
                    </div>
                </form>

            </div>

            <div class="card card-custom p-4 mt-4">
                <h5 class="mb-3 fw-bold text-danger">Keamanan</h5>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" placeholder="Masukkan password baru">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" placeholder="Ulangi password baru">
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="button" class="btn btn-outline-danger">Ganti Password</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>