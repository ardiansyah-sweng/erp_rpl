@extends('layouts.app')

@section('title', 'Merk')

@section('page-title')
							<h3 class="mb-0 me-2">Merk</h3>
							<a href="{{ route('merks.create') }}" class="btn btn-primary btn-sm">Tambah</a>
							<a href="{{ route('merk.print') }}" class="btn btn-primary btn-sm ms-2" target="_blank">Cetak</a>
@endsection

@section('breadcrumb')
								<li class="breadcrumb-item active" aria-current="page">Merk</li>
@endsection

@section('content')
			<div class="card mb-4">
				<div class="card-header d-flex justify-content-between align-items-center">
					<div>
						<h3 class="card-title">List Merk</h3>
						<div class="mt-1">
							<span class="card-title">Jumlah Merk: {{ $totalMerks ?? 0 }}</span>
						</div>
					</div>
					<form action="{{ route('merk.index') }}" method="GET" class="d-flex ms-auto">
						<!-- Search bar berada di ujung kanan -->
						<div class="input-group input-group-sm ms-auto" style="width: 560px;">
							<input type="text" name="search" class="form-control" 
								   placeholder="Search Merk" value="{{ $search ?? '' }}">
							<select name="status" class="form-select">
								<option value="">Semua Status</option>
								<option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
								<option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
							</select>
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
										@if(($search ?? false) || ($status ?? false))
											Tidak ada merk yang sesuai dengan filter
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
@endsection
