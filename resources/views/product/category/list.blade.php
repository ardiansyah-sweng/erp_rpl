<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ERP RPL UAD | Daftar Kategori</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
        crossorigin="anonymous" />
    <link rel="stylesheet" href={{ asset("assets/dist/css/adminlte.css") }} />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
        crossorigin="anonymous" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
        crossorigin="anonymous" />
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
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img
                                src={{ asset("assets/dist/assets/img/user2-160x160.jpg") }}
                                class="user-image rounded-circle shadow"
                                alt="User Image" />
                            <span class="d-none d-md-inline">Mimin Gantenk</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            </ul>
                    </li>
                    </ul>
                </div>
            </nav>
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    <img
                        src={{asset("assets/dist/assets/img/LogoRPL.png")}}
                        alt="RPL"
                        class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">ERP RPL UAD</span>
                    </a>
                </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
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
                        <li class="nav-item menu-open"> 
                            <a href="#" class="nav-link active">
                                <i class="nav-icon bi bi-list"></i>
                                <p>
                                    Kategori
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah Kategori</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('category.list') }}" class="nav-link active">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Daftar Kategori</p>
                                    </a>
                                </li>
                            </ul>
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
                                    <a href="/supplier/pic/add" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Tambah PIC supplier</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/supplier/pic/list" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Daftar PIC supplier</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-fill"></i>
                                <p>Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('branch.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-geo-alt-fill"></i> 
                                <p>Branch</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('item.list') }}" class="nav-link">
                                <i class="nav-icon bi bi-journal-text"></i> 
                                <p>Item</p>
                            </a>
                        </li>
                    </ul>
                    </nav>
            </div>
            </aside>
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6 d-flex align-items-center">
                            <h3 class="mb-0 me-2">Daftar Kategori</h3>
                            <a href="#" class="btn btn-primary btn-sm">Tambah Kategori</a>
                        </div>

                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Daftar Kategori</li>
                            </ol>
                        </div>
                    </div>
                    </div>
                </div>

            <div class="app-content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">List Kategori</h3>
                            <div class="d-flex ms-auto">
                                <a href="{{ route('category.print') }}" class="btn btn-info btn-sm me-2">
                                    <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                                </a>
                                <form action="{{ route('category.list') }}" method="GET" class="d-flex">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="keywords" class="form-control" placeholder="Search Kategori" value="{{ request('keywords') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Kategori</th>
                                        <th>Parent Kategori</th>
                                        <th>Status Aktif</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($category as $cat)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cat->category }}</td>
                                        <td>{{ $cat->parentCategory ? $cat->parentCategory->category : 'Tidak Ada' }}</td>
                                        <td>
                                            @if($cat->active)
                                                <span class="badge text-bg-success">Aktif</span>
                                            @else
                                                <span class="badge text-bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-info detail-btn" data-id="{{ $cat->id }}" data-bs-toggle="modal" data-bs-target="#detailCategoryModal">Detail</button>
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $cat->id }}" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Edit</button>
                                            <form action="{{ route('category.delete', $cat->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data kategori.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $category->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailCategoryModal" tabindex="-1" aria-labelledby="detailCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailCategoryModalLabel">Detail Kategori</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>ID Kategori:</strong> <span id="detail-id"></span></p>
                            <p><strong>Nama Kategori:</strong> <span id="detail-category"></span></p>
                            <p><strong>Parent Kategori:</strong> <span id="detail-parent-category"></span></p>
                            <p><strong>Status Aktif:</strong> <span id="detail-active"></span></p>
                            <p><strong>Dibuat Pada:</strong> <span id="detail-created-at"></span></p>
                            <p><strong>Diperbarui Pada:</strong> <span id="detail-updated-at"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editCategoryForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <input type="hidden" id="edit-id" name="id">
                                <div class="mb-3">
                                    <label for="edit-category" class="form-label">Nama Kategori</label>
                                    <input type="text" class="form-control" id="edit-category" name="category" required minlength="3">
                                    <div class="invalid-feedback" id="edit-category-error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-parent-id" class="form-label">Parent Kategori</label>
                                    <select class="form-control" id="edit-parent-id" name="parent_id">
                                        <option value="0">Tidak Ada Parent</option>
                                        
                                    </select>
                                    <div class="invalid-feedback" id="edit-parent-id-error"></div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="edit-active" name="active" value="1">
                                    <label class="form-check-label" for="edit-active">Aktif</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            </footer>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src={{ asset("assets/dist/js/adminlte.js") }}></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('[data-lte-toggle="sidebar"]').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse');
            });

            // Handle Detail Button Click
            $('.detail-btn').on('click', function() {
                const categoryId = $(this).data('id');
                $.ajax({
                    url: `/category/detail/${categoryId}`, // Adjust this route if needed
                    method: 'GET',
                    success: function(data) {
                        $('#detail-id').text(data.id);
                        $('#detail-category').text(data.category);
                        $('#detail-parent-category').text(data.parent_category ? data.parent_category.category : 'Tidak Ada');
                        $('#detail-active').text(data.active ? 'Aktif' : 'Tidak Aktif');
                        $('#detail-created-at').text(new Date(data.created_at).toLocaleString());
                        $('#detail-updated-at').text(new Date(data.updated_at).toLocaleString());
                    },
                    error: function(xhr) {
                        alert('Gagal mengambil detail kategori.');
                        console.error(xhr.responseText);
                    }
                });
            });

            
            $('.edit-btn').on('click', function() {
                const categoryId = $(this).data('id');
                $('#edit-id').val(categoryId);

            
                $('#edit-category').removeClass('is-invalid');
                $('#edit-category-error').text('');
                $('#edit-parent-id').removeClass('is-invalid');
                $('#edit-parent-id-error').text('');

                
                $.ajax({
                    url: `/category/detail/${categoryId}`,
                    method: 'GET',
                    success: function(data) {
                        $('#edit-category').val(data.category);
                        $('#edit-active').prop('checked', data.active);

                        
                        $.ajax({
                            url: "{{ route('category.list') }}", 
                            method: 'GET',
                            success: function(allCategories) {
                                let options = '<option value="0">Tidak Ada Parent</option>';
                                allCategories.forEach(cat => {
                                    if (cat.id !== data.id) { 
                                        options += `<option value="${cat.id}" ${cat.id == data.parent_id ? 'selected' : ''}>${cat.category}</option>`;
                                    }
                                });
                                $('#edit-parent-id').html(options);
                            },
                            error: function(xhr) {
                                console.error('Gagal mengambil daftar kategori untuk parent:', xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr) {
                        alert('Gagal mengambil data kategori untuk diedit.');
                        console.error(xhr.responseText);
                    }
                });
            });

            
            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();
                const categoryId = $('#edit-id').val();
                const formData = {
                    category: $('#edit-category').val(),
                    parent_id: $('#edit-parent-id').val(),
                    active: $('#edit-active').is(':checked') ? 1 : 0,
                    _token: $('input[name="_token"]').val(), 
                    _method: 'PUT'
                };

                
                $('#edit-category').removeClass('is-invalid');
                $('#edit-category-error').text('');
                $('#edit-parent-id').removeClass('is-invalid');
                $('#edit-parent-id-error').text('');

                $.ajax({
                    url: `/category/update/${categoryId}`,
                    method: 'POST', 
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        $('#editCategoryModal').modal('hide');
                        location.reload(); 
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.category) {
                                $('#edit-category').addClass('is-invalid');
                                $('#edit-category-error').text(errors.category[0]);
                            }
                            if (errors.parent_id) {
                                $('#edit-parent-id').addClass('is-invalid');
                                $('#edit-parent-id-error').text(errors.parent_id[0]);
                            }
                        } else {
                            alert('Terjadi kesalahan saat mengupdate kategori.');
                            console.error(xhr.responseText);
                        }
                    }
                });
            });
        });
    </script>
    </body>
</html>