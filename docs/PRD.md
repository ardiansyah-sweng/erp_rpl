# Product Requirement Document (PRD)
## ERP-RPL
### Functional Requirements
#### 1. Branch
Branch adalah cabang-cabang yang dimiliki oleh perusahaan. Setiap cabang merupakan unit operasional yang memiliki lokasi fisik terpisah dan dapat melakukan aktivitas bisnis secara independen.

#### Data Requirements
- **Branch Name** (Required)
  - Nama identifikasi untuk cabang yang bersangkutan
  - Format: String, maksimal 50 karakter
  - Minimal 3 karakter
  - Harus unik dalam sistem
  
- **Address** (Required)
  - Alamat lengkap lokasi cabang berada
  - Format: String, maksimal 100 karakter
  - Minimal 3 karakter
  - Mencakup jalan, nomor, kota, dan kode pos
  
- **Telephone** (Required)
  - Nomor telepon utama cabang
  - Format: String, maksimal 30 karakter
  - Minimal 3 karakter
  - Dapat berupa nomor lokal atau internasional
  - Dapat menggunakan format dengan tanda hubung atau plus
  
- **Status** (Required)
  - Status operasional cabang saat ini
  - Format: Boolean (Active/Inactive)
  - Default: Active
  - Active = Cabang beroperasi normal
  - Inactive = Cabang tidak beroperasi/ditutup sementara

#### Business Rules
- Branch name harus unik di seluruh sistem
- Branch hanya bisa dihapus jika tidak muncul di tabel lain sebagai foreign key
- Branch yang berstatus inactive tidak dapat melakukan transaksi baru
- Minimal harus ada satu branch yang berstatus active dalam sistem
- Perubahan status branch memerlukan konfirmasi khusus
- Branch yang memiliki transaksi aktif tidak dapat diubah statusnya menjadi inactive

#### Functional Requirements
1. **Create Branch**
   - User dapat menambahkan cabang baru
   - Semua field required harus diisi
   - Validasi uniqueness untuk branch name
   - Validasi format dan panjang untuk setiap field
   
2. **Read Branch**
   - User dapat melihat daftar semua cabang
   - User dapat mencari cabang berdasarkan nama atau alamat
   - User dapat memfilter cabang berdasarkan status (active/inactive)
   - User dapat melihat detail lengkap dari satu cabang
   
3. **Update Branch**
   - User dapat mengubah informasi cabang yang sudah ada
   - Validasi yang sama seperti create branch
   - Branch name tetap harus unik setelah update
   - Perubahan status memerlukan konfirmasi
   
4. **Delete Branch**
   - User dapat menghapus cabang
   - Validasi bahwa branch tidak digunakan di tabel lain
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules
- **Branch Name**:
  - Required: "Nama cabang wajib diisi"
  - Minimum length (3): "Nama cabang minimal 3 karakter"
  - Maximum length (50): "Nama cabang maksimal 50 karakter"
  - Unique: "Nama cabang sudah ada, silakan gunakan nama lain"
  
- **Address**:
  - Required: "Alamat cabang wajib diisi"
  - Minimum length (3): "Alamat cabang minimal 3 karakter"
  - Maximum length (100): "Alamat cabang maksimal 100 karakter"
  
- **Telephone**:
  - Required: "Telepon cabang wajib diisi"
  - Minimum length (3): "Telepon cabang minimal 3 karakter"
  - Maximum length (30): "Telepon cabang maksimal 30 karakter"

#### User Interface Requirements
- Form input untuk create dan update branch
- Table listing dengan pagination untuk menampilkan daftar branch
- Search functionality dengan real-time filtering
- Status indicator yang jelas (active/inactive)
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements
- Branch data akan digunakan sebagai reference di modul lain (Purchase Order, Assortment Production)
- API endpoints untuk CRUD operations
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE BRANCH TESTS** |
| TC-BR-01 | Create branch with valid data | name: "Cabang Jakarta", address: "Jl. Sudirman No.1", telephone: "021-12345678" | Branch created successfully | Positive | <span style="color: green;">Passed</span> |
| TC-BR-02 | Create branch with minimum length name | name: "ABC" (3 chars) | Branch created successfully | Boundary | Passed |
| TC-BR-03 | Create branch with maximum length name | name: 50 characters string | Branch created successfully | Boundary | Passed |
| TC-BR-04 | Create branch with name too short | name: "AB" (2 chars) | Error: "Nama cabang minimal 3 karakter" | Boundary | Passed |
| TC-BR-05 | Create branch with name too long | name: 51 characters string | Error: "Nama cabang maksimal 50 karakter" | Boundary | Passed |
| TC-BR-06 | Create branch with duplicate name | name: existing branch name | Error: "Nama cabang sudah ada, silakan gunakan nama lain" | Negative | Passed |
| TC-BR-07 | Create branch with empty name | name: "" | Error: "Nama cabang wajib diisi" | Negative |
| TC-BR-08 | Create branch with minimum length address | address: "JL." (3 chars) | Branch created successfully | Boundary |
| TC-BR-09 | Create branch with maximum length address | address: 100 characters string | Branch created successfully | Boundary |
| TC-BR-10 | Create branch with address too short | address: "JL" (2 chars) | Error: "Alamat cabang minimal 3 karakter" | Boundary | Passed |
| TC-BR-11 | Create branch with address too long | address: 101 characters string | Error: "Alamat cabang maksimal 100 karakter" | Boundary | Passed |
| TC-BR-12 | Create branch with empty address | address: "" | Error: "Alamat cabang wajib diisi" | Negative |
| TC-BR-13 | Create branch with minimum length telephone | telephone: "123" (3 chars) | Branch created successfully | Boundary |
| TC-BR-14 | Create branch with maximum length telephone | telephone: 30 characters string | Branch created successfully | Boundary |
| TC-BR-15 | Create branch with telephone too short | telephone: "12" (2 chars) | Error: "Telepon cabang minimal 3 karakter" | Boundary | Passed |
| TC-BR-16 | Create branch with telephone too long | telephone: 31 characters string | Error: "Telepon cabang maksimal 30 karakter" | Boundary | Passed |
| TC-BR-17 | Create branch with empty telephone | telephone: "" | Error: "Telepon cabang wajib diisi" | Negative |
| **READ BRANCH TESTS** |
| TC-BR-18 | View all branches | N/A | Display all branches with pagination | Positive | None |
| TC-BR-19 | Search branch by name | search: "Jakarta" | Display branches containing "Jakarta" | Positive | None |
| TC-BR-20 | Search branch by address | search: "Sudirman" | Display branches with address containing "Sudirman" | Positive | None |
| TC-BR-21 | Filter branch by active status | filter: "active" | Display only active branches | Positive | None |
| TC-BR-22 | Filter branch by inactive status | filter: "inactive" | Display only inactive branches | Positive | None |
| TC-BR-23 | View branch detail | branch_id: valid ID | Display complete branch information | Positive | None |
| TC-BR-24 | View non-existing branch | branch_id: invalid ID | Error: "Branch not found" | Negative |
| **UPDATE BRANCH TESTS** |
| TC-BR-25 | Update branch with valid data | Valid branch data changes | Branch updated successfully | Positive | None |
| TC-BR-26 | Update branch name to duplicate | name: existing branch name | Error: "Nama cabang sudah ada, silakan gunakan nama lain" | Negative |
| TC-BR-27 | Update branch status to inactive | status: inactive | Status updated with confirmation | Positive | None |
| TC-BR-28 | Update branch with invalid data | Invalid field values | Appropriate validation errors | Negative |
| **DELETE BRANCH TESTS** |
| TC-BR-29 | Delete unused branch | branch_id: unused branch | Branch deleted successfully | Positive | None |
| TC-BR-30 | Delete branch with references | branch_id: branch with FK references | Error: "Branch tidak dapat dihapus karena masih digunakan" | Negative |
| TC-BR-31 | Delete non-existing branch | branch_id: invalid ID | Error: "Branch not found" | Negative |
| TC-BR-32 | Delete with confirmation dialog | Confirm deletion | Branch deleted after confirmation | Positive | None |
| TC-BR-33 | Cancel deletion | Cancel deletion dialog | Branch not deleted | Positive | None |

