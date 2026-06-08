<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Edit Bill of Material</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.css') }}" />
  </head>
  <!--end::Head-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="{{ asset('assets/dist/assets/img/user2-160x160.jpg') }}"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Mimin Gantenk</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <!--end::Header-->

      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="{{ url('/dashboard') }}" class="brand-link">
            <img
              src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}"
              alt="RPL"
              class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">ERP RPL UAD</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('product.list') }}" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Produk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('purchase.orders') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>Purchase Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('item.list') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>Item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('bom.list') }}" class="nav-link active">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>Bill Of Material</p>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </aside>
      <!--end::Sidebar-->

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h3 class="mb-0">Edit Bill of Material</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('bom.list') }}">BOM</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">
            <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Form Edit Bill of Material</h3>
                  </div>

                  <form method="POST" action="{{ route('bom.update', $bom->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                      @if ($errors->any())
                        <div class="alert alert-danger">
                          <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                        </div>
                      @endif

                      <!-- BOM ID (readonly) -->
                      <div class="mb-3">
                        <label class="form-label fw-semibold">BOM ID</label>
                        <input
                          type="text"
                          class="form-control"
                          value="{{ $bom->bom_id }}"
                          disabled
                        />
                        <small class="text-muted">BOM ID tidak dapat diubah.</small>
                      </div>

                      <!-- Nama BOM -->
                      <div class="mb-3">
                        <label for="bom_name" class="form-label fw-semibold">Nama BOM <span class="text-danger">*</span></label>
                        <input
                          type="text"
                          name="bom_name"
                          id="bom_name"
                          class="form-control @error('bom_name') is-invalid @enderror"
                          value="{{ old('bom_name', $bom->bom_name) }}"
                          placeholder="Masukkan nama BOM"
                          required
                        />
                        @error('bom_name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Measurement Unit -->
                      <div class="mb-3">
                        <label for="measurement_unit" class="form-label fw-semibold">Measurement Unit <span class="text-danger">*</span></label>
                        <select
                          name="measurement_unit"
                          id="measurement_unit"
                          class="form-select @error('measurement_unit') is-invalid @enderror"
                          required
                        >
                          @foreach(['PCS', 'KG', 'L', 'Meter', 'Set', 'Pack', 'TON', 'Kwintal', 'Liter'] as $unit)
                            <option value="{{ $unit }}" {{ old('measurement_unit', $bom->measurement_unit) === $unit ? 'selected' : '' }}>
                              {{ $unit }}
                            </option>
                          @endforeach
                        </select>
                        @error('measurement_unit')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Total Cost -->
                      <div class="mb-3">
                        <label for="total_cost" class="form-label fw-semibold">Total Cost (Rp) <span class="text-danger">*</span></label>
                        <input
                          type="number"
                          name="total_cost"
                          id="total_cost"
                          class="form-control @error('total_cost') is-invalid @enderror"
                          value="{{ old('total_cost', $bom->total_cost) }}"
                          placeholder="0"
                          min="0"
                          required
                        />
                        @error('total_cost')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                      <!-- Status Aktif -->
                      <div class="mb-3">
                        <label for="active" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select
                          name="active"
                          id="active"
                          class="form-select @error('active') is-invalid @enderror"
                          required
                        >
                          <option value="1" {{ old('active', $bom->active) == 1 ? 'selected' : '' }}>Aktif</option>
                          <option value="0" {{ old('active', $bom->active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('active')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer d-flex justify-content-between">
                      <a href="{{ route('bom.list') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                      </a>
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                      </button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!--begin::Footer-->
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Scripts-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
          });
        }
      });
    </script>
    <!--end::Scripts-->
  </body>
</html>
