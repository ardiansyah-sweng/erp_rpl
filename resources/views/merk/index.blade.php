<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ERP RPL UAD | Merk</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
    <!--end::Required Plugin(AdminLTE)-->
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
	<div class="app-wrapper">
		<!-- Sidebar & Header -->
		<nav class="app-header navbar navbar-expand bg-body">
			<div class="container-fluid">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
							<i class="bi bi-list"></i>
						</a>
					</li>
					<li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
					<li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
				</ul>
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link" data-widget="navbar-search" href="#" role="button">
							<i class="bi bi-search"></i>
						</a>
					</li>
					<li class="nav-item dropdown user-menu">
						<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
							<img src="{{ asset('assets/dist/assets/img/user2-160x160.jpg') }}" class="user-image rounded-circle shadow" alt="User Image" />
							<span class="d-none d-md-inline">Mimin Gantenk</span>
						</a>
					</li>
				</ul>
			</div>
		</nav>
		<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
			<div class="sidebar-brand">
				<a href="/dashboard" class="brand-link">
					<img src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}" alt="RPL" class="brand-image opacity-75 shadow" />
					<span class="brand-text fw-light">ERP RPL UAD</span>
				</a>
			</div>
			<div class="sidebar-wrapper">
				<nav class="mt-2">
					<ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
						<li class="nav-item"><a href="/dashboard" class="nav-link"><i class="nav-icon bi bi-speedometer"></i><p>Dashboard</p></a></li>
						<li class="nav-item"><a href="{{ route('product.list') }}" class="nav-link"><i class="nav-icon bi bi-box-seam-fill"></i><p>Produk</p></a></li>
						<li class="nav-item"><a href="{{ route('purchase.orders') }}" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i><p>Purchase Orders</p></a></li>
						<li class="nav-item"><a href="{{ route('branch.list') }}" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i><p>Branch</p></a></li>
						<li class="nav-item"><a href="{{ route('item.list') }}" class="nav-link"><i class="nav-icon bi bi-clipboard-fill"></i><p>Item</p></a></li>
						<li class="nav-item"><a href="{{ route('merks.index') }}" class="nav-link active"><i class="nav-icon bi bi-clipboard-fill"></i><p>Merk</p></a></li>
					</ul>
				</nav>
			</div>
		</aside>
		<!-- Main Content -->
		<main class="app-main">
			<div class="app-content-header">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-sm-6 d-flex align-items-center">
							<h3 class="mb-0 me-2">Merk</h3>
							<a href="{{ route('merks.create') }}" class="btn btn-primary btn-sm">Tambah</a>
							<a href="{{ route('merks.index', ['export' => 'pdf']) }}" class="btn btn-primary btn-sm ms-2">Cetak Merk</a>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-end">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Merk</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<div class="card mb-4">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h3 class="card-title">List Merk</h3>
					<form action="{{ route('merk.index') }}" method="GET" class="d-flex ms-auto">
						<!-- Search bar berada di ujung kanan -->
						<div class="input-group input-group-sm ms-auto" style="width: 450px;">
							<input type="text" name="search" class="form-control" 
								   placeholder="Search Merk" value="{{ $search ?? '' }}">
							<div class="input-group-append">
								<button type="submit" class="btn btn-default">
									<i class="bi bi-search"></i>
								</button>
							</div>
						</div>
					</form>
				</div>

				<!-- Search Form -->
				<div class="card-body">
					<!-- Alert Messages -->
					@if(session('success'))
						<div class="alert alert-success">
							{{ session('success') }}
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger">
							{{ session('error') }}
						</div>
					@endif

					<table class="table table-bordered">
						<thead class="text-center">
							<tr>
								<th style="width: 10px">ID</th>
								<th>Nama Merk</th>
								<th>Status Aktif</th>
								<th>Dibuat</th>
								<th>Diperbarui</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@forelse($merks as $merk)
								<tr>
									<td>{{ $merk->id }}</td>
									<td>
										<a href="{{ route('merk.show', $merk->id) }}" style="color: inherit; text-decoration: none;">
											{{ $merk->merk }}
										</a>
									</td>
									<td class="text-center">
										@if($merk->is_active == 1)
											<i class="bi bi-check-circle-fill text-success"></i>
										@else
											<i class="bi bi-x-circle-fill text-danger"></i>
										@endif
									</td>
									<td>{{ $merk->created_at }}</td>
									<td>{{ $merk->updated_at }}</td>
									<td>
										<a href="{{ route('merks.edit', $merk->id) }}" class="btn btn-sm btn-primary">Edit</a>
										<form action="{{ route('merks.destroy', $merk->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus merk ini?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-danger">Delete</button>
										</form>
										<a href="{{ route('merks.show', $merk->id) }}" class="btn btn-info">Detail</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="text-center">
										@if($search ?? false)
											Tidak ada merk yang ditemukan dengan kata kunci "{{ $search }}"
										@else
											No data available in table
										@endif
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
				<!-- /.card-body -->
				<div class="card-footer clearfix">
					@if(isset($merks) && method_exists($merks, 'links'))
						{{ $merks->appends(request()->query())->links('pagination::bootstrap-4') }}
					@endif
				</div>
			</div>
		</main>
		<!--end::App Main-->
		<!--begin::Footer-->
		<footer class="app-footer">
			<!--begin::To the end-->
			<div class="float-end d-none d-sm-inline">Anything you want</div>
			<!--end::To the end-->
			<!--begin::Copyright-->
			<strong>
				Copyright &copy; 2014-2024&nbsp;
				<a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
			</strong>
			All rights reserved.
			<!--end::Copyright-->
		</footer>
		<!--end::Footer-->
	</div>
	<!--end::App Wrapper-->
	
	<!--begin::Script-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
</body>
</html>