#### 2. Warehouse
Warehouse adalah gudang-gudang yang dimiliki oleh perusahaan. Gudang berfungsi untuk menyimpan bahan baku, bahan setengah jadi hingga barang jadi. Terkadang, gudang dan cabang berada di tempat yang sama namun memiliki pengelolaan yang terpisah.

#### Data Requirements
- **Warehouse Name** (Required)
  - Nama identifikasi untuk gudang yang bersangkutan
  - Format: String, maksimal 50 karakter
  - Minimal 3 karakter
  - Harus unik dalam sistem
  
- **Address** (Required)
  - Alamat lengkap lokasi gudang berada
  - Format: String, maksimal 100 karakter
  - Minimal 3 karakter
  - Mencakup jalan, nomor, kota, dan kode pos
  
- **Telephone** (Required)
  - Nomor telepon utama gudang
  - Format: String, maksimal 30 karakter
  - Minimal 3 karakter
  - Dapat berupa nomor lokal atau internasional
  - Dapat menggunakan format dengan tanda hubung atau plus
  - Bisa memiliki lebih dari satu nomor (dipisah koma)
  
- **Status** (Required)
  - Status operasional gudang saat ini
  - Format: Boolean (Active/Inactive)
  - Default: Active
  - Active = Gudang beroperasi normal
  - Inactive = Gudang tidak beroperasi/ditutup sementara

- **Gudang Raw Material (RM)** (Required)
  - Status gudang sebagai penyimpan Raw Material atau tidak
  - Format: Boolean (Yes/No)
  - Default: False
  - Yes = Gudang sebagai penyimpan Raw Material
  - No = Gudang bukan sebagai penyimpan Raw Material

- **Gudang Finished Goods (FG)** (Required)
  - Status gudang sebagai penyimpan barang jadi (finished goods) atau tidak
  - Format: Boolean (Yes/No)
  - Default: False
  - Yes = Gudang sebagai penyimpan barang jadi (finished goods)
  - No = Gudang bukan sebagai penyimpan barang jadi (finished goods)

#### Business Rules
- Warehouse name harus unik di seluruh sistem
- Warehouse hanya bisa dihapus jika tidak muncul di tabel lain sebagai foreign key
- Warehouse yang berstatus inactive tidak dapat menerima stock masuk atau keluar
- Minimal harus ada satu warehouse yang berstatus active dalam sistem
- Perubahan status warehouse memerlukan konfirmasi khusus
- Warehouse yang memiliki stock aktif tidak dapat diubah statusnya menjadi inactive
- Transfer stock antar warehouse hanya dapat dilakukan jika kedua warehouse berstatus active

#### Functional Requirements
1. **Create Warehouse**
   - User dapat menambahkan gudang baru
   - Semua field required harus diisi
   - Validasi uniqueness untuk warehouse name
   - Validasi format dan panjang untuk setiap field
   
2. **Read Warehouse**
   - User dapat melihat daftar semua gudang
   - User dapat mencari gudang berdasarkan nama atau alamat
   - User dapat memfilter gudang berdasarkan status (active/inactive)
   - User dapat melihat detail lengkap dari satu gudang
   - User dapat melihat stock summary per gudang
   
3. **Update Warehouse**
   - User dapat mengubah informasi gudang yang sudah ada
   - Validasi yang sama seperti create warehouse
   - Warehouse name tetap harus unik setelah update
   - Perubahan status memerlukan konfirmasi
   
4. **Delete Warehouse**
   - User dapat menghapus gudang
   - Validasi bahwa warehouse tidak digunakan di tabel lain
   - Validasi bahwa warehouse tidak memiliki stock aktif
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules
- **Warehouse Name**:
  - Required: "Nama gudang wajib diisi"
  - Minimum length (3): "Nama gudang minimal 3 karakter"
  - Maximum length (50): "Nama gudang maksimal 50 karakter"
  - Unique: "Nama gudang sudah ada, silakan gunakan nama lain"
  
- **Address**:
  - Required: "Alamat gudang wajib diisi"
  - Minimum length (3): "Alamat gudang minimal 3 karakter"
  - Maximum length (100): "Alamat gudang maksimal 100 karakter"
  
- **Telephone**:
  - Required: "Telepon gudang wajib diisi"
  - Minimum length (3): "Telepon gudang minimal 3 karakter"
  - Maximum length (30): "Telepon gudang maksimal 30 karakter"

