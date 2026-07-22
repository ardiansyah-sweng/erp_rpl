@php
    $isSupplier = request()->routeIs('supplier.*');
    $isPurchaseOrder = request()->routeIs('purchase.orders*') || request()->routeIs('purchase_orders.*') || request()->routeIs('goods-returns.*') || request()->routeIs('po-payments.*');
    $isProduction = request()->routeIs('bom.*') || request()->routeIs('billofmaterial.*') || request()->routeIs('assort*');
    $isItem = request()->routeIs('item.*') || request()->routeIs('items.*');
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/dist/assets/img/LogoRPL.png') }}" alt="RPL" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">ERP RPL UAD</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('merks.index') }}" class="nav-link @if(request()->routeIs('merks.*') || request()->routeIs('merk.*')) active @endif">
                        <i class="nav-icon bi bi-tag-fill"></i>
                        <p>Merk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('product.list') }}" class="nav-link @if(request()->routeIs('product.*') || request()->routeIs('products.*')) active @endif">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Produk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('warehouses.index') }}" class="nav-link @if(request()->routeIs('warehouses.*') || request()->routeIs('warehouse.*')) active @endif">
                        <i class="nav-icon bi bi-box2"></i>
                        <p>Warehouse</p>
                    </a>
                </li>
                <li class="nav-item @if($isSupplier) menu-open @endif">
                    <a href="#" class="nav-link @if($isSupplier) active @endif">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Supplier</p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/supplier/pic/add" class="nav-link @if(url()->current() == url('/supplier/pic/add')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tambah PIC supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/supplier/material/add" class="nav-link @if(url()->current() == url('/supplier/material/add')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tambah Supplier Item</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/supplier/add" class="nav-link @if(url()->current() == url('/supplier/add')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tambah Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.material.list') }}" class="nav-link @if(request()->routeIs('supplier.material.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Supplier Material</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.pic.list') }}" class="nav-link @if(request()->routeIs('supplier.pic.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>List PIC Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.list') }}" class="nav-link @if(request()->routeIs('supplier.list') && !request()->routeIs('supplier.pic.*') && !request()->routeIs('supplier.material.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>List Supplier</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item @if($isPurchaseOrder) menu-open @endif">
                    <a href="#" class="nav-link @if($isPurchaseOrder) active @endif">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Purchase Orders
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('purchase.orders') }}" class="nav-link @if(request()->routeIs('purchase.orders*') || request()->routeIs('purchase_orders.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Daftar Purchase Orders</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('goods-returns.index') }}" class="nav-link @if(request()->routeIs('goods-returns.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Return Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('po-payments.index') }}" class="nav-link @if(request()->routeIs('po-payments.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Pembayaran PO</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('branches.index') }}" class="nav-link @if(request()->routeIs('branches.*') || request()->routeIs('branch.*')) active @endif">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Branch</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('item.list') }}" class="nav-link @if($isItem) active @endif">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Item</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link @if(request()->routeIs('categories.*') || request()->routeIs('category.*')) active @endif">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Category</p>
                    </a>
                </li>
                <li class="nav-item @if($isProduction) menu-open @endif">
                    <a href="#" class="nav-link @if($isProduction) active @endif">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Production</p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('assortment_production.list') }}" class="nav-link @if(request()->routeIs('assortment_production.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Production List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bom.list') }}" class="nav-link @if(request()->routeIs('bom.*') || request()->routeIs('billofmaterial.*')) active @endif">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Bill Of Material</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('activity-logs.index') }}" class="nav-link @if(request()->routeIs('activity-logs.*')) active @endif">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Log Aktivitas</p>
                    </a>
                </li>
                @if(auth()->user()?->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link @if(request()->routeIs('users.*')) active @endif">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Kelola User</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
