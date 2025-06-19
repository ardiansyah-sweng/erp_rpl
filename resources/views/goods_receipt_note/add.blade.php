<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Tambah Goods Receipt Note</title>
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
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
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
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Admin</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img
                    src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Admin - Web Developer
                    <small>Member since Jan. 2024</small>
                  </p>
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
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="/dashboard" class="brand-link">
            <img
              src={{asset("assets/dist/assets/img/LogoRPL.png")}}
              alt="RPL"
              class="brand-image opacity-75 shadow"
            />
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
                <a href="{{ route('purchase.orders') }}" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-fill"></i>
                  <p>
                    Purchase Orders
                  </p>
                </a>                
              </li>
              <li class="nav-item">
                <a href="{{ route('branch.list') }}" class="nav-link active">
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
            </ul>
          </nav>
        </div>
      </aside>
      <!-- Main Content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Add Goods Receipt Note</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                    <h3 class="card-title">Form Goods Receipt Note</h3>
                  </div>

                  <div class="card-body">
                    <div class="form-group mb-3">
                      <label for="po_number">PO Number</label>
                      <input type="text" class="form-control" id="po_number" value="POO0002" readonly>
                    </div>
                    <form>
                        <div class="form-group mb-3">
                            <label for="branch">Branch</label> 
                            <input type="text" class="form-control" id="branch" value="Yogyakarta" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="supplier_id">Supplier ID</label>
                            <input type="text" class="form-control" id="supplier_id" value="SUUP001" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label for="supplier_name">Name Supplier</label>
                            <input type="text" class="form-control" id="supplier_name" value="PT. XYZ" readonly>
                        </div>
                        
                        <div class="table-responsive">
                          <table class="table table-bordered" id="itemsTable">
                              <thead class="table-primary text-center">
                              <tr>
                                  <th>SKU</th>
                                  <th>Name Item</th>
                                  <th>Qty</th>
                                  <th>Unit Price</th>
                                  <th>Amount</th>
                                  <th>Delivery Date</th>
                                  <th>Delivery Quantity</th>
                                  <th>Action</th>
                              </tr>
                              </thead>
                              <tbody class="text-center">
                                <!-- Dummy Data -->
                                <tr>
                                    <td><input type="text" class="form-control" value="KAOS-M" readonly></td>
                                    <td><input type="text" class="form-control" value="Kaos Sedang" readonly></td>
                                    <td><input type="text" class="form-control" value="15" readonly></td>
                                    <td><input type="text" class="form-control" value="20000" readonly></td>
                                    <td><input type="text" class="form-control" value="300000" readonly></td>
                                    <td><input type="date" class="form-control" value="2025-06-10" required></td>
                                    <td><input type="number" class="form-control" value="10" min="0" max="15" required></td>
                                    <td class="mb-3" style="display: flex; gap: 8px;">
                                      <button type="button" class="btn btn-info btn-sm comments">
                                      <i class="bi bi-chat"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                                      <i class="bi bi-trash"></i>
                                      </button>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td><input type="text" class="form-control sku " value="KAOS-L" readonly></td>
                                    <td><input type="text" class="form-control name-item" value="Kaos Besar" readonly></td>
                                    <td><input type="number" class="form-control qty" value="10" readonly></td>
                                    <td><input type="number" class="form-control unit-price" value="20000" readonly></td>
                                    <td><input type="number" class="form-control amount" value="300000" readonly></td>
                                    <td><input type="date" class="form-control delivery-date" value="2025-06-10" required></td>
                                    <td><input type="number" class="form-control delivery-quantity" value="10" min="0" max="15" required></td>
                                    <td class="mb-3" style="display: flex; gap: 8px;">
                                      <button type="button" class="btn btn-info btn-sm comments">
                                      <i class="bi bi-chat"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm remove-row-btn">
                                      <i class="bi bi-trash"></i>
                                      </button>
                                    </td>
                                  </tr>
                              </tbody>
                          </table>
                        </div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary mb-3">
                            <i class="bi bi-check-circle"></i> Tambah
                          </button>
                          <button type="button" class="btn btn-danger mb-3">
                            <i class="bi bi-x-circle"></i> Batal
                          </button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Comment Modal Start -->
      <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="commentModalLabel">
                <i class="bi bi-chat-text"></i> Tambah Komentar
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="commentText" class="form-label">Komentar:</label>
                <textarea class="form-control" id="commentText" rows="5" placeholder="Masukkan komentar Anda di sini..."></textarea>
              </div>
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <small>Komentar ini akan disimpan untuk item yang dipilih dalam tabel.</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Batal
              </button>
              <button type="button" class="btn btn-primary" id="saveComment">
                <i class="bi bi-check-circle"></i> Simpan
              </button>
            </div>
          </div>
        </div>
      </div>
       <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
          Copyright &copy; 2014-2024&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
      </footer>
    </div>
    <!-- Comment Modal end -->
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            const commentTextArea = document.getElementById('commentText');
            let currentCommentButton = null;

            // Event listener untuk klik di dalam tabel (Hapus dan Komen)
            document.getElementById('itemsTable').addEventListener('click', function(e) {
                const targetCommentBtn = e.target.closest('.comments');
                const targetDeleteBtn = e.target.closest('.remove-row-btn');

                if (targetCommentBtn) {
                    currentCommentButton = targetCommentBtn;
                    commentTextArea.value = currentCommentButton.dataset.comment || '';
                    commentModal.show();
                }

                if (targetDeleteBtn) {
                    targetDeleteBtn.closest('tr').remove();
                }
            });

            // Event listener untuk menyimpan komentar
            document.getElementById('saveComment').addEventListener('click', function() {
                if (currentCommentButton) {
                    const newComment = commentTextArea.value.trim();
                    currentCommentButton.dataset.comment = newComment;
                    const icon = currentCommentButton.querySelector('i');
                    if (newComment) {
                        currentCommentButton.classList.replace('btn-info', 'btn-success');
                        icon.classList.replace('bi-chat', 'bi-chat-fill');
                    } else {
                        currentCommentButton.classList.replace('btn-success', 'btn-info');
                        icon.classList.replace('bi-chat-fill', 'bi-chat');
                    }
                }
                commentModal.hide();
            });
        });
    </script>
    
  </body>
</html>