#### User Interface Requirements
- Form input untuk create dan update warehouse
- Table listing dengan pagination untuk menampilkan daftar warehouse
- Search functionality dengan real-time filtering
- Status indicator yang jelas (active/inactive)
- Stock summary display untuk setiap warehouse
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements
- Warehouse data akan digunakan sebagai reference di modul Stock Management, Transfer, Production
- API endpoints untuk CRUD operations
- Stock movement tracking per warehouse
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE WAREHOUSE TESTS** |
| TC-WH-01 | Create warehouse with valid data | name: "Gudang Jakarta", address: "Jl. Sudirman No.1", telephone: "021-12345678" | Warehouse created successfully | Positive | None |
| TC-WH-02 | Create warehouse with minimum length name | name: "ABC" (3 chars) | Warehouse created successfully | Boundary | None |
| TC-WH-03 | Create warehouse with maximum length name | name: 50 characters string | Warehouse created successfully | Boundary | None |
| TC-WH-04 | Create warehouse with name too short | name: "AB" (2 chars) | Error: "Nama gudang minimal 3 karakter" | Boundary | None |
| TC-WH-05 | Create warehouse with name too long | name: 51 characters string | Error: "Nama gudang maksimal 50 karakter" | Boundary | None |
| TC-WH-06 | Create warehouse with duplicate name | name: existing warehouse name | Error: "Nama gudang sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-WH-07 | Create warehouse with empty name | name: "" | Error: "Nama gudang wajib diisi" | Negative | None |
| TC-WH-08 | Create warehouse with minimum length address | address: "JL." (3 chars) | Warehouse created successfully | Boundary | None |
| TC-WH-09 | Create warehouse with maximum length address | address: 100 characters string | Warehouse created successfully | Boundary | None |
| TC-WH-10 | Create warehouse with address too short | address: "JL" (2 chars) | Error: "Alamat gudang minimal 3 karakter" | Boundary | None |
| TC-WH-11 | Create warehouse with address too long | address: 101 characters string | Error: "Alamat gudang maksimal 100 karakter" | Boundary | None |
| TC-WH-12 | Create warehouse with empty address | address: "" | Error: "Alamat gudang wajib diisi" | Negative | None |
| TC-WH-13 | Create warehouse with minimum length telephone | telephone: "123" (3 chars) | Warehouse created successfully | Boundary | None |
| TC-WH-14 | Create warehouse with maximum length telephone | telephone: 30 characters string | Warehouse created successfully | Boundary | None |
| TC-WH-15 | Create warehouse with telephone too short | telephone: "12" (2 chars) | Error: "Telepon gudang minimal 3 karakter" | Boundary | None |
| TC-WH-16 | Create warehouse with telephone too long | telephone: 31 characters string | Error: "Telepon gudang maksimal 30 karakter" | Boundary | None |
| TC-WH-17 | Create warehouse with empty telephone | telephone: "" | Error: "Telepon gudang wajib diisi" | Negative | None |
| **READ WAREHOUSE TESTS** |
| TC-WH-18 | View all warehouses | N/A | Display all warehouses with pagination | Positive | None |
| TC-WH-19 | Search warehouse by name | search: "Jakarta" | Display warehouses containing "Jakarta" | Positive | None |
| TC-WH-20 | Search warehouse by address | search: "Sudirman" | Display warehouses with address containing "Sudirman" | Positive | None |
| TC-WH-21 | Filter warehouse by active status | filter: "active" | Display only active warehouses | Positive | None |
| TC-WH-22 | Filter warehouse by inactive status | filter: "inactive" | Display only inactive warehouses | Positive | None |
| TC-WH-23 | View warehouse detail | warehouse_id: valid ID | Display complete warehouse information | Positive | None |
| TC-WH-24 | View non-existing warehouse | warehouse_id: invalid ID | Error: "Warehouse not found" | Negative | None |
| **UPDATE WAREHOUSE TESTS** |
| TC-WH-25 | Update warehouse with valid data | Valid warehouse data changes | Warehouse updated successfully | Positive | None |
| TC-WH-26 | Update warehouse name to duplicate | name: existing warehouse name | Error: "Nama gudang sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-WH-27 | Update warehouse status to inactive | status: inactive | Status updated with confirmation | Positive | None |
| TC-WH-28 | Update warehouse with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE WAREHOUSE TESTS** |
| TC-WH-29 | Delete unused warehouse | warehouse_id: unused warehouse | Warehouse deleted successfully | Positive | None |
| TC-WH-30 | Delete warehouse with references | warehouse_id: warehouse with FK references | Error: "Warehouse tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-WH-31 | Delete non-existing warehouse | warehouse_id: invalid ID | Error: "Warehouse not found" | Negative | None |
| TC-WH-32 | Delete with confirmation dialog | Confirm deletion | Warehouse deleted after confirmation | Positive | None |
| TC-WH-33 | Cancel deletion | Cancel deletion dialog | Warehouse not deleted | Positive | None |

#### 3. Merk
Merk adalah nama brand merk yang melekat pada suatu produk. Merk digunakan untuk mengidentifikasi produsen atau brand dari produk yang dijual dalam sistem ERP.

#### Data Requirements
- **Merk Name** (Required)
  - Nama brand atau merk produk
  - Format: String, maksimal 50 karakter
  - Minimal 2 karakter
  - Harus unik dalam sistem
  - Dapat berupa nama brand lokal maupun internasional
  
- **Status** (Required)
  - Status keaktifan merk dalam sistem
  - Format: Boolean (Active/Inactive)
  - Default: Active
  - Active = Merk dapat digunakan untuk produk baru
  - Inactive = Merk tidak dapat digunakan untuk produk baru

#### Business Rules
- Merk name harus unik di seluruh sistem
- Merk hanya bisa dihapus jika tidak muncul di tabel lain sebagai foreign key
- Merk yang berstatus inactive tidak dapat digunakan untuk produk baru
- Merk yang sudah digunakan pada produk tidak dapat dihapus
- Perubahan status merk dari active ke inactive memerlukan konfirmasi
- Merk yang memiliki produk aktif tidak dapat diubah statusnya menjadi inactive

#### Functional Requirements
1. **Create Merk**
   - User dapat menambahkan merk baru
   - Field merk name wajib diisi
   - Validasi uniqueness untuk merk name
   - Validasi format dan panjang nama merk
   - Status default adalah active
   
2. **Read Merk**
   - User dapat melihat daftar semua merk
   - User dapat mencari merk berdasarkan nama
   - User dapat memfilter merk berdasarkan status (active/inactive)
   - User dapat melihat detail lengkap dari satu merk
   - User dapat melihat jumlah produk yang menggunakan merk tersebut
   
3. **Update Merk**
   - User dapat mengubah informasi merk yang sudah ada
   - Validasi yang sama seperti create merk
   - Merk name tetap harus unik setelah update
   - Perubahan status memerlukan konfirmasi
   
4. **Delete Merk**
   - User dapat menghapus merk
   - Validasi bahwa merk tidak digunakan di tabel lain
   - Validasi bahwa merk tidak memiliki produk aktif
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules
- **Merk Name**:
  - Required: "Nama merk wajib diisi"
  - Minimum length (2): "Nama merk minimal 2 karakter"
  - Maximum length (50): "Nama merk maksimal 50 karakter"
  - Unique: "Nama merk sudah ada, silakan gunakan nama lain"
  - Format: "Nama merk hanya boleh berisi huruf, angka, dan spasi"

#### User Interface Requirements
- Form input untuk create dan update merk
- Table listing dengan pagination untuk menampilkan daftar merk
- Search functionality dengan real-time filtering
- Status indicator yang jelas (active/inactive)
- Product count display untuk setiap merk
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop
- Bulk operations untuk mengubah status multiple merk

