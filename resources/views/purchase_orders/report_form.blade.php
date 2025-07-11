<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Laporan Purchase Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!-- Header -->
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
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img src={{asset("assets/dist/assets/img/user1-128x128.jpg")}} alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                    </div>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
            </li>
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }} class="user-image rounded-circle shadow" alt="User Image" />
                <span class="d-none d-md-inline">Admin</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }} class="rounded-circle shadow" alt="User Image" />
                  <p>Admin - Web Developer <small>Member since Jan. 2024</small></p>
                </li>
                <li class="user-footer">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                  <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Sidebar -->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="/dashboard" class="brand-link">
            <img src={{asset("assets/dist/assets/img/LogoRPL.png")}} alt="RPL" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">ERP RPL UAD</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item">
                <a href="/dashboard" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/product/list" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Produk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>
                    Supplier
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="./widgets/small-box.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Small Box</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/supplier/pic/add" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tambah PIC supplier</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="./widgets/cards.html" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Cards</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Purchase Orders
                  </p>
                </a>                
              </li>
              <li class="nav-item">
                <a href="{{ route('branch.list') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Branch
                  </p>
                </a>                
              </li>
              <li class="nav-item">
              <a href="{{ route('item.list') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Item
                  </p>
                </a>                
              </li>
            </ul>
          </nav>
        </div>
      </aside>

      <!-- Main content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Laporan Purchase Order</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="/purchase-orders">Purchase Orders</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Form Laporan Purchase Order</h3>
                  </div>
                  <form action="{{ route('purchase_orders.pdf') }}" method="POST">
                    @csrf
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="end_date">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                            @error('end_date')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="form-group mb-4">
                        <label for="supplier_id">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                          <option value="">Pilih Supplier</option>
                          @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->company_name }}</option>
                          @endforeach
                        </select>
                        @error('supplier_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-pdf me-1"></i> Generate PDF</button>
                      <a href="/purchase-orders" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
  </body>
</html>