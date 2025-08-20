@extends('layouts.app')
@section('content')
<!-- List Merk Table -->
<div class="container mt-4">
	<div class="card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h3 class="card-title mb-0">List Merk</h3>
			<button class="btn btn-primary btn-sm">Tambah</button>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>id</th>
							<th>merk</th>
							<th>active</th>
							<th>created_at</th>
							<th>updated_at</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>Voluptatibus</td>
							<td><span class="badge bg-success">1</span></td>
							<td>2025-08-16 09:08:57</td>
							<td>2025-08-16 09:08:57</td>
							<td>
								<button class="btn btn-info btn-sm">Detail</button>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Et</td>
							<td><span class="badge bg-danger">0</span></td>
							<td>2025-08-16 09:08:57</td>
							<td>2025-08-16 09:08:57</td>
							<td>
								<button class="btn btn-info btn-sm">Detail</button>
							</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Beatae</td>
							<td><span class="badge bg-success">1</span></td>
							<td>2025-08-16 09:08:57</td>
							<td>2025-08-16 09:08:57</td>
							<td>
								<button class="btn btn-info btn-sm">Detail</button>
							</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Presentium</td>
							<td><span class="badge bg-danger">0</span></td>
							<td>2025-08-16 09:08:57</td>
							<td>2025-08-16 09:08:57</td>
							<td>
								<button class="btn btn-info btn-sm">Detail</button>
							</td>
						</tr>
						<tr>
							<td>5</td>
							<td>Vitae</td>
							<td><span class="badge bg-success">1</span></td>
							<td>2025-08-16 09:08:57</td>
							<td>2025-08-16 09:08:57</td>
							<td>
								<button class="btn btn-info btn-sm">Detail</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- Static Pagination -->
			<nav>
				<ul class="pagination justify-content-end">
					<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
					<li class="page-item active"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
				</ul>
			</nav>
		</div>
	</div>
</div>
@endsection
