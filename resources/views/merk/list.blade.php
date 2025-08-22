<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ERP RPL UAD | Merk</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
	<link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
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
						<li class="nav-item"><a href="{{ route('merk.list') }}" class="nav-link active"><i class="nav-icon bi bi-clipboard-fill"></i><p>Merk</p></a></li>
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
							<a href="/merk/add" class="btn btn-primary btn-sm">Tambah</a>
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
					<form action="#" method="GET" class="d-flex ms-auto">
						<div class="input-group input-group-sm ms-auto" style="width: 450px;">
							<input type="text" name="search" class="form-control" placeholder="Search Merk">
							<div class="input-group-append">
								<button type="submit" class="btn btn-default">
									<i class="bi bi-search"></i>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead class="text-center">
							<tr>
								<th style="width: 10px">ID</th>
								<th>Merk</th>
								<th>Active</th>
								<th>Created At</th>
								<th>Updated At</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@forelse($merks as $merk)
								<tr>
									<td>{{ $merk->id }}</td>
									<td>{{ $merk->merk }}</td>
									<td class="text-center">
										@if ($merk->active)
											<span class="badge bg-success">Aktif</span>
										@else
											<span class="badge bg-danger">Nonaktif</span>
										@endif
									</td>
									<td>{{ $merk->created_at }}</td>
									<td>{{ $merk->updated_at }}</td>
									<td>
										<a href="#" class="btn btn-sm btn-primary">Edit</a>
										   <form action="{{ route('merk.delete', $merk->id) }}" method="POST" style="display: inline;">
											   @csrf
											   @method('DELETE')
											   <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus merk ini?')">Delete</button>
										   </form>
										<a href="{{ route('merk.detail', $merk->id) }}" class="btn btn-info">Detail</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="text-center">No data available in table</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					<div class="d-flex justify-content-end">
						{{ $merks->links('pagination::bootstrap-4') }}
					</div>
				</div>
			</div>
		</main>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