#### Integration Requirements
- Merk data akan digunakan sebagai reference di modul Product Management
- API endpoints untuk CRUD operations
- Product association tracking per merk
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE MERK TESTS** |
| TC-MK-01 | Create merk with valid data | name: "BrandX" | Merk created successfully | Positive | None |
| TC-MK-02 | Create merk with minimum length name | name: "AB" (2 chars) | Merk created successfully | Boundary | None |
| TC-MK-03 | Create merk with maximum length name | name: 50 characters string | Merk created successfully | Boundary | None |
| TC-MK-04 | Create merk with name too short | name: "A" (1 char) | Error: "Nama merk minimal 2 karakter" | Boundary | None |
| TC-MK-05 | Create merk with name too long | name: 51 characters string | Error: "Nama merk maksimal 50 karakter" | Boundary | None |
| TC-MK-06 | Create merk with duplicate name | name: existing merk name | Error: "Nama merk sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-MK-07 | Create merk with empty name | name: "" | Error: "Nama merk wajib diisi" | Negative | None |
| TC-MK-08 | Create merk with invalid format | name: "Brand@123" | Error: "Nama merk hanya boleh berisi huruf, angka, dan spasi" | Negative | None |
| **READ MERK TESTS** |
| TC-MK-09 | View all merk | N/A | Display all merk with pagination | Positive | None |
| TC-MK-10 | Search merk by name | search: "BrandX" | Display merk containing "BrandX" | Positive | None |
| TC-MK-11 | Filter merk by active status | filter: "active" | Display only active merk | Positive | None |
| TC-MK-12 | Filter merk by inactive status | filter: "inactive" | Display only inactive merk | Positive | None |
| TC-MK-13 | View merk detail | merk_id: valid ID | Display complete merk information | Positive | None |
| TC-MK-14 | View non-existing merk | merk_id: invalid ID | Error: "Merk not found" | Negative | None |
| **UPDATE MERK TESTS** |
| TC-MK-15 | Update merk with valid data | Valid merk data changes | Merk updated successfully | Positive | None |
| TC-MK-16 | Update merk name to duplicate | name: existing merk name | Error: "Nama merk sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-MK-17 | Update merk status to inactive | status: inactive | Status updated with confirmation | Positive | None |
| TC-MK-18 | Update merk with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE MERK TESTS** |
| TC-MK-19 | Delete unused merk | merk_id: unused merk | Merk deleted successfully | Positive | None |
| TC-MK-20 | Delete merk with references | merk_id: merk with FK references | Error: "Merk tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-MK-21 | Delete non-existing merk | merk_id: invalid ID | Error: "Merk not found" | Negative | None |
| TC-MK-22 | Delete with confirmation dialog | Confirm deletion | Merk deleted after confirmation | Positive | None |
| TC-MK-23 | Cancel deletion | Cancel deletion dialog | Merk not deleted | Positive | None |
#### 4. Category
Category digunakan untuk mengklasifikasikan atau pengelompokan produk. Category dibuat bersarang. Jadi ada category induk yang tidak memiliki sub-category dan ada sub-category yang merupakan anak dari category induk.

#### Data
- **Category**. Nama kategori produk
- **Parent**. ID kategori induk (default NULL)
- **Status**. Status category saat ini apakah aktif atau tidak aktif

#### Rule
- Category induk hanya bisa dihapus selama sub-category tidak muncul di tabel lain
- Sub-category hanya bisa dihapus selama tidak muncul di tabel lain
- Category yang berstatus inactive tidak dapat digunakan pada produk baru
- Minimal harus ada satu category yang berstatus active dalam sistem
- Perubahan status category memerlukan konfirmasi khusus
- Category yang memiliki produk aktif tidak dapat diubah statusnya menjadi inactive

#### Functional Requirements
1. **Create Category**
   - User dapat menambahkan category baru
   - Field category name wajib diisi
   - Validasi uniqueness untuk category name
   - Validasi format dan panjang untuk setiap field
   - Status default adalah active
   - Jika ada parent category, harus valid dan ada dalam sistem
   
2. **Read Category**
   - User dapat melihat daftar semua category
   - User dapat mencari category berdasarkan nama
   - User dapat memfilter category berdasarkan status (active/inactive)
   - User dapat melihat detail lengkap dari satu category
   - User dapat melihat jumlah produk yang tergolong dalam category tersebut
   
3. **Update Category**
   - User dapat mengubah informasi category yang sudah ada
   - Validasi yang sama seperti create category
   - Category name tetap harus unik setelah update
   - Perubahan status memerlukan konfirmasi
   
4. **Delete Category**
   - User dapat menghapus category
   - Validasi bahwa category tidak digunakan di tabel lain
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules
- **Category Name**:
  - Required: "Nama kategori wajib diisi"
  - Minimum length (3): "Nama kategori minimal 3 karakter"
  - Maximum length (50): "Nama kategori maksimal 50 karakter"
  - Unique: "Nama kategori sudah ada, silakan gunakan nama lain"
  - Format: "Nama kategori hanya boleh berisi huruf, angka, dan spasi"
- **Parent**:
  - Optional, jika diisi harus valid ID kategori induk
- **Status**:
  - Required: "Status kategori wajib diisi"
  - Value: Active/Inactive

#### User Interface Requirements
- Form input untuk create dan update category
- Dropdown/select untuk memilih parent category
- Table listing dengan pagination untuk menampilkan daftar category dan sub-category
- Search dan filter category berdasarkan nama dan status
- Status indicator yang jelas (active/inactive)
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements
- Category data digunakan sebagai reference di modul Product Management
- API endpoints untuk CRUD operations
- Hierarchical category support (parent-child)
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE CATEGORY TESTS** |
| TC-CT-01 | Create category with valid data | name: "Elektronik", status: active | Category created successfully | Positive | None |
| TC-CT-02 | Create category with minimum length name | name: "ABC" (3 chars) | Category created successfully | Boundary | None |
| TC-CT-03 | Create category with maximum length name | name: 50 characters string | Category created successfully | Boundary | None |
| TC-CT-04 | Create category with name too short | name: "AB" (2 chars) | Error: "Nama kategori minimal 3 karakter" | Boundary | None |
| TC-CT-05 | Create category with name too long | name: 51 characters string | Error: "Nama kategori maksimal 50 karakter" | Boundary | None |
| TC-CT-06 | Create category with duplicate name | name: existing category name | Error: "Nama kategori sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-CT-07 | Create category with empty name | name: "" | Error: "Nama kategori wajib diisi" | Negative | None |
| TC-CT-08 | Create category with invalid format | name: "Kategori@123" | Error: "Nama kategori hanya boleh berisi huruf, angka, dan spasi" | Negative | None |
| TC-CT-09 | Create category with parent | name: "Smartphone", parent: valid category ID | Category created successfully as sub-category | Positive | None |
| TC-CT-10 | Create category with invalid parent | name: "Smartphone", parent: invalid ID | Error: "Parent category tidak valid" | Negative | None |
| **READ CATEGORY TESTS** |
| TC-CT-11 | View all categories | N/A | Display all categories with pagination | Positive | None |
| TC-CT-12 | Search category by name | search: "Elektronik" | Display categories containing "Elektronik" | Positive | None |
| TC-CT-13 | Filter category by active status | filter: "active" | Display only active categories | Positive | None |
| TC-CT-14 | Filter category by inactive status | filter: "inactive" | Display only inactive categories | Positive | None |
| TC-CT-15 | View category detail | category_id: valid ID | Display complete category information | Positive | None |
| TC-CT-16 | View non-existing category | category_id: invalid ID | Error: "Category not found" | Negative | None |
| **UPDATE CATEGORY TESTS** |
| TC-CT-17 | Update category with valid data | Valid category data changes | Category updated successfully | Positive | None |
| TC-CT-18 | Update category name to duplicate | name: existing category name | Error: "Nama kategori sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-CT-19 | Update category status to inactive | status: inactive | Status updated with confirmation | Positive | None |
| TC-CT-20 | Update category with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE CATEGORY TESTS** |
| TC-CT-21 | Delete unused category | category_id: unused category | Category deleted successfully | Positive | None |
| TC-CT-22 | Delete category with references | category_id: category with FK references | Error: "Category tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-CT-23 | Delete non-existing category | category_id: invalid ID | Error: "Category not found" | Negative | None |
| TC-CT-24 | Delete with confirmation dialog | Confirm deletion | Category deleted after confirmation | Positive | None |
| TC-CT-25 | Cancel deletion | Cancel deletion dialog | Category not deleted | Positive | None |
#### 5. Supplier
Supplier adalah pihak atau perusahaan yang menyediakan barang atau jasa untuk perusahaan. Supplier dapat berupa individu, perusahaan lokal, maupun internasional.

