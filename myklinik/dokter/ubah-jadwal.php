<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Jadwal Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .day-box {
            border: 1px solid #d8d8d8;
            border-radius: 10px;
            padding: 15px;
        }
        .day-title {
            font-weight: 700;
            color: #1e2c4c;
        }
        .form-control {
            border-radius: 10px;
            padding: 10px;
        }
        .btn-save {
            background: #0b4dff;
            color: white;
            font-weight: bold;
            border-radius: 12px;
            padding: 12px;
        }
    </style>
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Sistem Reservasi Klinik</a>
            <div class="d-flex">
                <a href="#" class="btn btn-outline-light btn-sm">Kembali</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">

        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="fw-bold">Ubah Jadwal Praktik Dokter</h2>
            <p class="text-muted">Atur jam praktik Anda sesuai kebutuhan</p>
        </div>

        <form>

            <div class="row g-4">

                <!-- Senin -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Senin</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" value="08:00">
                            <input type="time" class="form-control" value="12:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="seninLibur">
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Selasa -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Selasa</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" value="13:00">
                            <input type="time" class="form-control" value="17:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="selasaLibur">
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Rabu -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Rabu</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" disabled value="08:00">
                            <input type="time" class="form-control" disabled value="12:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Kamis -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Kamis</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" value="08:00">
                            <input type="time" class="form-control" value="12:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Jumat -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Jumat</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" value="13:00">
                            <input type="time" class="form-control" value="17:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Sabtu -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Sabtu</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" value="09:00">
                            <input type="time" class="form-control" value="12:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

                <!-- Minggu -->
                <div class="col-lg-6">
                    <div class="day-box">
                        <div class="day-title mb-2">Minggu</div>
                        <div class="d-flex gap-2">
                            <input type="time" class="form-control" disabled value="08:00">
                            <input type="time" class="form-control" disabled value="12:00">
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Libur</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Button -->
            <div class="text-center mt-5">
                <button class="btn btn-save px-5">Simpan Jadwal</button>
            </div>

        </form>

    </div>

</body>
</html>
