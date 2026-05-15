{{-- resources/views/category/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="categoryForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <!-- Label diberikan ID agar teksnya bisa diubah via JS -->
            <label for="category" id="category_label">Nama Grup Kategori Produk <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('category') is-invalid @enderror"
                   id="category"
                   name="category"
                   placeholder="Masukkan nama kategori"
                   value="{{ old('category', $category->category ?? '') }}"
                   required>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Minimal 3 karakter, hanya huruf, angka, spasi, dash, underscore, dan titik</small>
        </div>

        <!-- Checkbox Sub Kategori -->
        <div class="form-group">
            <div class="form-check">
                <!-- Kita cek apakah sebelumnya ada parent_id (misal dari old input error atau saat edit) -->
                <input type="checkbox" class="form-check-input" id="is_subcategory"
                       {{ old('parent_id', $category->parent_id ?? false) ? 'checked' : '' }}>
                <label class="form-check-label font-weight-bold" for="is_subcategory">
                    Sub Kategori
                </label>
            </div>
            <small class="form-text text-muted">Centang jika ini adalah sub-kategori dari grup lain</small>
        </div>

        <!-- Wrapper Dropdown Parent (Disembunyikan secara default menggunakan style="display: none;") -->
        <div class="form-group" id="parent_category_wrapper" style="display: none;">
            <label for="parent_id">Grup Kategori Produk (Parent)</label>
            <select class="form-control @error('parent_id') is-invalid @enderror"
                    id="parent_id"
                    name="parent_id">
                <option value="">-- Pilih Grup Kategori Produk --</option>
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $parentCategory)
                        @if(!isset($category) || $parentCategory->id !== $category->id)
                            <option value="{{ $parentCategory->id }}"
                                    {{ old('parent_id', $category->parent_id ?? '') == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->category }}
                                @if($parentCategory->parent)
                                    ({{ $parentCategory->parent->category }})
                                @endif
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>
            @error('parent_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       class="form-check-input @error('is_active') is-invalid @enderror"
                       id="is_active"
                       name="is_active"
                       value="1"
                       {{ old('is_active', ($category->is_active ?? true)) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Aktif
                </label>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Add
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
             Cancel
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Logic Skenario 1 dan 2 (Tampilkan/Sembunyikan Dropdown) ===
    const isSubcategoryCheckbox = document.getElementById('is_subcategory');
    const parentCategoryWrapper = document.getElementById('parent_category_wrapper');
    const parentIdSelect = document.getElementById('parent_id');
    const categoryLabel = document.getElementById('category_label');

    function toggleParentDropdown() {
        if (isSubcategoryCheckbox.checked) {
            // Skenario 2: Checkbox dicentang
            parentCategoryWrapper.style.display = 'block';
            categoryLabel.innerHTML = 'Nama Sub Kategori Produk <span class="text-danger">*</span>';
        } else {
            // Skenario 1: Checkbox tidak dicentang
            parentCategoryWrapper.style.display = 'none';
            parentIdSelect.value = ''; // Reset pilihan parent
            categoryLabel.innerHTML = 'Nama Grup Kategori Produk <span class="text-danger">*</span>';
        }
    }

    // Jalankan saat halaman pertama kali diload (mengatasi retain data saat validasi error / edit)
    toggleParentDropdown();

    // Jalankan setiap kali checkbox di-klik
    isSubcategoryCheckbox.addEventListener('change', toggleParentDropdown);


    // === Form validation enhancement bawaan dari kode kamu sebelumnya ===
    const form = document.getElementById('categoryForm');
    const categoryInput = document.getElementById('category');

    categoryInput.addEventListener('input', function() {
        const value = this.value.trim();
        const regex = /^[a-zA-Z0-9\s\-\_\.]+$/;

        if (value.length > 0 && value.length < 3) {
            this.setCustomValidity('Category name must be at least 3 characters long');
        } else if (value.length > 0 && !regex.test(value)) {
            this.setCustomValidity('Category name can only contain letters, numbers, spaces, hyphens, underscores, and dots');
        } else {
            this.setCustomValidity('');
        }
    });

    form.addEventListener('submit', function(e) {
        const categoryValue = categoryInput.value.trim();

        if (categoryValue.length < 3) {
            e.preventDefault();
            alert('Category name must be at least 3 characters long');
            categoryInput.focus();
            return false;
        }

        // Validasi tambahan: Jika checkbox sub kategori dicentang, pastikan dropdown parent dipilih
        if (isSubcategoryCheckbox.checked && parentIdSelect.value === '') {
            e.preventDefault();
            alert('Silakan pilih Grup Kategori Produk (Parent)!');
            parentIdSelect.focus();
            return false;
        }

        const method = form.querySelector('input[name="_method"]');
        const action = (!method || method.value === 'POST') ? 'menambahkan' : 'mengupdate';
        if (!confirm(`Apakah Anda yakin ingin ${action} category ini?`)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