#### Data Requirements
- **Supplier ID** (Required)
  - Identitas Supplier
  - Format: String, maksimal 6 karakter
  - Minimal 3 karakter
  - Harus unik dalam sistem
- **Supplier Name** (Required)
  - Nama identifikasi supplier
  - Format: String, maksimal 50 karakter
  - Minimal 3 karakter
  - Harus unik dalam sistem
- **Address** (Required)
  - Alamat lengkap supplier
  - Format: String, maksimal 100 karakter
  - Minimal 3 karakter
- **Telephone** (Required)
  - Nomor telepon utama supplier
  - Format: String, maksimal 30 karakter
  - Minimal 3 karakter
  - Dapat berupa nomor lokal/internasional
- **Email** (Optional)
  - Email supplier
  - Format: Email valid
- **Status** (Required)
  - Status aktif/nonaktif supplier
  - Format: Boolean (Active/Inactive)
  - Default: Active

#### Business Rules
- Supplier name harus unik di seluruh sistem
- Supplier hanya bisa dihapus jika tidak muncul di tabel lain sebagai foreign key
- Supplier hanya bisa dihapus jika tidak memiliki person in charge (PIC)
- Supplier yang berstatus inactive tidak dapat digunakan untuk transaksi baru
- Minimal harus ada satu supplier yang berstatus active dalam sistem
- Perubahan status supplier memerlukan konfirmasi khusus
- Supplier yang memiliki transaksi aktif tidak dapat diubah statusnya menjadi inactive

#### Validation Rules
- **Supplier Name**:
  - Required: "Nama supplier wajib diisi"
  - Minimum length (3): "Nama supplier minimal 3 karakter"
  - Maximum length (50): "Nama supplier maksimal 50 karakter"
  - Unique: "Nama supplier sudah ada, silakan gunakan nama lain"
- **Address**:
  - Required: "Alamat supplier wajib diisi"
  - Minimum length (3): "Alamat supplier minimal 3 karakter"
  - Maximum length (100): "Alamat supplier maksimal 100 karakter"
- **Telephone**:
  - Required: "Telepon supplier wajib diisi"
  - Minimum length (3): "Telepon supplier minimal 3 karakter"
  - Maximum length (30): "Telepon supplier maksimal 30 karakter"
- **Email**:
  - Optional, jika diisi harus format email valid
- **Status**:
  - Required: "Status supplier wajib diisi"
  - Value: Active/Inactive

#### User Interface Requirements
- Form input untuk create dan update supplier
- Table listing dengan pagination untuk menampilkan daftar supplier
- Search dan filter supplier berdasarkan nama, status, dan email
- Status indicator yang jelas (active/inactive)
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements
- Supplier data digunakan sebagai reference di modul Purchase Order, Inventory, Payment
- API endpoints untuk CRUD operations
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE SUPPLIER TESTS** |
| TC-SP-01 | Create supplier with valid data | name: "PT Sumber Makmur", address: "Jl. Merdeka No.1", telephone: "021-12345678", email: "info@sumbermakmur.com" | Supplier created successfully | Positive | None |
| TC-SP-02 | Create supplier with minimum length name | name: "ABC" (3 chars) | Supplier created successfully | Boundary | None |
| TC-SP-03 | Create supplier with maximum length name | name: 50 characters string | Supplier created successfully | Boundary | None |
| TC-SP-04 | Create supplier with name too short | name: "AB" (2 chars) | Error: "Nama supplier minimal 3 karakter" | Boundary | None |
| TC-SP-05 | Create supplier with name too long | name: 51 characters string | Error: "Nama supplier maksimal 50 karakter" | Boundary | None |
| TC-SP-06 | Create supplier with duplicate name | name: existing supplier name | Error: "Nama supplier sudah ada, silakan gunakan nama lain" | Negative | None |
| TC-SP-07 | Create supplier with empty name | name: "" | Error: "Nama supplier wajib diisi" | Negative | None |
| TC-SP-08 | Create supplier with minimum length address | address: "JL." (3 chars) | Supplier created successfully | Boundary | None |
| TC-SP-09 | Create supplier with maximum length address | address: 100 characters string | Supplier created successfully | Boundary | None |
| TC-SP-10 | Create supplier with address too short | address: "JL" (2 chars) | Error: "Alamat supplier minimal 3 karakter" | Boundary | None |
| TC-SP-11 | Create supplier with address too long | address: 101 characters string | Error: "Alamat supplier maksimal 100 karakter" | Boundary | None |
| TC-SP-12 | Create supplier with empty address | address: "" | Error: "Alamat supplier wajib diisi" | Negative | None |
| TC-SP-13 | Create supplier with minimum length telephone | telephone: "123" (3 chars) | Supplier created successfully | Boundary | None |
| TC-SP-14 | Create supplier with maximum length telephone | telephone: 30 characters string | Supplier created successfully | Boundary | None |
| TC-SP-15 | Create supplier with telephone too short | telephone: "12" (2 chars) | Error: "Telepon supplier minimal 3 karakter" | Boundary | None |
| TC-SP-16 | Create supplier with telephone too long | telephone: 31 characters string | Error: "Telepon supplier maksimal 30 karakter" | Boundary | None |
| TC-SP-17 | Create supplier with empty telephone | telephone: "" | Error: "Telepon supplier wajib diisi" | Negative | None |
| TC-SP-18 | Create supplier with invalid email | email: "invalid-email" | Error: "Format email tidak valid" | Negative | None |
#### 6. Supplier PIC
Supplier PIC (Person In Charge) adalah individu yang bertanggung jawab sebagai kontak utama dari supplier. Satu supplier dapat memiliki lebih dari satu PIC.

