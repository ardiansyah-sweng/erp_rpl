<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Edit PIC Supplier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="ERP RPL UAD | Edit PIC Supplier" />
    <meta name="author" content="ColorlibHQ" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
    </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
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
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
              <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="nav-icon bi bi-speedometer"></i><p>Dashboard</p></a></li>
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active"><i class="nav-icon bi bi-person-circle"></i><p>Supplier<i class="nav-arrow bi bi-chevron-right"></i></p></a>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a href="/supplier/pic/list" class="nav-link active"><i class="nav-icon bi bi-circle"></i><p>List PIC supplier</p></a></li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit PIC Supplier</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Edit PIC Supplier</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <form id="picForm" action="{{ route('supplier.pic.edit', $pic->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                  <div class="mb-3">
                    <label for="supplier_id" class="form-label">ID Supplier</label>
                    <input type="text" class="form-control bg-light" id="supplier_id" name="supplier_id" value="{{ $pic->supplier_id }}" readonly>
                  </div>

                  <div class="mb-3">
                    <label for="pic_name" class="form-label">Nama PIC</label>
                    <input type="text" class="form-control" id="pic_name" name="name" value="{{ old('name', $pic->name) }}" required>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $pic->email) }}" required>
                  </div>

                  <div class="mb-3">
                    <label for="telephone" class="form-label">Telephone</label>
                    <input type="text" class="form-control" id="telephone" name="phone_number" value="{{ old('phone_number', $pic->phone_number) }}" required>
                  </div>

                  <div class="mb-3">
                    <label for="assignment_date" class="form-label">Assignment Date</label>
                    <input type="date" class="form-control" id="assignment_date" name="assigned_date" value="{{ \Carbon\Carbon::parse($pic->assigned_date)->format('Y-m-d') }}" required>
                  </div>

                  <div class="mb-3">
                    <label class="form-label d-block">Foto PIC</label>
                    <img src="{{ $pic->photo ? asset('storage/foto_pic/'.$pic->photo) : asset('assets/dist/assets/img/avatar_default.png') }}" class="img-thumbnail mb-2" style="width: 100px;">
                    <input type="file" class="form-control" id="pic_photo" name="photo">
                  </div>

                  <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ $pic->status ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">Status Aktif</label>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="/supplier/pic/list" class="btn btn-secondary">Back to List</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </main>
      </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
  </body>
</html>