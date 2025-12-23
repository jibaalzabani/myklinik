<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien - Klinik Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f6f9;">

<nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">🏥 Panel Pasien</span>
        <button onclick="logout()" class="btn btn-danger btn-sm">Keluar</button>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4 text-center">
            <div class="card p-4 border-0 shadow-sm">
                <h3>Halo, <span id="userName">Pasien</span> 👋</h3>
                <p class="text-muted">Jangan lupa jaga kesehatan ya!</p>
                <a href="reservasi-kunjungan.php" class="btn btn-primary btn-lg fw-bold shadow">
                    ➕ Buat Janji Temu Baru
                </a>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    📜 Riwayat Pendaftaran & Diagnosa
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa / Catatan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="historyTable">
                                <tr><td colspan="5" class="text-center">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const API_URL = 'http://localhost:5000';
    const userId = localStorage.getItem('id_user');
    const userName = localStorage.getItem('nama');

    if(!userId) window.location.href = 'index.php';
    if(userName) document.getElementById('userName').innerText = userName;

    async function loadHistory() {
        try {
            const res = await fetch(`${API_URL}/appointments/${userId}`);
            const data = await res.json();
            const tbody = document.getElementById('historyTable');

            if(data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-3">Belum ada riwayat berobat.</td></tr>';
                return;
            }

            let html = '';
            data.forEach(d => {
                const tgl = new Date(d.date).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                const jam = d.time ? d.time.substring(0,5) : '-';
                
                // Tampilkan Diagnosa jika ada
                let diagnosaText = d.diagnosis ? d.diagnosis : '<span class="text-muted fst-italic">Menunggu pemeriksaan...</span>';
                if(d.status === 'batal') diagnosaText = '<span class="text-danger">Dibatalkan</span>';

                let badge = 'bg-secondary';
                if(d.status === 'selesai') badge = 'bg-success';
                if(d.status === 'pending') badge = 'bg-warning text-dark';
                if(d.status === 'batal') badge = 'bg-danger';

                // Tombol Cetak Struk (Hanya muncul jika status pending/selesai)
                let btnCetak = '';
                if(d.status !== 'batal') {
                    // Kita kirim data ke fungsi via parameter
                    // replace tanda petik agar tidak error di JS
                    const docName = d.doctor_name.replace(/'/g, ""); 
                    btnCetak = `
                        <button onclick="cetakStruk('${tgl}', '${jam}', '${docName}', '${d.status.toUpperCase()}')" 
                        class="btn btn-sm btn-outline-dark">
                        🖨️ Cetak Bukti
                        </button>
                    `;
                }

                html += `
                    <tr>
                        <td>
                            <div class="fw-bold">${tgl}</div>
                            <small class="text-muted">Jam: ${jam}</small>
                        </td>
                        <td>${d.doctor_name}</td>
                        <td>${diagnosaText}</td>
                        <td><span class="badge ${badge} text-capitalize">${d.status}</span></td>
                        <td>${btnCetak}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;

        } catch (error) {
            console.error(error);
        }
    }

    // --- FUNGSI CETAK STRUK ---
    function cetakStruk(tgl, jam, dokter, status) {
        // Membuat jendela popup baru
        const printWindow = window.open('', '', 'height=600,width=400');
        
        const strukHTML = `
            <html>
            <head>
                <title>Struk Pendaftaran - Klinik Sehat</title>
                <style>
                    body { font-family: 'Courier New', monospace; padding: 20px; text-align: center; }
                    .header { font-weight: bold; font-size: 18px; margin-bottom: 5px; }
                    .sub { font-size: 12px; margin-bottom: 20px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
                    .content { text-align: left; margin: 20px 0; }
                    .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
                    .status { border: 2px solid #000; padding: 5px; font-weight: bold; margin-top: 20px; display: inline-block; }
                    .footer { margin-top: 30px; font-size: 10px; font-style: italic; }
                </style>
            </head>
            <body>
                <div class="header">KLINIK SEHAT</div>
                <div class="sub">Jl. Sehat Selalu No. 123<br>Telp: 021-555-9999</div>
                
                <div class="content">
                    <div class="row"><span>Nama Pasien:</span> <span>${userName}</span></div>
                    <div class="row"><span>Tanggal:</span> <span>${tgl}</span></div>
                    <div class="row"><span>Jam:</span> <span>${jam}</span></div>
                    <div class="row"><span>Dokter:</span> <span>${dokter}</span></div>
                </div>

                <div class="status">${status}</div>

                <div class="footer">
                    *Harap bawa struk ini saat datang.<br>
                    Terima kasih telah mempercayai kami.
                </div>
                <script>
                    window.print();
                    window.onafterprint = function() { window.close(); }
                <\/script>
            </body>
            </html>
        `;

        printWindow.document.write(strukHTML);
        printWindow.document.close();
    }

    function logout() {
        if(confirm("Yakin ingin keluar?")) {
            localStorage.clear();
            window.location.href = '../index.php';
        }
    }

    loadHistory();
</script>

</body>
</html>