#### Data Requirements
- **Supplier** (Required)
  - Relasi ke supplier utama
  - Format: ID supplier valid
- **PIC Name** (Required)
  - Nama PIC
  - Format: String, maksimal 50 karakter
  - Minimal 3 karakter
- **Telephone** (Required)
  - Nomor telepon PIC
  - Format: String, maksimal 30 karakter
  - Minimal 3 karakter
- **Email** (Optional)
  - Email PIC
  - Format: Email valid
- **Position/Role** (Optional)
  - Jabatan atau peran PIC di supplier
  - Format: String, maksimal 30 karakter
- **Status** (Required)
  - Status aktif/nonaktif PIC
  - Format: Boolean (Active/Inactive)
  - Default: Active

#### Business Rules
- Satu supplier dapat memiliki banyak PIC
- PIC name harus unik dalam satu supplier
- PIC hanya bisa dihapus jika tidak muncul di tabel lain sebagai foreign key
- PIC yang berstatus inactive tidak dapat digunakan untuk transaksi baru
- Perubahan status PIC memerlukan konfirmasi khusus

#### Validation Rules
- **Supplier**:
  - Required: "Supplier wajib dipilih"
  - Value: ID supplier valid
- **PIC Name**:
  - Required: "Nama PIC wajib diisi"
  - Minimum length (3): "Nama PIC minimal 3 karakter"
  - Maximum length (50): "Nama PIC maksimal 50 karakter"
  - Unique per supplier: "Nama PIC sudah ada untuk supplier ini, silakan gunakan nama lain"
- **Telephone**:
  - Required: "Telepon PIC wajib diisi"
  - Minimum length (3): "Telepon PIC minimal 3 karakter"
  - Maximum length (30): "Telepon PIC maksimal 30 karakter"
- **Email**:
  - Optional, jika diisi harus format email valid
- **Position/Role**:
  - Optional, maksimal 30 karakter
- **Status**:
  - Required: "Status PIC wajib diisi"
  - Value: Active/Inactive

#### User Interface Requirements
- Form input untuk create dan update PIC supplier
- Dropdown/select untuk memilih supplier
- Table listing dengan pagination untuk menampilkan daftar PIC per supplier
- Search dan filter PIC berdasarkan nama, supplier, status, dan email
- Status indicator yang jelas (active/inactive)
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements
- Supplier PIC data digunakan sebagai reference di modul Purchase Order, Inventory, Payment
- API endpoints untuk CRUD operations
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE SUPPLIER PIC TESTS** |
| TC-SPIC-01 | Create PIC with valid data | supplier: valid ID, name: "Budi", telephone: "08123456789", email: "budi@supplier.com", position: "Manager" | PIC created successfully | Positive | None |
| TC-SPIC-02 | Create PIC with minimum length name | name: "ABC" (3 chars) | PIC created successfully | Boundary | None |
| TC-SPIC-03 | Create PIC with maximum length name | name: 50 characters string | PIC created successfully | Boundary | None |
| TC-SPIC-04 | Create PIC with name too short | name: "AB" (2 chars) | Error: "Nama PIC minimal 3 karakter" | Boundary | None |
| TC-SPIC-05 | Create PIC with name too long | name: 51 characters string | Error: "Nama PIC maksimal 50 karakter" | Boundary | None |
| TC-SPIC-06 | Create PIC with duplicate name in same supplier | name: existing PIC name, supplier: same supplier | Error: "Nama PIC sudah ada untuk supplier ini, silakan gunakan nama lain" | Negative | None |
| TC-SPIC-07 | Create PIC with empty name | name: "" | Error: "Nama PIC wajib diisi" | Negative | None |
| TC-SPIC-08 | Create PIC with minimum length telephone | telephone: "123" (3 chars) | PIC created successfully | Boundary | None |
| TC-SPIC-09 | Create PIC with maximum length telephone | telephone: 30 characters string | PIC created successfully | Boundary | None |
| TC-SPIC-10 | Create PIC with telephone too short | telephone: "12" (2 chars) | Error: "Telepon PIC minimal 3 karakter" | Boundary | None |
| TC-SPIC-11 | Create PIC with telephone too long | telephone: 31 characters string | Error: "Telepon PIC maksimal 30 karakter" | Boundary | None |
| TC-SPIC-12 | Create PIC with empty telephone | telephone: "" | Error: "Telepon PIC wajib diisi" | Negative | None |
| TC-SPIC-13 | Create PIC with invalid email | email: "invalid-email" | Error: "Format email tidak valid" | Negative | None |
| TC-SPIC-14 | Create PIC with position too long | position: 31 characters string | Error: "Jabatan maksimal 30 karakter" | Boundary | None |
| **READ SUPPLIER PIC TESTS** |
| TC-SPIC-15 | View all PICs for supplier | supplier: valid ID | Display all PICs for supplier with pagination | Positive | None |
| TC-SPIC-16 | Search PIC by name | search: "Budi" | Display PICs containing "Budi" | Positive | None |
| TC-SPIC-17 | Filter PIC by active status | filter: "active" | Display only active PICs | Positive | None |
| TC-SPIC-18 | Filter PIC by inactive status | filter: "inactive" | Display only inactive PICs | Positive | None |
| TC-SPIC-19 | View PIC detail | pic_id: valid ID | Display complete PIC information | Positive | None |
| TC-SPIC-20 | View non-existing PIC | pic_id: invalid ID | Error: "PIC not found" | Negative | None |
| **UPDATE SUPPLIER PIC TESTS** |
| TC-SPIC-21 | Update PIC with valid data | Valid PIC data changes | PIC updated successfully | Positive | None |
| TC-SPIC-22 | Update PIC name to duplicate in same supplier | name: existing PIC name, supplier: same supplier | Error: "Nama PIC sudah ada untuk supplier ini, silakan gunakan nama lain" | Negative | None |
| TC-SPIC-23 | Update PIC status to inactive | status: inactive | Status updated with confirmation | Positive | None |
| TC-SPIC-24 | Update PIC with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE SUPPLIER PIC TESTS** |
| TC-SPIC-25 | Delete unused PIC | pic_id: unused PIC | PIC deleted successfully | Positive | None |
| TC-SPIC-26 | Delete PIC with references | pic_id: PIC with FK references | Error: "PIC tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-SPIC-27 | Delete non-existing PIC | pic_id: invalid ID | Error: "PIC not found" | Negative | None |
| TC-SPIC-28 | Delete with confirmation dialog | Confirm deletion | PIC deleted after confirmation | Positive | None |
| TC-SPIC-29 | Cancel deletion | Cancel deletion dialog | PIC not deleted | Positive | None |
### 7. Products

