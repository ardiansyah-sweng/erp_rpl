<div class="sidebar-wrapper">
        <nav class="mt-2">
          <!--begin::Sidebar Menu-->
          <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="false">
            <li class="nav-item">
              <a href="dashboard" class="nav-link active">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product.list') }}" class="nav-link">
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
                <li class="nav-item">
                  <a href="/supplier/material/add" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Tambah Supplier Item</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/supplier/add" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Tambah Supplier</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/supplier/material/list" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Supplier Material</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/supplier/pic/list" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>List PIC Supplier</p>
                  <a href="/supplier/list" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>List Supplier</p>
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
          <!--end::Sidebar Menu-->
        </nav>
      </div>
      <!--end::Sidebar Wrapper-->
    </aside>