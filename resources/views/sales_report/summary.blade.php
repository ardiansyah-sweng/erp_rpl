<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report Summary</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
    <style>
        :root {
            --sr-navy: #11324d;
            --sr-blue: #2f80ed;
            --sr-cyan: #56ccf2;
            --sr-cream: #f7f9fc;
            --sr-text: #1f2937;
            --sr-muted: #6b7280;
            --sr-border: rgba(17, 50, 77, 0.08);
            --sr-shadow: 0 18px 45px rgba(17, 50, 77, 0.12);
        }

        body {
            font-family: "Source Sans 3", sans-serif;
            color: var(--sr-text);
            background:
                radial-gradient(circle at top left, rgba(86, 204, 242, 0.25), transparent 28%),
                radial-gradient(circle at top right, rgba(47, 128, 237, 0.18), transparent 24%),
                linear-gradient(180deg, #edf4fb 0%, #f8fafc 48%, #eef3f8 100%);
            min-height: 100vh;
        }

        .page-shell {
            padding: 40px 0 56px;
        }

        .hero-panel {
            position: relative;
            overflow: hidden;
            border: 0;
            border-radius: 28px;
            background: linear-gradient(135deg, #11324d 0%, #1f5d8d 55%, #2f80ed 100%);
            color: #fff;
            box-shadow: var(--sr-shadow);
        }

        .hero-panel::before,
        .hero-panel::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-panel::before {
            width: 240px;
            height: 240px;
            top: -80px;
            right: -60px;
        }

        .hero-panel::after {
            width: 160px;
            height: 160px;
            bottom: -50px;
            left: -40px;
        }

        .hero-content,
        .hero-stat {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 0.9rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 700;
            line-height: 1.05;
            margin: 18px 0 14px;
        }

        .hero-copy {
            max-width: 640px;
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.84);
            margin-bottom: 0;
        }

        .action-group .btn {
            min-width: 168px;
            border-radius: 14px;
            padding: 0.85rem 1.2rem;
            font-weight: 600;
        }

        .btn-export {
            background: #fff;
            color: var(--sr-navy);
            border: 0;
        }

        .btn-export:hover {
            background: #f2f7ff;
            color: var(--sr-navy);
        }

        .btn-back {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.32);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .hero-stat {
            padding: 22px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
        }

        .hero-stat-label {
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 8px;
        }

        .hero-stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .metric-card,
        .insight-card,
        .note-card {
            border: 0;
            border-radius: 24px;
            box-shadow: var(--sr-shadow);
        }

        .metric-card {
            position: relative;
            overflow: hidden;
            min-height: 100%;
        }

        .metric-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.9;
        }

        .metric-card.total::before {
            background: linear-gradient(135deg, #eff8ff 0%, #d8ecff 100%);
        }

        .metric-card.transactions::before {
            background: linear-gradient(135deg, #eefcf5 0%, #d4f5e3 100%);
        }

        .metric-card.average::before {
            background: linear-gradient(135deg, #fff7ea 0%, #ffe6b8 100%);
        }

        .metric-card .card-body {
            position: relative;
            z-index: 1;
            padding: 28px;
        }

        .metric-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.45rem;
            margin-bottom: 22px;
        }

        .metric-card.total .metric-icon {
            background: rgba(47, 128, 237, 0.14);
            color: #1d66c1;
        }

        .metric-card.transactions .metric-icon {
            background: rgba(39, 174, 96, 0.14);
            color: #1f9b57;
        }

        .metric-card.average .metric-icon {
            background: rgba(242, 153, 74, 0.18);
            color: #d67a12;
        }

        .metric-label {
            color: var(--sr-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.82rem;
            margin-bottom: 10px;
        }

        .metric-value {
            font-size: clamp(1.85rem, 3vw, 2.5rem);
            font-weight: 700;
            line-height: 1.05;
            margin-bottom: 10px;
        }

        .metric-caption {
            color: #4b5563;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .section-copy {
            color: var(--sr-muted);
            margin-bottom: 0;
        }

        .insight-card .card-body,
        .note-card .card-body {
            padding: 28px;
        }

        .insight-strip {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            background: #eef6ff;
            margin-bottom: 18px;
        }

        .insight-strip:last-child {
            margin-bottom: 0;
        }

        .insight-strip i {
            font-size: 1.35rem;
            color: var(--sr-blue);
        }

        .insight-strip strong {
            display: block;
            margin-bottom: 2px;
        }

        .notes-list {
            display: grid;
            gap: 14px;
        }

        .note-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 16px 18px;
            border-radius: 18px;
            background: var(--sr-cream);
            border: 1px solid var(--sr-border);
        }

        .note-item i {
            color: var(--sr-blue);
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .note-item p {
            margin: 0;
            color: #475467;
        }

        code {
            color: #0f5fb5;
            background: rgba(47, 128, 237, 0.08);
            padding: 2px 7px;
            border-radius: 8px;
        }

        @media (max-width: 991.98px) {
            .hero-stat-grid {
                margin-top: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <div class="container">
            <div class="hero-panel p-4 p-lg-5 mb-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <div class="hero-content">
                            <span class="eyebrow">
                                <i class="bi bi-bar-chart-line-fill"></i>
                                Sales Analytics
                            </span>
                            <h1 class="hero-title">Sales Report Summary</h1>
                            <p class="hero-copy">
                                Ringkasan penjualan ini dihitung langsung dari data transaksi pada tabel
                                <strong>purchase_order</strong> untuk membantu melihat performa penjualan secara cepat.
                            </p>
                            <div class="action-group d-flex flex-column flex-sm-row gap-3 mt-4">
                                <a href="{{ route('sales.report.pdf') }}" class="btn btn-export">
                                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-back">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-3 hero-stat-grid">
                            <div class="col-12">
                                <div class="hero-stat">
                                    <p class="hero-stat-label">Total Penjualan</p>
                                    <p class="hero-stat-value">Rp{{ number_format($totalSales, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat">
                                    <p class="hero-stat-label">Transaksi</p>
                                    <p class="hero-stat-value">{{ number_format($transactionCount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="hero-stat">
                                    <p class="hero-stat-label">Rata-rata</p>
                                    <p class="hero-stat-value">Rp{{ number_format($averageTransactionValue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="card metric-card total h-100">
                        <div class="card-body">
                            <div class="metric-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <p class="metric-label">Total Sales</p>
                            <p class="metric-value">Rp{{ number_format($totalSales, 0, ',', '.') }}</p>
                            <p class="metric-caption">Akumulasi seluruh nilai penjualan yang tercatat di sistem.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card metric-card transactions h-100">
                        <div class="card-body">
                            <div class="metric-icon">
                                <i class="bi bi-receipt-cutoff"></i>
                            </div>
                            <p class="metric-label">Number of Transactions</p>
                            <p class="metric-value">{{ number_format($transactionCount, 0, ',', '.') }}</p>
                            <p class="metric-caption">Jumlah seluruh transaksi purchase order yang sudah tersimpan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card metric-card average h-100">
                        <div class="card-body">
                            <div class="metric-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <p class="metric-label">Average Transaction Value</p>
                            <p class="metric-value">Rp{{ number_format($averageTransactionValue, 0, ',', '.') }}</p>
                            <p class="metric-caption">Nilai rata-rata transaksi untuk membaca kualitas penjualan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card insight-card h-100">
                        <div class="card-body">
                            <h2 class="section-title">Quick Insight</h2>
                            <p class="section-copy mb-4">Tiga indikator utama yang bisa langsung dipakai saat review performa.</p>

                            <div class="insight-strip">
                                <i class="bi bi-stars"></i>
                                <div>
                                    <strong>Snapshot cepat</strong>
                                    <span class="text-muted">Halaman ini cocok untuk melihat kondisi penjualan tanpa buka detail transaksi satu per satu.</span>
                                </div>
                            </div>

                            <div class="insight-strip">
                                <i class="bi bi-filetype-pdf"></i>
                                <div>
                                    <strong>Siap dibagikan</strong>
                                    <span class="text-muted">Tombol export memudahkan laporan dibawa ke dosen, tim, atau kebutuhan dokumentasi.</span>
                                </div>
                            </div>

                            <div class="insight-strip">
                                <i class="bi bi-speedometer2"></i>
                                <div>
                                    <strong>Fokus ke metrik inti</strong>
                                    <span class="text-muted">Total sales, transaksi, dan rata-rata nilai transaksi jadi highlight utama di satu layar.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card note-card h-100">
                        <div class="card-body">
                            <h2 class="section-title">Calculation Notes</h2>
                            <p class="section-copy mb-4">Penjelasan singkat sumber perhitungan supaya hasil report mudah dipahami.</p>

                            <div class="notes-list">
                                <div class="note-item">
                                    <i class="bi bi-calculator"></i>
                                    <p><strong>Total Sales</strong> dihitung dari penjumlahan semua nilai pada kolom <code>purchase_order.total</code>.</p>
                                </div>
                                <div class="note-item">
                                    <i class="bi bi-collection"></i>
                                    <p><strong>Number of Transactions</strong> dihitung dari jumlah seluruh record pada tabel <code>purchase_order</code>.</p>
                                </div>
                                <div class="note-item">
                                    <i class="bi bi-bar-chart"></i>
                                    <p><strong>Average Transaction Value</strong> dihitung dari rata-rata nilai pada kolom <code>purchase_order.total</code>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