Produk adalah entitas barang yang dijual atau dikelola oleh perusahaan. Setiap produk memiliki identitas unik, tipe, kategori, dan deskripsi.

#### Data Requirements

- **Product ID** (Required)
  - Kode unik produk, 4 karakter, format: CHAR(4)
  - Harus unik di seluruh sistem

- **Name** (Required)
  - Nama produk, maksimal 35 karakter, format: String
  - Minimal 3 karakter

- **Type** (Required)
  - Jenis produk, maksimal 12 karakter, format: String
  - Contoh: "Barang", "Jasa"

- **Category** (Required)
  - ID kategori produk, format: Integer
  - Merujuk ke tabel kategori

- **Description** (Optional)
  - Deskripsi produk, maksimal 225 karakter, format: String
  - Boleh kosong

#### Business Rules

- Product ID harus unik
- Nama produk harus unik dalam satu kategori
- Produk tidak dapat dihapus jika digunakan di transaksi lain
- Deskripsi boleh kosong

#### Functional Requirements

1. **Create Product**
   - User dapat menambahkan produk baru
   - Semua field required harus diisi
   - Validasi uniqueness untuk Product ID
   - Validasi format dan panjang untuk setiap field

2. **Read Product**
   - User dapat melihat daftar semua produk
   - User dapat mencari produk berdasarkan nama, tipe, atau kategori
   - User dapat melihat detail lengkap dari satu produk

3. **Update Product**
   - User dapat mengubah informasi produk yang sudah ada
   - Validasi yang sama seperti create product
   - Product ID tetap harus unik setelah update

4. **Delete Product**
   - User dapat menghapus produk
   - Validasi bahwa produk tidak digunakan di tabel lain
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules

- **Product ID**:
  - Required: "Kode produk wajib diisi"
  - Length (4): "Kode produk harus 4 karakter"
  - Unique: "Kode produk sudah ada, silakan gunakan kode lain"

- **Name**:
  - Required: "Nama produk wajib diisi"
  - Minimum length (3): "Nama produk minimal 3 karakter"
  - Maximum length (35): "Nama produk maksimal 35 karakter"
  - Unique in category: "Nama produk sudah ada di kategori ini"

- **Type**:
  - Required: "Tipe produk wajib diisi"
  - Maximum length (12): "Tipe produk maksimal 12 karakter"

- **Category**:
  - Required: "Kategori produk wajib diisi"
  - Integer: "Kategori produk harus berupa angka"

- **Description**:
  - Maximum length (225): "Deskripsi produk maksimal 225 karakter"

#### User Interface Requirements

- Form input untuk create dan update produk
- Table listing dengan pagination untuk menampilkan daftar produk
- Search dan filter berdasarkan nama, tipe, kategori
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements

- Produk digunakan di modul transaksi, inventory, dan reporting
- API endpoints untuk CRUD operations
- Export/import functionality untuk data produk

#### Test Cases

| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE PRODUCT TESTS** |
| TC-PR-01 | Create product with valid data | product_id: "A001", name: "Produk Satu", type: "Barang", category: 1, description: "Produk contoh" | Product created successfully | Positive | None |
| TC-PR-02 | Create product with duplicate product_id | product_id: existing | Error: "Kode produk sudah ada, silakan gunakan kode lain" | Negative | None |
| TC-PR-03 | Create product with name too short | name: "AB" | Error: "Nama produk minimal 3 karakter" | Boundary | None |
| TC-PR-04 | Create product with name too long | name: 36 chars | Error: "Nama produk maksimal 35 karakter" | Boundary | None |
| TC-PR-05 | Create product with type too long | type: 13 chars | Error: "Tipe produk maksimal 12 karakter" | Boundary | None |
| TC-PR-06 | Create product with empty category | category: "" | Error: "Kategori produk wajib diisi" | Negative | None |
| TC-PR-07 | Create product with non-integer category | category: "abc" | Error: "Kategori produk harus berupa angka" | Negative | None |
| TC-PR-08 | Create product with description too long | description: 226 chars | Error: "Deskripsi produk maksimal 225 karakter" | Boundary | None |
| **READ PRODUCT TESTS** |
| TC-PR-09 | View all products | N/A | Display all products with pagination | Positive | None |
| TC-PR-10 | Search product by name | search: "Produk Satu" | Display products containing "Produk Satu" | Positive | None |
| TC-PR-11 | Filter product by type | filter: "Barang" | Display only products with type "Barang" | Positive | None |
| TC-PR-12 | Filter product by category | filter: 1 | Display only products in category 1 | Positive | None |
| TC-PR-13 | View product detail | product_id: "A001" | Display complete product information | Positive | None |
| TC-PR-14 | View non-existing product | product_id: "ZZZZ" | Error: "Product not found" | Negative | None |
| **UPDATE PRODUCT TESTS** |
| TC-PR-15 | Update product with valid data | Valid product data changes | Product updated successfully | Positive | None |
| TC-PR-16 | Update product_id to duplicate | product_id: existing | Error: "Kode produk sudah ada, silakan gunakan kode lain" | Negative | None |
| TC-PR-17 | Update product with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE PRODUCT TESTS** |
| TC-PR-18 | Delete unused product | product_id: unused | Product deleted successfully | Positive | None |
| TC-PR-19 | Delete product with references | product_id: used in FK | Error: "Produk tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-PR-20 | Delete non-existing product | product_id: "ZZZZ" | Error: "Product not found" | Negative | None |
| TC-PR-21 | Delete with confirmation dialog | Confirm deletion | Product deleted after confirmation | Positive | None |
| TC-PR-22 | Cancel deletion | Cancel deletion dialog | Product not deleted | Positive | None |
### 8. Item

Item adalah satuan barang yang dapat dijual, dibeli, atau dikelola dalam sistem. Item berelasi dengan produk dan memiliki detail SKU, harga, satuan, dan stok.

#### Data Requirements

- **Product ID** (Required)
  - Kode produk, 4 karakter, format: CHAR(4)
  - Merujuk ke tabel produk

- **SKU** (Required)
  - Stock Keeping Unit, kode unik item, maksimal 50 karakter, format: String

- **Name** (Required)
  - Nama item, maksimal 50 karakter, format: String
  - Minimal 3 karakter

- **Measurement** (Required)
  - Satuan item, maksimal 6 karakter, format: String
  - Merujuk ke tabel measurement_unit

- **Base Price** (Required)
  - Harga dasar item, format: Integer
  - Default: 0

