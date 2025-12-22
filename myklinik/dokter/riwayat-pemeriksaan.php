<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemeriksaan Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Sistem Reservasi Klinik</a>
            <div class="d-flex">
                <a href="dashboard-dokter.php" class="btn btn-outline-light btn-sm">Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Title -->
        <div class="mb-4 text-center">
            <h2 class="fw-bold">Riwayat Pemeriksaan</h2>
            <p class="text-muted">Data pemeriksaan pasien yang telah Anda tangani</p>
        </div>

        <!-- Card Container -->
        <div class="card shadow">
            <div class="card-body">

                <!-- Search & Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Cari nama pasien…">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select">
                            <option selected>Filter berdasarkan tanggal</option>
                            <option>7 Hari Terakhir</option>
                            <option>30 Hari Terakhir</option>
                            <option>Tahun Ini</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nama Pasien</th>
                                <th>Tanggal Pemeriksaan</th>
                                <th>Keluhan</th>
                                <th>Diagnosis</th>
                                <th>Tindakan</th>
                                <th>Resep Obat</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Budi Santoso</td>
                                <td>2025-01-10</td>
                                <td>Demam, Batuk</td>
                                <td>ISPA</td>
                                <td>Pemeriksaan Fisik</td>
                                <td>Paracetamol, Ambroxol</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Siti Rahma</td>
                                <td>2025-01-07</td>
                                <td>Pusing</td>
                                <td>Vertigo</td>
                                <td>Pemberian Vitamin</td>
                                <td>Vitamin B6</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Agus Hermawan</td>
                                <td>2025-01-05</td>
                                <td>Nyeri Perut</td>
                                <td>Gastritis</td>
                                <td>Konsultasi</td>
                                <td>Antasida</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
