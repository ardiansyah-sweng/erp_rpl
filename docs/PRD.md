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
- Branch data akan digunakan sebagai reference di modul lain (Warehouse, User Management, Transaction)
- API endpoints untuk CRUD operations
- Export functionality untuk reporting (PDF, Excel)
- Import functionality untuk bulk data entry

#### Test Cases
| Test Case ID | Test Scenario | Input Data | Expected Result | Test Type |
|--------------|---------------|------------|-----------------|-----------|
| **CREATE BRANCH TESTS** |
| TC-BR-01 | Create branch with valid data | name: "Cabang Jakarta", address: "Jl. Sudirman No.1", telephone: "021-12345678" | Branch created successfully | Positive |
| TC-BR-02 | Create branch with minimum length name | name: "ABC" (3 chars) | Branch created successfully | Boundary |
| TC-BR-03 | Create branch with maximum length name | name: 50 characters string | Branch created successfully | Boundary |
| TC-BR-04 | Create branch with name too short | name: "AB" (2 chars) | Error: "Nama cabang minimal 3 karakter" | Boundary |
| TC-BR-05 | Create branch with name too long | name: 51 characters string | Error: "Nama cabang maksimal 50 karakter" | Boundary |
| TC-BR-06 | Create branch with duplicate name | name: existing branch name | Error: "Nama cabang sudah ada, silakan gunakan nama lain" | Negative |
| TC-BR-07 | Create branch with empty name | name: "" | Error: "Nama cabang wajib diisi" | Negative |
| TC-BR-08 | Create branch with minimum length address | address: "JL." (3 chars) | Branch created successfully | Boundary |
| TC-BR-09 | Create branch with maximum length address | address: 100 characters string | Branch created successfully | Boundary |
| TC-BR-10 | Create branch with address too short | address: "JL" (2 chars) | Error: "Alamat cabang minimal 3 karakter" | Boundary |
| TC-BR-11 | Create branch with address too long | address: 101 characters string | Error: "Alamat cabang maksimal 100 karakter" | Boundary |
| TC-BR-12 | Create branch with empty address | address: "" | Error: "Alamat cabang wajib diisi" | Negative |
| TC-BR-13 | Create branch with minimum length telephone | telephone: "123" (3 chars) | Branch created successfully | Boundary |
| TC-BR-14 | Create branch with maximum length telephone | telephone: 30 characters string | Branch created successfully | Boundary |
| TC-BR-15 | Create branch with telephone too short | telephone: "12" (2 chars) | Error: "Telepon cabang minimal 3 karakter" | Boundary |
| TC-BR-16 | Create branch with telephone too long | telephone: 31 characters string | Error: "Telepon cabang maksimal 30 karakter" | Boundary |
| TC-BR-17 | Create branch with empty telephone | telephone: "" | Error: "Telepon cabang wajib diisi" | Negative |
| **READ BRANCH TESTS** |
| TC-BR-18 | View all branches | N/A | Display all branches with pagination | Positive |
| TC-BR-19 | Search branch by name | search: "Jakarta" | Display branches containing "Jakarta" | Positive |
| TC-BR-20 | Search branch by address | search: "Sudirman" | Display branches with address containing "Sudirman" | Positive |
| TC-BR-21 | Filter branch by active status | filter: "active" | Display only active branches | Positive |
| TC-BR-22 | Filter branch by inactive status | filter: "inactive" | Display only inactive branches | Positive |
| TC-BR-23 | View branch detail | branch_id: valid ID | Display complete branch information | Positive |
| TC-BR-24 | View non-existing branch | branch_id: invalid ID | Error: "Branch not found" | Negative |
| **UPDATE BRANCH TESTS** |
| TC-BR-25 | Update branch with valid data | Valid branch data changes | Branch updated successfully | Positive |
| TC-BR-26 | Update branch name to duplicate | name: existing branch name | Error: "Nama cabang sudah ada, silakan gunakan nama lain" | Negative |
| TC-BR-27 | Update branch status to inactive | status: inactive | Status updated with confirmation | Positive |
| TC-BR-28 | Update branch with invalid data | Invalid field values | Appropriate validation errors | Negative |
| **DELETE BRANCH TESTS** |
| TC-BR-29 | Delete unused branch | branch_id: unused branch | Branch deleted successfully | Positive |
| TC-BR-30 | Delete branch with references | branch_id: branch with FK references | Error: "Branch tidak dapat dihapus karena masih digunakan" | Negative |
| TC-BR-31 | Delete non-existing branch | branch_id: invalid ID | Error: "Branch not found" | Negative |
| TC-BR-32 | Delete with confirmation dialog | Confirm deletion | Branch deleted after confirmation | Positive |
| TC-BR-33 | Cancel deletion | Cancel deletion dialog | Branch not deleted | Positive |

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

#### 4. Category
Category digunakan untuk mengklasifikasikan atau pengelompokan produk. Category dibuat bersarang. Jadi ada category induk yang tidak memiliki sub-category dan ada sub-category yang merupakan anak dari category induk.

#### Data
- **Category**. Nama kategori produk
- **Parent**. ID kategori induk (default NULL)
- **Status**. Status category saat ini apakah aktif atau tidak aktif

#### Rule
- Category induk hanya bisa dihapus selama sub-category tidak muncul di tabel lain
- Sub-category hanya bisa dihapus selama tidak muncul di tabel lain