- **Selling Price** (Required)
  - Harga jual item, format: Integer
  - Default: 0

- **Purchase Unit** (Required)
  - Satuan pembelian, format: Integer
  - Default: 30 (kode unit Pieces)

- **Sell Unit** (Required)
  - Satuan penjualan, format: Integer
  - Default: 30

- **Stock Unit** (Required)
  - Satuan stok, format: Integer
  - Default: 0

#### Business Rules

- SKU harus unik di seluruh sistem
- Product ID harus valid dan ada di tabel produk
- Measurement harus valid dan ada di tabel measurement_unit
- Harga tidak boleh negatif
- Nama item harus unik dalam satu SKU

#### Functional Requirements

1. **Create Item**
   - User dapat menambahkan item baru
   - Semua field required harus diisi
   - Validasi uniqueness untuk SKU
   - Validasi format dan panjang untuk setiap field

2. **Read Item**
   - User dapat melihat daftar semua item
   - User dapat mencari item berdasarkan SKU, nama, atau measurement
   - User dapat melihat detail lengkap dari satu item

3. **Update Item**
   - User dapat mengubah informasi item yang sudah ada
   - Validasi yang sama seperti create item
   - SKU tetap harus unik setelah update

4. **Delete Item**
   - User dapat menghapus item
   - Validasi bahwa item tidak digunakan di transaksi lain
   - Soft delete direkomendasikan untuk audit trail
   - Konfirmasi diperlukan sebelum penghapusan

#### Validation Rules

- **Product ID**:
  - Required: "Kode produk wajib diisi"
  - Length (4): "Kode produk harus 4 karakter"
  - Exists: "Kode produk tidak ditemukan"

- **SKU**:
  - Required: "SKU wajib diisi"
  - Maximum length (50): "SKU maksimal 50 karakter"
  - Unique: "SKU sudah ada, silakan gunakan kode lain"

- **Name**:
  - Required: "Nama item wajib diisi"
  - Minimum length (3): "Nama item minimal 3 karakter"
  - Maximum length (50): "Nama item maksimal 50 karakter"
  - Unique in SKU: "Nama item sudah ada untuk SKU ini"

- **Measurement**:
  - Required: "Satuan wajib diisi"
  - Maximum length (6): "Satuan maksimal 6 karakter"
  - Exists: "Satuan tidak ditemukan"

- **Base Price**:
  - Required: "Harga dasar wajib diisi"
  - Integer: "Harga dasar harus berupa angka"
  - Min (0): "Harga dasar tidak boleh negatif"

- **Selling Price**:
  - Required: "Harga jual wajib diisi"
  - Integer: "Harga jual harus berupa angka"
  - Min (0): "Harga jual tidak boleh negatif"

- **Purchase Unit**:
  - Required: "Satuan pembelian wajib diisi"
  - Integer: "Satuan pembelian harus berupa angka"

- **Sell Unit**:
  - Required: "Satuan penjualan wajib diisi"
  - Integer: "Satuan penjualan harus berupa angka"

- **Stock Unit**:
  - Required: "Satuan stok wajib diisi"
  - Integer: "Satuan stok harus berupa angka"

#### User Interface Requirements

- Form input untuk create dan update item
- Table listing dengan pagination untuk menampilkan daftar item
- Search dan filter berdasarkan SKU, nama, measurement
- Confirmation dialog untuk delete operations
- Responsive design untuk mobile dan desktop

#### Integration Requirements

- Item digunakan di modul transaksi, inventory, dan reporting
- API endpoints untuk CRUD operations
- Export/import functionality untuk data item

#### Test Cases

| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type | Actual Result |
|--------------|---------------|------------|-----------------|-----------|---------------|
| **CREATE ITEM TESTS** |
| TC-IT-01 | Create item with valid data | product_id: "A001", sku: "SKU001", name: "Item Satu", measurement: "PCS", base_price: 1000, selling_price: 1200, purchase_unit: 30, sell_unit: 30, stock_unit: 0 | Item created successfully | Positive | None |
| TC-IT-02 | Create item with duplicate SKU | sku: existing | Error: "SKU sudah ada, silakan gunakan kode lain" | Negative | None |
| TC-IT-03 | Create item with name too short | name: "AB" | Error: "Nama item minimal 3 karakter" | Boundary | None |
| TC-IT-04 | Create item with name too long | name: 51 chars | Error: "Nama item maksimal 50 karakter" | Boundary | None |
| TC-IT-05 | Create item with measurement too long | measurement: 7 chars | Error: "Satuan maksimal 6 karakter" | Boundary | None |
| TC-IT-06 | Create item with negative base price | base_price: -1 | Error: "Harga dasar tidak boleh negatif" | Negative | None |
| TC-IT-07 | Create item with negative selling price | selling_price: -1 | Error: "Harga jual tidak boleh negatif" | Negative | None |
| TC-IT-08 | Create item with non-integer purchase_unit | purchase_unit: "abc" | Error: "Satuan pembelian harus berupa angka" | Negative | None |
| TC-IT-09 | Create item with non-integer sell_unit | sell_unit: "abc" | Error: "Satuan penjualan harus berupa angka" | Negative | None |
| TC-IT-10 | Create item with non-integer stock_unit | stock_unit: "abc" | Error: "Satuan stok harus berupa angka" | Negative | None |
| **READ ITEM TESTS** |
| TC-IT-11 | View all items | N/A | Display all items with pagination | Positive | None |
| TC-IT-12 | Search item by SKU | search: "SKU001" | Display items containing "SKU001" | Positive | None |
| TC-IT-13 | Filter item by measurement | filter: "PCS" | Display only items with measurement "PCS" | Positive | None |
| TC-IT-14 | View item detail | sku: "SKU001" | Display complete item information | Positive | None |
| TC-IT-15 | View non-existing item | sku: "ZZZZ" | Error: "Item not found" | Negative | None |
| **UPDATE ITEM TESTS** |
| TC-IT-16 | Update item with valid data | Valid item data changes | Item updated successfully | Positive | None |
| TC-IT-17 | Update SKU to duplicate | sku: existing | Error: "SKU sudah ada, silakan gunakan kode lain" | Negative | None |
| TC-IT-18 | Update item with invalid data | Invalid field values | Appropriate validation errors | Negative | None |
| **DELETE ITEM TESTS** |
| TC-IT-19 | Delete unused item | sku: unused | Item deleted successfully | Positive | None |
| TC-IT-20 | Delete item with references | sku: used in FK | Error: "Item tidak dapat dihapus karena masih digunakan" | Negative | None |
| TC-IT-21 | Delete non-existing item | sku: "ZZZZ" | Error: "Item not found" | Negative | None |
| TC-IT-22 | Delete with confirmation dialog | Confirm deletion | Item deleted after confirmation | Positive | None |
| TC-IT-23 | Cancel deletion | Cancel deletion dialog | Item not deleted | Positive | None |