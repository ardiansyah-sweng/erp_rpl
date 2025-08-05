# Product Requirement Document (PRD)
## ERP-RPL
### Functional Requirements
#### 1. Branch
Branch adalah cabang-cabang yang dimiliki oleh perusahaan.

#### Data
- **Branch name**. Biasanya sebutan untuk cabang yang bersangkutan
- **Address**. Alamat lokasi cabang berada
- **Telephone**. Nomor telepon cabang. Bisa memiliki lebih dari satu nomor.
- **Status**. Status cabang saat ini apakah masih aktif atau sudah tidak aktif.
- **Maps**. Titik menunuju lokasi berdasarkan maps (bisa longitude latitude atau url google maps). Opsional.
  
#### Rule
- Branch hanya bisa dihapus jika tidak muncul di tabel lain

#### 2. Warehouse
Warehouse adalah gudang-gudang yang dimiliki oleh perusahaan. Gudang berfungsi untuk menyimpan bahan baku, bahan setengah jadi hingga barang jadi. Terkadang, gudang dan cabang berada di tempat yang sama.

#### Data
- **Warehouse name**. Sebutan untuk gudang yang bersangkutan.
- **Address**. Alamat lokaso gudang berada
- **Telephone**. Nomor telepon gudang. Bisa memiliki lebih dari satu nomor.
- **Status**. Status gudang saat ini apakah masih aktif atau sudah tidak aktif.

#### Rule
- Warehouse hanya bisa dihapus jika tidak muncul di tabel lain

#### 3. Merk
Merk adalah nama brand merk yang melekat pada suatu produk.

#### Data
- **Merk**. Nama merk
- **Status**. Status merk apakah aktif atau sudah non-aktif

#### Rule
- Merk hanya bisa dihapus jika tidak muncul di tabel lain

#### 4. Category
Category digunakan untuk mengklasifikasikan atau pengelompokan produk. Category dibuat bersarang. Jadi ada category induk yang tidak memiliki sub-category dan ada sub-category yang merupakan anak dari category induk.

#### Data
- **Category**. Nama kategori produk
- **Parent**. ID kategori induk (default NULL)
- **Status**. Status category saat ini apakah aktif atau tidak aktif

#### Rule
- Category induk hanya bisa dihapus selama sub-category tidak muncul di tabel lain
- Sub-category hanya bisa dihapus selama tidak muncul di tabel lain