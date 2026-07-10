<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Simulasi Keuntungan Bisnis</title>
    <!-- Manggil CSS AdminLTE & Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Source Sans Pro', sans-serif; }
        .navbar-brand-custom { background-color: #343a40; color: #fff; padding: 15px; font-weight: bold; }
        .card-header-blue { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>

    <!-- Header Atas biar mirip Dashboard -->
    <div class="navbar-brand-custom d-flex justify-content-between align-items-center shadow-sm mb-4">
        <div>
            <i class="bi bi-calculator-fill me-2 text-success"></i> ERP RPL UAD - Modul Kalkulator
        </div>
        <a href="/dashboard" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <div class="container pb-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2 class="fw-bold text-dark"><i class="bi bi-graph-up-arrow text-primary"></i> Kalkulator Simulasi Keuntungan Bisnis</h2>
                <p class="text-muted">Kelola dan proyeksikan keuntungan produk secara otomatis dan akurat.</p>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Input (Kiri) -->
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header card-header-blue fw-bold">
                        <i class="bi bi-pencil-square me-1"></i> Input Simulasi Produk
                    </div>
                    <div class="card-body bg-white p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Estimasi Harga Modal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" id="harga_modal" class="form-control" placeholder="Contoh: 10000" oninput="hitungUntung()">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Estimasi Harga Jual (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" id="harga_jual" class="form-control" placeholder="Contoh: 15000" oninput="hitungUntung()">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Target Penjualan (Pcs / Bulan)</label>
                            <div class="input-group">
                                <input type="number" id="target_jual" class="form-control" placeholder="Contoh: 100" oninput="hitungUntung()">
                                <span class="input-group-text bg-light">Pcs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Hasil (Kanan) -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="bi bi-bar-chart-line me-1"></i> Hasil Analisis & Proyeksi
                    </div>
                    <div class="card-body bg-white p-4" id="box_hasil">
                        <div class="text-center py-4 text-muted" id="placeholder_text">
                            <i class="bi bi-info-circle display-4 d-block mb-2 text-warning"></i>
                            Silakan masukkan data simulasi di sebelah kiri untuk melihat hasil analisis keuntungan.
                        </div>
                        
                        <!-- Bagian Hasil -->
                        <div id="konten_hasil" class="d-none">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Untung / Pcs</small>
                                        <span class="fs-4 fw-bold text-success" id="res_untung_pcs">Rp 0</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded border">
                                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Total Untung / Bulan</small>
                                        <span class="fs-4 fw-bold text-primary" id="res_untung_bulan">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-3 rounded mb-3 text-center fw-bold fs-5" id="status_bisnis">
                                -
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-end text-muted small">
                        Fitur Analisis Keuntungan Otomatis
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logika Javascript Kalkulator Otomatis -->
    <script>
        function hitungUntung() {
            const modal = parseFloat(document.getElementById('harga_modal').value) || 0;
            const jual = parseFloat(document.getElementById('harga_jual').value) || 0;
            const target = parseFloat(document.getElementById('target_jual').value) || 0;

            const placeholder = document.getElementById('placeholder_text');
            const konten = document.getElementById('konten_hasil');
            const statusBisnis = document.getElementById('status_bisnis');

            if (modal === 0 && jual === 0 && target === 0) {
                placeholder.classList.remove('d-none');
                konten.classList.add('d-none');
                return;
            }

            placeholder.classList.add('d-none');
            konten.classList.remove('d-none');

            const untungPcs = jual - modal;
            const untungBulan = untungPcs * target;

            document.getElementById('res_untung_pcs').innerText = "Rp " + untungPcs.toLocaleString('id-ID');
            document.getElementById('res_untung_bulan').innerText = "Rp " + untungBulan.toLocaleString('id-ID');

            if (untungPcs > 0) {
                statusBisnis.className = "p-3 rounded mb-3 text-center fw-bold fs-5 bg-success-subtle text-success border border-success";
                statusBisnis.innerHTML = '<i class="bi bi-check-circle-fill"></i> BISNIS IDEAL (Untung)';
            } else if (untungPcs < 0) {
                statusBisnis.className = "p-3 rounded mb-3 text-center fw-bold fs-5 bg-danger-subtle text-danger border border-danger";
                statusBisnis.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> PERINGATAN (Rugi)';
            } else {
                statusBisnis.className = "p-3 rounded mb-3 text-center fw-bold fs-5 bg-warning-subtle text-warning border border-warning";
                statusBisnis.innerHTML = '<i class="bi bi-info-circle-fill"></i> BREAK EVEN POINT (Impas)';
            }
        }
    </script>
</body>
</html>