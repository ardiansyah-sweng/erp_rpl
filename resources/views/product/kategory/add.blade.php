<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ERP RPL UAD - Kategori Produk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap, AdminLTE, Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" rel="stylesheet">

  <!-- Custom Style -->
  <style>
    body {
      background: url('/mnt/data/2d664b90-b69e-4764-8593-9785f74210ac.png') no-repeat center center fixed;
      background-size: cover;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      margin-bottom: 2rem;
      transition: transform 0.3s ease, background 0.5s;
    }

    .glass-card:hover {
      transform: scale(1.02);
    }

    .fade-in {
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      0% { opacity: 0; transform: translateY(10px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    label.form-check-label {
      margin-left: 0.5rem;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Top Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="bi bi-list"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ms-auto">
    <!-- Search Icon -->
    <li class="nav-item">
      <a class="nav-link" data-widget="navbar-search" href="#" role="button">
        <i class="bi bi-search"></i>
      </a>
    </li>

    <!-- Notification Icon -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-bs-toggle="dropdown" href="#">
        <i class="bi bi-bell-fill"></i>
        <span class="badge bg-danger">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <span class="dropdown-header">3 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">New user registered</a>
        <a href="#" class="dropdown-item">Order #1234 placed</a>
        <a href="#" class="dropdown-item">System update available</a>
      </div>
    </li>

    <!-- User Profile -->
    <li class="nav-item dropdown">
      <a class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" href="#" role="button">
        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User Avatar" class="img-size-32 img-circle me-2" style="width:32px;height:32px;">
        <span>Mimin Gantenk</span>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <a href="#" class="dropdown-item">Profile</a>
        <a href="#" class="dropdown-item">Settings</a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">Logout</a>
      </div>
    </li>
  </ul>
</nav>


  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <img src="https://cdn-icons-png.flaticon.com/512/9068/9068756.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ERP RPL UAD</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="#" class="nav-link active">
              <i class="nav-icon bi bi-speedometer2"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-box"></i>
              <p>Produk</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content pt-4">
      <div class="container-fluid fade-in">
        <h2 class="text-center mb-4 fw-bold text-primary">Manajemen Kategori Produk</h2>
        <div class="row">
          <!-- Grup Kategori -->
          <div class="col-md-6">
            <div class="glass-card">
              <h4 class="mb-3"><i class="bi bi-box-fill text-primary"></i> Tambah Grup Kategori</h4>
              <form>
                <div class="mb-3">
                  <label class="form-label">Nama Grup Kategori Produk</label>
                  <input type="text" class="form-control" placeholder="Contoh: Elektronik" required>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="aktifGrup" checked>
                  <label class="form-check-label" for="aktifGrup">Aktif</label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Add</button>
                <button type="reset" class="btn btn-secondary ms-2">Cancel</button>
              </form>
            </div>
          </div>

          <!-- Sub Kategori -->
          <div class="col-md-6">
            <div class="glass-card">
              <h4 class="mb-3"><i class="bi bi-tags-fill text-info"></i> Tambah Sub Kategori</h4>
              <form>
                <div class="mb-3">
                  <label class="form-label">Nama Sub Kategori Produk</label>
                  <input type="text" class="form-control" placeholder="Contoh: HP Second" required>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="grupKategoriCheck" checked>
                  <label class="form-check-label" for="grupKategoriCheck">Grup Kategori Produk</label>
                </div>
                <div class="mb-3">
                  <select class="form-select">
                    <option selected disabled>Pilih Grup Kategori</option>
                    <option value="elektronik">Elektronik</option>
                    <option value="fashion">Fashion</option>
                    <option value="makanan">Makanan</option>
                  </select>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" id="aktifSub" checked>
                  <label class="form-check-label" for="aktifSub">Aktif</label>
                </div>
                <button type="submit" class="btn btn-info text-white"><i class="bi bi-plus-circle me-1"></i>Add</button>
                <button type="reset" class="btn btn-secondary ms-2">Cancel</button>
              </form>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>

</body>
</html>
