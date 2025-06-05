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
                <a href="#" class="nav-link">
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
                      <input type="text" class="form-control" id="po_number" value="PO0001" readonly>
                    </div>
                    <form>
                        <div class="form-group mb-3">
                            <label for="branch">Cabang</label> 
                            <select class="form-control" id="branch">
                                <option value="">Pilih Cabang</option>
                                <option value="Yogyakarta" selected>Yogyakarta</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Surakarta">Surakarta</option>
                                <option value="Bogor">Bogor</option>
                                <option value="Surabaya">Surabaya</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="supplier_id">ID Supplier</label>
                            <input type="text" id="supplierSearch" class="form-control" placeholder="Cari Supplier">
                            <select class="form-control" id="supplier_id" size="5" style="display:none;">
                              <option value="SUP001">SUP001 - Penyetor Kaos</option>
                              <option value="SUP002">SUP002 - Penyetor Celana</option>
                              <option value="SUP003">SUP003 - Penyetor Topi</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="supplier_name">Nama Supplier</label>
                            <input type="text" class="form-control" id="supplier_name" readonly>
                        </div>
                        
                        <div class="table-responsive">
                          <table class="table table-bordered" id="itemsTable">
                              <thead class="table-primary text-center">
                              <tr>
                                  <th>SKU</th>
                                  <th>Nama Item</th>
                                  <th>Qty</th>
                                  <th>Unit Price</th>
                                  <th>Amount</th>
                                  <th>Delivery Date</th>
                                  <th>Delivery Quantity</th>
                                  <th>Komentar</th>
                                  <th>Action</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td>
                                      <input type="text" class="form-control sku-search" placeholder="Cari SKU">
                                      <select class="form-control sku-dropdown" size="5" style="display:none; position:absolute; z-index:100;"></select>
                                  </td>
                                  <td><input type="text" class="form-control nama-item" readonly></td>
                                  <td><input type="number" class="form-control qty" value="1" min="1"></td>
                                  <td><input type="number" class="form-control unit-price" value="0"></td>
                                  <td><input type="number" class="form-control amount" value="0" readonly></td>
                                  <td><input type="date" class="form-control delivery-date"></td>
                                  <td><input type="number" class="form-control delivery-quantity" value="0"></td>
                                  <td><button type="button" class="btn btn-primary btn-sm comments"><i class="bi bi-chat"></i> Komen</button></td>
                                  <td><button type="button" class="btn btn-danger btn-sm remove"><i class="bi bi-trash"></i> Hapus</button></td>
                              </tr>
                              </tbody>
                          </table>
                        </div>
                        
                        <button type="button" id="addRow" class="btn btn-info mb-3">
                          <i class="bi bi-plus-circle"></i> Tambah Barang
                        </button>

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


    
    <script>
    // Global variable to track current comment button
    let currentCommentButton = null;

    // --- DATA DUMMY ---
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
        },
        "SUP003": {
            "TOPI-s": { name: "Topi Kecil", price: 1000 },
            "TOPI-m": { name: "Topi Sedang", price: 2000 },
            "TOPI-l": { name: "Topi Besar", price: 3000 },
        }
    };
    // --- AKHIR DATA DUMMY ---


    // Function to update amount calculation
    function updateAmount(rowElement) { 
        let qty = parseFloat(rowElement.querySelector(".qty").value) || 0;
        let price = parseFloat(rowElement.querySelector(".unit-price").value) || 0;
        rowElement.querySelector(".amount").value = (qty * price).toFixed(2); 

    }


    function updateTotal() {
        let total = 0;
        document.querySelectorAll(".amount").forEach(function(element) {
            total += parseFloat(element.value) || 0;
        });
    
        console.log("Total:", total); 
    }

    // Function to update comment button appearance
    function updateCommentButtonAppearance(button, hasComment) {
        if (hasComment) {
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
            button.innerHTML = '<i class="bi bi-chat-fill"></i> Komen ✓';
            button.setAttribute('title', 'Komentar tersedia - klik untuk edit');
        } else {
            button.classList.remove('btn-success');
            button.classList.add('btn-primary');
            button.innerHTML = '<i class="bi bi-chat"></i> Komen';
            button.setAttribute('title', 'Tambah komentar');
        }
    }

    // Fungsi untuk menampilkan toast notification (opsional, dari kode Anda)
    function showToast(message, type = 'info') {
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        let toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-bg-${type} border-0`;
        toastElement.setAttribute('role', 'alert');
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toastElement);
        let toast = new bootstrap.Toast(toastElement);
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }


    // Document ready
    document.addEventListener('DOMContentLoaded', function() {

        // Event listener untuk input pencarian supplier
        document.getElementById('supplierSearch').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const optionsContainer = document.getElementById('supplier_id'); 
            const options = optionsContainer.options || optionsContainer.children; 

            // Clear existing options if it's a div/ul structure
            if (optionsContainer.tagName !== 'SELECT') {
                optionsContainer.innerHTML = '';
            }

            let hasMatches = false;
            for (const id in suppliers) {
                const name = suppliers[id];
                const text = `${id} - ${name}`.toLowerCase();
                if (text.includes(filter)) {
                    hasMatches = true;
                    if (optionsContainer.tagName === 'SELECT') {
                        // For select element, add option
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = `${id} - ${name}`;
                        optionsContainer.appendChild(option);
                    } else {
                        // For div/ul, create clickable elements
                        const divOption = document.createElement('div');
                        divOption.className = 'dropdown-item'; 
                        divOption.textContent = `${id} - ${name}`;
                        divOption.dataset.supplierId = id;
                        divOption.dataset.supplierName = name;
                        divOption.addEventListener('click', function() {
                            const selectedId = this.dataset.supplierId;
                            const selectedName = this.dataset.supplierName;
                            document.getElementById('supplierSearch').value = selectedId;
                            document.getElementById('supplier_name').value = selectedName;
                            document.getElementById('supplier_id').style.display = 'none'; 
                            populateSKUDropdowns(selectedId); 
                        });
                        optionsContainer.appendChild(divOption);
                    }
                }
            }

            // Menampilkan/menyembunyikan dropdown
            optionsContainer.style.display = hasMatches && filter.length > 0 ? 'block' : 'none';

            // Disable SKU input until supplier is selected
            if (!document.getElementById('supplier_name').value) {
                document.querySelectorAll(".sku-search").forEach(el => el.disabled = true);
                document.querySelectorAll(".sku-dropdown").forEach(el => el.style.display = 'none');
            }
        });

        // Event listener untuk pemilihan supplier dari dropdown (jika <select>)
        document.getElementById('supplier_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const supplierId = selectedOption.value;
            const supplierName = selectedOption.text.split(' - ')[1]; 

            document.getElementById('supplier_name').value = supplierName;
            document.getElementById('supplierSearch').value = supplierId;
            this.style.display = 'none'; 
            populateSKUDropdowns(supplierId); 
            enableSKUInput(); 
        });

        // Function to populate SKU dropdowns for all rows
        function populateSKUDropdowns(supplierId) {
            const supplierItems = items[supplierId];
            document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
                const skuDropdown = row.querySelector('.sku-dropdown');
                skuDropdown.innerHTML = ''; 

                if (supplierItems) {
                    for (const sku in supplierItems) {
                        const item = supplierItems[sku];
                        const option = document.createElement('option');
                        option.value = sku;
                        option.textContent = `${sku} - ${item.name}`;
                        skuDropdown.appendChild(option);
                    }
                }
            });
        }
        
        // Function to enable SKU input
        function enableSKUInput() {
            if (document.getElementById('branch').value && document.getElementById('supplierSearch').value) { 
                document.querySelectorAll(".sku-search").forEach(el => el.disabled = false);
            }
        }

        // Handle initial SKU input disable/enable
        document.getElementById('branch').addEventListener('change', enableSKUInput);
        document.getElementById('supplierSearch').addEventListener('change', enableSKUInput); 

        // Ketika input SKU di setiap baris diklik, pastikan cabang dan supplier terisi
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('sku-search')) {
                if (!document.getElementById('branch').value || !document.getElementById('supplierSearch').value) {
                    alert("Pilih Cabang dan Supplier terlebih dahulu!");
                    e.preventDefault(); 
                    return false;
                }
            }
        });

        // Saat pengguna mulai mengetik SKU di setiap baris, munculkan dropdown SKU
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('sku-search')) {
                const row = e.target.closest('tr');
                const filter = e.target.value.toLowerCase();
                const supplierId = document.getElementById('supplierSearch').value;
                const itemsList = items[supplierId] || {};
                const skuDropdown = row.querySelector('.sku-dropdown');

                skuDropdown.innerHTML = '';
                if (filter.length > 0) {
                    for (let sku in itemsList) {
                        const item = itemsList[sku];
                        if (item.name.toLowerCase().includes(filter) || sku.toLowerCase().includes(filter)) {
                            const option = document.createElement('option');
                            option.value = sku;
                            option.textContent = `${sku} - ${item.name}`;
                            skuDropdown.appendChild(option);
                        }
                    }
                    skuDropdown.style.display = skuDropdown.options.length > 0 ? 'block' : 'none'; 
                } else {
                    skuDropdown.style.display = 'none';
                }
            }
        });

        // Ketika SKU dipilih dari dropdown di setiap baris
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('sku-dropdown')) {
                const row = e.target.closest('tr');
                const sku = e.target.value;
                const supplierId = document.getElementById('supplierSearch').value;
                const item = items[supplierId] ? items[supplierId][sku] : null;

                if (item) {
                    row.querySelector('.sku-search').value = sku;
                    row.querySelector('.nama-item').value = item.name;
                    row.querySelector('.unit-price').value = item.price;
                    updateAmount(row); 
                }
                e.target.style.display = 'none';
            }
        });


        // Handle qty and price input changes (dari kode Anda)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty') || e.target.classList.contains('unit-price')) {
                let row = e.target.closest('tr');
                updateAmount(row);
            }
        });

        // Handle add row button
        document.getElementById('addRow').addEventListener('click', function() {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control sku-search" placeholder="Cari SKU" ${!document.getElementById('branch').value || !document.getElementById('supplierSearch').value ? 'disabled' : ''}>
                    <select class="form-control sku-dropdown" size="5" style="display:none; position:absolute; z-index:100;"></select>
                </td>
                <td><input type="text" class="form-control nama-item" readonly></td>
                <td><input type="number" class="form-control qty" value="1" min="1"></td>
                <td><input type="number" class="form-control unit-price" value="0"></td>
                <td><input type="number" class="form-control amount" value="0" readonly></td>
                <td><input type="date" class="form-control delivery-date"></td>
                <td><input type="number" class="form-control delivery-quantity" value="0"></td>
                <td><button type="button" class="btn btn-primary btn-sm comments"><i class="bi bi-chat"></i> Komen</button></td>
                <td><button type="button" class="btn btn-danger btn-sm remove"><i class="bi bi-trash"></i> Hapus</button></td>
            `;
            document.querySelector('#itemsTable tbody').appendChild(newRow);
            const currentSupplierId = document.getElementById('supplierSearch').value;
            if (currentSupplierId) {
                populateSKUDropdowns(currentSupplierId);
            }
        });

        // Handle remove button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove') || e.target.closest('.remove')) {
                let button = e.target.classList.contains('remove') ? e.target : e.target.closest('.remove');
                button.closest('tr').remove();
            }
        });

        // Handle comment button clicks
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('comments') || e.target.closest('.comments')) {
                let button = e.target.classList.contains('comments') ? e.target : e.target.closest('.comments');
                currentCommentButton = button;
                
                let currentComment = button.dataset.comment || '';
                
                document.getElementById('commentText').value = currentComment;
                
                let modal = new bootstrap.Modal(document.getElementById('commentModal'));
                modal.show();
            }
        });

        // Handle save comment button
        document.getElementById('saveComment').addEventListener('click', function() {
            let commentText = document.getElementById('commentText').value.trim();
            
            if (currentCommentButton) {
                currentCommentButton.dataset.comment = commentText;
                updateCommentButtonAppearance(currentCommentButton, commentText !== '');
                
                if (commentText !== '') {
                    console.log('Komentar berhasil disimpan:', commentText);
                    showToast('Komentar berhasil disimpan!', 'success');
                }
            }
            
            let modal = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
            modal.hide();
            
            document.getElementById('commentText').value = '';
            currentCommentButton = null;
        });

        // Handle modal close event
        document.getElementById('commentModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('commentText').value = '';
            currentCommentButton = null;
        });
        // Disable SKU search on initial load
        document.querySelectorAll(".sku-search").forEach(el => el.disabled = true);
        document.querySelectorAll(".sku-dropdown").forEach(el => el.style.display = 'none');

        // Menyusun data form saat tombol submit ditekan (diperbarui)
        document.getElementById('submitBtn').addEventListener('click', function () {
            const po_number = document.getElementById('po_number')?.value || '';
            const supplier_id = document.getElementById('supplierSearch')?.value || '';
            const supplier_name = document.getElementById('supplier_name')?.value || '';
            const branch = document.getElementById('branch')?.value || '';

            const itemsData = [];
            document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
                const sku = row.querySelector('.sku-search')?.value || '';
                const name = row.querySelector('.nama-item')?.value || '';
                const qty = row.querySelector('.qty')?.value || '';
                const unitPrice = row.querySelector('.unit-price')?.value || '';
                const amount = row.querySelector('.amount')?.value || '';
                const deliveryDate = row.querySelector('.delivery-date')?.value || '';
                const deliveryQuantity = row.querySelector('.delivery-quantity')?.value || '';
                const comment = row.querySelector('.comments')?.dataset.comment || '';

                itemsData.push({
                    sku,
                    name,
                    qty,
                    unitPrice,
                    amount,
                    deliveryDate, 
                    deliveryQuantity,
                    comment
                });
            });

            const formData = {
                po_number,
                supplier_id,
                supplier_name,
                branch,
                items: itemsData,
            };

            console.log("Form Data JSON:", formData);
        });
    });

</script>
    
  </body>
</html>