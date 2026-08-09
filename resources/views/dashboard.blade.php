@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title')
    <h3 class="mb-0">Dashboard</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
    {{-- ===== 4 CARD UTAMA ===== --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalPO }}</h3>
                    <p>Total Purchase Orders</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
                </svg>
                <a href="{{ route('purchase.orders') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat PO <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $grnThisMonth }}</h3>
                    <p>GRN Bulan Ini</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
                </svg>
                <a href="{{ route('goods-returns.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat GRN <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $productionStats['in_production'] ?? 0 }}</h3>
                    <p>Produksi Berjalan</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M11.25 4.533A9.718 9.718 0 006.703 12c0 1.062.162 2.082.467 3.043l-3.904 3.905A.75.75 0 013.224 18.7l3.905-3.905A9.718 9.718 0 0011.25 19.467V22.5a.75.75 0 001.5 0v-3.033a9.718 9.718 0 005.121-3.968l3.904 3.905a.75.75 0 101.06-1.061l-3.904-3.905A9.718 9.718 0 0012.75 4.533V1.5a.75.75 0 00-1.5 0v3.033z"></path>
                </svg>
                <a href="{{ route('assortment_production.list') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Produksi <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $returnsThisMonth }}</h3>
                    <p>Goods Return Bulan Ini</p>
                </div>
                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
                </svg>
                <a href="{{ route('goods-returns.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Return <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ===== 8 MINI CARD (MASTER DATA SUMMARY) ===== --}}
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle rounded p-2 me-3">
                            <i class="bi bi-building text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Supplier</div>
                            <div class="fs-5 fw-semibold">{{ $totalSupplier }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle rounded p-2 me-3">
                            <i class="bi bi-shop text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Cabang Aktif / Total</div>
                            <div class="fs-5 fw-semibold">{{ $branchStats['active_branches'] ?? 0 }} / {{ $totalBranch }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle rounded p-2 me-3">
                            <i class="bi bi-box-seam text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Warehouse Aktif / Total</div>
                            <div class="fs-5 fw-semibold">{{ $activeWarehouse }} / {{ $totalWarehouse }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning-subtle rounded p-2 me-3">
                            <i class="bi bi-tag text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Merk Aktif / Total</div>
                            <div class="fs-5 fw-semibold">{{ $merkStats['active_merk'] ?? 0 }} / {{ $merkStats['total_merk'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary-subtle rounded p-2 me-3">
                            <i class="bi bi-box text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Item / Bahan</div>
                            <div class="fs-5 fw-semibold">{{ $totalItem }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger-subtle rounded p-2 me-3">
                            <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Item Stok Rendah</div>
                            <div class="fs-5 fw-semibold">{{ $lowStockCount }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success-subtle rounded p-2 me-3">
                            <i class="bi bi-cash-coin text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Nilai Total Stok</div>
                            <div class="fs-5 fw-semibold">Rp {{ number_format($totalStockValue ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info-subtle rounded p-2 me-3">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Pengguna</div>
                            <div class="fs-5 fw-semibold">{{ $totalUsers }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 3 CHARTS ===== --}}
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Status Purchase Order</h3>
                </div>
                <div class="card-body">
                    <div id="po-status-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Tren PO 6 Bulan Terakhir</h3>
                </div>
                <div class="card-body">
                    <div id="po-trend-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Supplier</h3>
                </div>
                <div class="card-body">
                    <div id="top-supplier-chart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== 3 TABEL RINGKASAN ===== --}}
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">PO Mendekati Deadline / Overdue</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outstandingPOs as $po)
                                <tr>
                                    <td>{{ $po->po_number }}</td>
                                    <td>{{ $po->supplier?->company_name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</td>
                                    <td><span class="badge text-bg-warning">{{ $po->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Tidak ada PO overdue</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Item Stok Rendah (&lt; 10)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->{\App\Constants\ItemColumns::SKU} }}</td>
                                    <td>{{ $item->{\App\Constants\ItemColumns::NAME} }}</td>
                                    <td><span class="badge text-bg-danger">{{ $item->{\App\Constants\ItemColumns::STOCK_UNIT} }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Semua stok aman</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Mutasi Stok Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>SKU</th>
                                <th>Perubahan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStockLogs as $log)
                                <tr>
                                    <td>{{ $log->log_id }}</td>
                                    <td>{{ $log->sku }}</td>
                                    <td>{{ $log->old_stock }} → {{ $log->new_stock }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada mutasi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <script>
        @php
            $statusLabels = [
                'Draft' => 'Draft',
                'Submitted' => 'Submitted',
                'In Review' => 'In Review',
                'Revised' => 'Revised',
                'Approved' => 'Approved',
                'Rejected' => 'Rejected',
                'Cancelled' => 'Cancelled',
                'Closed' => 'Closed',
                'Partially Delivered' => 'Part. Delivered',
                'Fully Delivered' => 'Fully Delivered',
            ];
            $statusData = [];
            foreach ($statusLabels as $key => $label) {
                $statusData[$label] = $poStatusBreakdown[$key]['total'] ?? 0;
            }
            $trendCategories = [];
            $trendSeries = [];
            foreach ($monthlyTrend as $item) {
                $trendCategories[] = $item->month;
                $trendSeries[] = (int) $item->total;
            }
            $supplierNames = [];
            $supplierCounts = [];
            foreach ($topSuppliers as $supplier) {
                $supplierNames[] = $supplier->company_name ?? 'Unknown';
                $supplierCounts[] = (int) $supplier->po_count;
            }
        @endphp

        const statusData = @json(array_values($statusData));
        const statusLabels = @json(array_keys($statusData));
        new ApexCharts(document.querySelector('#po-status-chart'), {
            series: statusData,
            chart: { height: 280, type: 'donut' },
            labels: statusLabels,
            legend: { position: 'bottom' },
            responsive: [{ breakpoint: 480, options: { chart: { height: 240 }, legend: { position: 'bottom' } } }]
        }).render();

        new ApexCharts(document.querySelector('#po-trend-chart'), {
            series: [{ name: 'Jumlah PO', data: @json($trendSeries) }],
            chart: { height: 280, type: 'area', toolbar: { show: false } },
            colors: ['#0d6efd'],
            dataLabels: { enabled: false },
            xaxis: { categories: @json($trendCategories) },
            stroke: { curve: 'smooth' },
            fill: { opacity: 0.3 }
        }).render();

        new ApexCharts(document.querySelector('#top-supplier-chart'), {
            series: [{ name: 'Jumlah PO', data: @json($supplierCounts) }],
            chart: { height: 280, type: 'bar', toolbar: { show: false } },
            colors: ['#20c997'],
            plotOptions: { bar: { horizontal: true } },
            xaxis: { categories: @json($supplierNames) },
            dataLabels: { enabled: true }
        }).render();
    </script>
@endpush