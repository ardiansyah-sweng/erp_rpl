@php
use App\Helpers\EncryptionHelper;
@endphp
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Purchase Orders</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!-- begin:: Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end:: Tailwind -->
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
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />

    <!-- Bootstrap Modal Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->
            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src={{asset("assets/dist/assets/img/user1-128x128.jpg")}}
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src={{ asset("assets/dist/assets/img/user8-128x128.jpg") }}
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src={{ asset("assets/dist/assets/img/user3-128x128.jpg") }}
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-end fs-7 text-warning">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">The subject goes here</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Mimin Gantenk</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Alexander Pierce - Web Developer
                    <small>Member since Nov. 2023</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-body">
                  <!--begin::Row-->
                  <div class="row">
                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                  </div>
                  <!--end::Row-->
                </li>
                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                  <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="dashboard" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src={{asset("assets/dist/assets/img/LogoRPL.png")}}
              alt="RPL"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">ERP RPL UAD</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item">
                <a href="dashboard" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./generate/theme.html" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Produk</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-circle"></i>
                  <p>
                    Supplier
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="/supplier/pic/add" class="nav-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Tambah PIC supplier</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="{{ route('purchase.orders') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Purchase Orders
                    <!-- <span class="nav-badge badge text-bg-secondary me-3">6</span>
                    <i class="nav-arrow bi bi-chevron-right"></i> -->
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
                      <p>Item</p>
                    </a>
                  </li>
              </li>
              <li class="nav-item">
              <a href="{/category" class="nav-link">
              <i class="nav-icon bi bi-box-seam-fill"></i>
                      <p>Kategori</p>
                    </a>
              </li>
            <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>
    <!--end::Sidebar-->
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <!-- Tempat menaruh konten purchase_orders -->
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-md-12">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <h2 class="card-title mb-0 me-2">Purchase Orders</h2>
                    <!-- <a href="{{ route('purchase_orders.add') }}" class="btn btn-primary btn-sm">Add</a> -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPurchaseOrderModal">  Add </button>
                    <a href="{{ route('purchase_orders.report_form') }}" class="btn btn-primary ms-2">Cetak PDF</a>
                  </div>

                  <!-- Modal -->
                  <div class="modal fade" id="addPurchaseOrderModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalTitle">Add Purchase Order</h5>
                        </div>
                        <div class="modal-body">
                          <form id="purchaseOrderForm">
                            <!-- PO Number -->
                            <div class="form-group">
                              <label for="po_number">PO Number</label>
                              <input type="text" class="form-control" id="po_number" value="PO0001" readonly>
                            </div>
                            <div class="form-group">
                              <label for="branch">Cabang</label>
                              <!-- <input type="text" class="form-control" id="branch" placeholder="Masukkan nama cabang"> -->
                              <select class="form-control" id="branch">
                                <option value="">Pilih Cabang</option>
                                <option value="Yogyakarta">Yogyakarta</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Surakarta">Surakarta</option>
                                <option value="Bogor">Bogor</option>
                                <option value="Surabaya">Surabaya</option>
                              </select>
                            </div>
                            <!-- Supplier ID dan Nama Supplier -->
                            <div class="form-group">
                                <label for="supplier_id">ID Supplier</label>
                                <input type="text" id="supplierSearch" class="form-control" placeholder="Cari Supplier">
                                <select class="form-control" id="supplier_id" size="5" style="display:none;">
                                    <option value="SUP001">SUP001 - Penyetor Kaos</option>
                                    <option value="SUP002">SUP002 - Penyetor Celana</option>
                                </select>
                            </div>
                            <div class="form-group">
                              <label for="supplier_name">Nama Supplier</label>
                              <input type="text" class="form-control" id="supplier_name" readonly>
                            </div>

                            <table class="table" id="itemsTable">
                              <thead>
                                <tr>
                                  <th>SKU</th>
                                  <th>Nama Item</th>
                                  <th>Qty</th>
                                  <th>Unit Price</th>
                                  <th>Amount</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              <tr>
                                <td>
                                  <input type="text" class="form-control sku-search" placeholder="Cari SKU">
                                  <select class="form-control sku-dropdown" size="5" style="display:none;"></select>
                                </td>
                                <td><input type="text" class="form-control nama-item" readonly></td>
                                <td><input type="number" class="form-control qty" value="1"></td>
                                <td><input type="number" class="form-control unit-price" value="0"></td>
                                <td><input type="number" class="form-control amount" value="0" readonly></td>
                                <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
                              </tr>
                              </tbody>
                            </table>
                            <button type="button" id="addRow" class="btn btn-info mb-3">Tambah Barang</button>

                            <div class="form-group">
                              <label>Sub Total Rp.</label>
                              <input type="text" class="form-control" id="subtotal" readonly>
                            </div>
                            <div class="form-group">
                              <label>Tax Rp.</label>
                              <input type="text" class="form-control" id="tax" readonly>
                            </div>

                            <button type="button" id="submitBtn" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--begin::Start Search Bar-->
                  <form action="{{ route('purchase_orders.search') }}" method="GET" class="d-flex ms-auto">
                      <div class="input-group input-group-sm ms-auto" style="width: 450px;">
                          <input type="text" name="keyword" class="form-control" placeholder="Search Purchase Order" value="{{ request('keyword') }}">
                          <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                  <i class="bi bi-search"></i>
                              </button>
                          </div>
                      </div>
                  </form>


                  <!--end::Start Search Bar-->
                </div>
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">No</th>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @isset($purchaseOrders)
                      @forelse($purchaseOrders as $index => $order)
                      <tr class="align-middle">
                        <td>{{ $index + 1 }}</td>
                        <td><a href="/purchase_orders/detail/{{ EncryptionHelper::encrypt($order->po_number) }}">{{ $order->po_number }}</a></td>
                        <td><a href="#">{{ $order->supplier ? $order->supplier->company_name : 'Supplier not found' }}</a></td>
                        <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                        <!-- <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</td> -->
                        <td>{{ $order->status }}</td>
                        <td>
                          <a href="#" class="btn btn-sm btn-primary">Edit</a>
                          <a href="#" class="btn btn-sm btn-danger">Delete</a>
                          <a href="/purchase_orders/detail/{{ EncryptionHelper::encrypt($order->po_number) }}" class="btn btn-sm btn-info">Detail</a>
                           <a href="goods_receipt_note/add" class="btn btn-sm btn-warning">GRN</a>
                           <a href="goods_receipt_note/detail" class="btn btn-sm btn-success">Detail GRN</a>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="8" class="py-3 px-6 text-center">Tidak ada data purchase order.</td> <!-- Updated colspan -->
                      </tr>
                      @endforelse
                      @endisset
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  {{ $purchaseOrders->links('pagination::bootstrap-4') }}
                </div>
              </div>
              <!--end::Row-->
            </div>
            <!--end::Container-->
          </div>

          <!--end::App Content-->
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
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="../../dist/js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
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
  <!-- OPTIONAL SCRIPTS -->
  <!-- sortablejs -->
  <script
    src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
    integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
    crossorigin="anonymous"></script>
  <!-- sortablejs -->
  <script>
    const connectedSortables = document.querySelectorAll('.connectedSortable');
    connectedSortables.forEach((connectedSortable) => {
      let sortable = new Sortable(connectedSortable, {
        group: 'shared',
        handle: '.card-header',
      });
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
      cardHeader.style.cursor = 'move';
    });
  </script>
  <!-- apexcharts -->
  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>
  <!-- ChartJS -->
  <script>
    // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
    // IT'S ALL JUST JUNK FOR DEMO
    // ++++++++++++++++++++++++++++++++++++++++++

    const sales_chart_options = {
      series: [{
          name: 'Digital Goods',
          data: [28, 48, 40, 19, 86, 27, 90],
        },
        {
          name: 'Electronics',
          data: [65, 59, 80, 81, 56, 55, 40],
        },
      ],
      chart: {
        height: 300,
        type: 'area',
        toolbar: {
          show: false,
        },
      },
      legend: {
        show: false,
      },
      colors: ['#0d6efd', '#20c997'],
      dataLabels: {
        enabled: false,
      },
      stroke: {
        curve: 'smooth',
      },
      xaxis: {
        type: 'datetime',
        categories: [
          '2023-01-01',
          '2023-02-01',
          '2023-03-01',
          '2023-04-01',
          '2023-05-01',
          '2023-06-01',
          '2023-07-01',
        ],
      },
      tooltip: {
        x: {
          format: 'MMMM yyyy',
        },
      },
    };

    const sales_chart = new ApexCharts(
      document.querySelector('#revenue-chart'),
      sales_chart_options,
    );
    sales_chart.render();
  </script>
  <!-- jsvectormap -->
  <script
    src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
    integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
    integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
    crossorigin="anonymous"></script>
  <!-- jsvectormap -->
  <script>
    const visitorsData = {
      US: 398, // USA
      SA: 400, // Saudi Arabia
      CA: 1000, // Canada
      DE: 500, // Germany
      FR: 760, // France
      CN: 300, // China
      AU: 700, // Australia
      BR: 600, // Brazil
      IN: 800, // India
      GB: 320, // Great Britain
      RU: 3000, // Russia
    };

    // World map by jsVectorMap
    const map = new jsVectorMap({
      selector: '#world-map',
      map: 'world',
    });

    // Sparkline charts
    const option_sparkline1 = {
      series: [{
        data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
      }, ],
      chart: {
        type: 'area',
        height: 50,
        sparkline: {
          enabled: true,
        },
      },
      stroke: {
        curve: 'straight',
      },
      fill: {
        opacity: 0.3,
      },
      yaxis: {
        min: 0,
      },
      colors: ['#DCE6EC'],
    };

    const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
    sparkline1.render();

    const option_sparkline2 = {
      series: [{
        data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
      }, ],
      chart: {
        type: 'area',
        height: 50,
        sparkline: {
          enabled: true,
        },
      },
      stroke: {
        curve: 'straight',
      },
      fill: {
        opacity: 0.3,
      },
      yaxis: {
        min: 0,
      },
      colors: ['#DCE6EC'],
    };

    const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
    sparkline2.render();

    const option_sparkline3 = {
      series: [{
        data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
      }, ],
      chart: {
        type: 'area',
        height: 50,
        sparkline: {
          enabled: true,
        },
      },
      stroke: {
        curve: 'straight',
      },
      fill: {
        opacity: 0.3,
      },
      yaxis: {
        min: 0,
      },
      colors: ['#DCE6EC'],
    };

    const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
    sparkline3.render();
  </script>

  <!-- jQuery
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

  <!-- AdminLTE JS -->
  <script src={{ asset("assets/dist/js/adminlte.js") }}></script>

  <!-- Custom Sidebar Toggle Script -->
  <script>
    $(document).ready(function() {
      $('[data-widget="pushmenu"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
      });
    });
  </script>

  <script>
      // Data Dummy untuk Supplier dan Item
      const suppliers = {
          "SUP001": "Penyetor Kaos",
          "SUP002": "Penyetor Celana",
      };

      const items = {
          "SUP001": {
              "KAOS-s": { name: "Kaos Kecil", price: 1000 },
              "KAOS-m": { name: "Kaos Sedang", price: 2000 },
              "KAOS-l": { name: "Kaos Besar", price: 3000 },
          },
          "SUP002": {
              "CELANA-s": { name: "Celana Kecil", price: 1000 },
              "CELANA-m": { name: "Celana Sedang", price: 2000 },
              "CELANA-l": { name: "Celana Besar", price: 3000 },
          }
      };

      document.getElementById('supplierSearch').addEventListener('input', function() {
          const filter = this.value.toLowerCase();
          const options = document.getElementById('supplier_id').options;
          
          // Menyembunyikan semua option yang tidak sesuai
          for (let i = 0; i < options.length; i++) {
              const option = options[i];
              const text = option.text.toLowerCase();
              option.style.display = text.includes(filter) ? "" : "none";  // Menyembunyikan yang tidak sesuai
          }

          // Menampilkan dropdown jika input ada
          document.getElementById('supplier_id').style.display = filter ? 'block' : 'none';
      });

      document.getElementById('supplier_id').addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const supplierId = selectedOption.value; // Ambil ID Supplier yang dipilih
          const supplierName = selectedOption.text.split(' - ')[1]; // Ambil Nama Supplier berdasarkan ID

          // Isi kolom Nama Supplier
          document.getElementById('supplier_name').value = supplierName;

          // Isi kolom ID Supplier
          document.getElementById('supplierSearch').value = supplierId; // Isi dengan ID Supplier yang dipilih

          // Sembunyikan dropdown setelah memilih
          document.getElementById('supplier_id').style.display = 'none';  // Menyembunyikan dropdown setelah memilih
      });

      // Fungsi untuk menampilkan dropdown SKU berdasarkan supplier
      function populateSKU(supplierId) {
          const supplierItems = items[supplierId];
          $('#itemsTable tbody tr').each(function() {
              const skuDropdown = $(this).find('.sku');
              skuDropdown.empty(); // Kosongkan dropdown SKU

              if (supplierItems) {
                  // Menambah opsi SKU ke dropdown
                  for (const sku in supplierItems) {
                      const item = supplierItems[sku];
                      skuDropdown.append(`<option value="${sku}">${sku} - ${item.name}</option>`);
                  }
              }
              skuDropdown.css('display', supplierItems ? 'block' : 'none'); // Tampilkan dropdown jika ada item
          });
      }

      // Menambahkan event listener untuk menampilkan dropdown SKU berdasarkan supplier
      document.getElementById('skuSearch').addEventListener('input', function() {
          const filter = this.value.toLowerCase();
          const supplierId = document.getElementById('supplier_id').value;
          const itemsList = items[supplierId] || {}; // Ambil daftar item berdasarkan supplier yang dipilih
          const skuOptions = document.getElementById('sku_id');

          skuOptions.innerHTML = ''; // Kosongkan semua opsi sebelumnya

          if (filter.length > 0 && itemsList) {
              for (let sku in itemsList) {
                  const item = itemsList[sku];
                  if (item.name.toLowerCase().includes(filter)) {
                      // Menambahkan option SKU yang sesuai dengan pencarian
                      skuOptions.innerHTML += `<option value="${sku}">${sku} - ${item.name}</option>`;
                  }
              }
              skuOptions.style.display = filter.length > 0 ? 'block' : 'none'; // Tampilkan dropdown SKU jika ada input
          } else {
              skuOptions.style.display = 'none'; // Sembunyikan dropdown jika tidak ada filter
          }
      });

      // Ketika SKU dipilih
      document.getElementById('sku_id').addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const sku = selectedOption.value;
          const supplierId = document.getElementById('supplier_id').value;
          const item = items[supplierId] ? items[supplierId][sku] : null;

          if (item) {
              document.getElementById('item_name').value = item.name;  // Isi Nama Item
              document.getElementById('unit_price').value = item.price;  // Isi Unit Price dengan harga yang sesuai
          } else {
              document.getElementById('item_name').value = '';  // Kosongkan Nama Item jika SKU tidak ditemukan
              document.getElementById('unit_price').value = '';  // Kosongkan Unit Price
          }

          // Menyembunyikan dropdown SKU setelah memilih SKU
          document.getElementById('sku_id').style.display = 'none';  // Menyembunyikan dropdown setelah memilih SKU
      });

      // Ketika ID Supplier diubah
      $('#supplier_id').on('change', function() {
          const supplierId = $(this).val();  // Ambil ID Supplier dari input
          const supplierName = suppliers[supplierId];  // Cari nama supplier berdasarkan ID

          if (supplierName) {
              $('#supplier_name').val(supplierName);  // Isi nama supplier secara otomatis
              populateSKU(supplierId);  // Populasi SKU berdasarkan supplier yang dipilih
          } else {
              $('#supplier_name').val('');  // Kosongkan nama supplier jika tidak ditemukan
          }
      });

      // Fungsi untuk menghitung Amount (Qty * Unit Price)
      function updateAmount(row) {
          const qty = parseFloat(row.find('.qty').val()) || 0;  // Ambil Qty, default 0 jika kosong
          const price = parseFloat(row.find('.unit-price').val()) || 0;  // Ambil Unit Price, default 0 jika kosong
          const amount = qty * price;  // Hitung Amount (Qty * Unit Price)

          row.find('.amount').val(amount.toFixed(2));  // Tampilkan amount pada kolom Amount (2 angka desimal)
          updateTotal();  // Update Subtotal dan Tax setelah Amount berubah
      }

      // Fungsi untuk menghitung Subtotal dan Tax
      function updateTotal() {
          let total = 0;
          $(".amount").each(function () {
              total += parseFloat($(this).val()) || 0;  // Menjumlahkan semua Amount
          });
          $("#subtotal").val(total.toLocaleString("id-ID"));  // Tampilkan Subtotal
          $("#tax").val(total.toLocaleString("id-ID"));  // Tax sama dengan Subtotal untuk sementara
      }

      // Update Total ketika Qty atau Unit Price diubah
      $(document).on('input', '.qty, .unit-price', function() {
          const row = $(this).closest('tr'); // Ambil baris yang terkait
          updateAmount(row); // Panggil fungsi untuk update Amount dan Total
      });

      // Menghapus baris item
      $(document).on('click', '.remove', function() {
          $(this).closest('tr').remove();
          updateTotal();  // Update total setelah menghapus
      });

  </script>

  <script>
  $(document).ready(function() {
    // Disable input SKU saat awal
    $(".sku-search").prop('disabled', true); 
    $(".sku-dropdown").hide(); // Sembunyikan dropdown SKU

    // Aktifkan input SKU hanya jika cabang dan supplier terisi
    $('#branch, #supplier_id').on('input change', function() {
      if ($('#branch').val() && $('#supplier_id').val()) {
        $(".sku-search").prop('disabled', false); // Enable SKU search
      } else {
        $(".sku-search").prop('disabled', true); // Disable SKU search
        $(".sku-dropdown").hide(); // Hide dropdown SKU
      }
    });

    // Ketika input SKU diklik, pastikan cabang dan supplier terisi
    $('.sku-search').on('click', function() {
      if (!$('#branch').val() || !$('#supplier_id').val()) {
        alert("Pilih Cabang dan Supplier terlebih dahulu!");
        return false; // Hentikan jika cabang dan supplier belum terisi
      }
    });

    // Saat pengguna mulai mengetik SKU, munculkan dropdown SKU
    $(document).on('input', '.sku-search', function() {
      const row = $(this).closest('tr');
      const filter = $(this).val().toLowerCase();
      const supplierId = $('#supplier_id').val();
      const itemsList = items[supplierId] || {};
      const skuDropdown = row.find('.sku-dropdown');

      skuDropdown.empty();
      if (filter.length > 0) {
        for (let sku in itemsList) {
          const item = itemsList[sku];
          if (item.name.toLowerCase().includes(filter)) {
            skuDropdown.append(`<option value="${sku}">${sku} - ${item.name}</option>`);
          }
        }
        skuDropdown.show();
      } else {
        skuDropdown.hide();
      }
    });

    // Ketika SKU dipilih dari dropdown
    $(document).on('change', '.sku-dropdown', function() {
      const row = $(this).closest('tr');
      const sku = $(this).val();
      const supplierId = $('#supplier_id').val();
      const item = items[supplierId] ? items[supplierId][sku] : null;

      if (item) {
        row.find('.sku-search').val(sku);
        row.find('.nama-item').val(item.name);
        row.find('.unit-price').val(item.price);
        updateAmount(row);
      }
      $(this).hide(); // Sembunyikan dropdown SKU setelah memilih
    });

    // Fungsi untuk menghitung Amount
    function updateAmount(row) {
      const qty = parseFloat(row.find('.qty').val()) || 0;
      const price = parseFloat(row.find('.unit-price').val()) || 0;
      const amount = qty * price;
      row.find('.amount').val(amount.toFixed(2));
      updateTotal();
    }

    // Fungsi untuk menghitung Subtotal dan Tax
    function updateTotal() {
      let total = 0;
      $(".amount").each(function () {
        total += parseFloat($(this).val()) || 0;
      });
      $("#subtotal").val(total.toLocaleString("id-ID"));
      $("#tax").val(total.toLocaleString("id-ID"));
    }

    // Update Total ketika Qty atau Unit Price diubah
    $(document).on('input', '.qty, .unit-price', function() {
      const row = $(this).closest('tr');
      updateAmount(row);
    });

    // Menghapus baris item
    $(document).on('click', '.remove', function() {
      $(this).closest('tr').remove();
      updateTotal();
    });

    // Menambahkan baris item baru
    $(document).on('click', '#addRow', function() {
      const newRow = `
        <tr>
          <td>
            <input type="text" class="form-control sku-search" placeholder="Cari SKU">
            <select class="form-control sku-dropdown" size="5" style="display:none;"></select>
          </td>
          <td><input type="text" class="form-control nama-item" readonly></td>
          <td><input type="number" class="form-control qty" value="1" min="1"></td>
          <td><input type="number" class="form-control unit-price" value="0"></td>
          <td><input type="number" class="form-control amount" value="0" readonly></td>
          <td><button type="button" class="btn btn-danger remove">Hapus</button></td>
        </tr>
      `;
      $('#itemsTable tbody').append(newRow);
    });

    // Menyusun data form saat tombol submit ditekan
    document.getElementById('submitBtn').addEventListener('click', function () {
      const po_number = document.getElementById('po_number').value;
      const supplier_id = document.getElementById('supplierSearch').value;
      const supplier_name = document.getElementById('supplier_name').value;
      const branch = document.getElementById('branch').value;

      const items = [];
      document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        const sku = row.querySelector('.sku-search')?.value || '';
        const name = row.querySelector('.nama-item')?.value || '';
        const qty = row.querySelector('.qty')?.value || '';
        const unitPrice = row.querySelector('.unit-price')?.value || '';
        const amount = row.querySelector('.amount')?.value || '';

        items.push({
          sku,
          name,
          qty,
          unitPrice,
          amount
        });
      });

      const subtotal = document.getElementById('subtotal').value;
      const tax = document.getElementById('tax').value;

      const formData = {
        po_number,
        supplier_id,
        supplier_name,
        branch,
        items,
        subtotal,
        tax
      };

      console.log("Form Data JSON:", formData);
    });
  });
  </script>
  <script>
    $(document).ready(function() {
        // Menambahkan event listener baru pada tombol submit
        $('#submitBtn').on('click', function() {
            // Beri jeda singkat agar script lama (jika ada) berjalan lebih dulu
            setTimeout(function() {
                // Mengambil semua data dari form di dalam modal
                const dataForEmail = {
                    header: {
                        po_number: $('#po_number').val(),
                        branch: $('#branch').val(),
                        supplier_name: $('#supplier_name').val(),
                        supplier_id: $('#supplierSearch').val(),
                        order_date: new Date().toISOString().slice(0, 10)
                    },
                    items: [],
                    subtotal: $('#subtotal').val().replace(/[^0-9]/g, ''),
                    tax: $('#tax').val().replace(/[^0-9]/g, '')
                };

                $('#itemsTable tbody tr').each(function() {
                    const row = $(this);
                    const sku = row.find('.sku-search').val();
                    if (sku) {
                        dataForEmail.items.push({
                            sku: sku,
                            name: row.find('.nama-item').val(),
                            qty: row.find('.qty').val(),
                            unitPrice: row.find('.unit-price').val(),
                            amount: row.find('.amount').val()
                        });
                    }
                });

                // Jika tidak ada item, batalkan pengiriman
                if (dataForEmail.items.length === 0) {
                    return;
                }

                // Kirim data ke controller menggunakan AJAX
                $.ajax({
                    url: '{{ route("purchase_orders.send_email") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(dataForEmail),
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(response) {
                        console.log('Email Terkirim:', response.success);
                        // Jika ingin menampilkan notifikasi, bisa pakai alert:
                        alert(response.success);
                    },
                    error: function(xhr) {
                        console.error('Gagal Kirim Email:', xhr.responseJSON.error);
                        alert('Gagal mengirim email: ' + xhr.responseJSON.error);
                    }
                });
            }, 500); // Jeda 0.5 detik
        });
    });
  </script>
  <!--end::Script-->
</body>
<!--end::Body-->

</html>