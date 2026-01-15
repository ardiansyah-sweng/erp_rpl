{{-- resources/views/category/form.blade.php --}}
<form action="{{ $action }}" method="POST" id="categoryForm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <div class="card-body">
        <div class="form-group">
            <label for="category">Nama Category <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('category') is-invalid @enderror" 
                   id="category" 
                   name="category" 
                   placeholder="Masukkan nama category" 
                   value="{{ old('category', $category->category ?? '') }}"
                   required>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Minimal 3 karakter, hanya huruf, angka, spasi, dash, underscore, dan titik</small>
        </div>

        <div class="form-group">
            <label for="parent_id">Parent Category</label>
            <select class="form-control @error('parent_id') is-invalid @enderror" 
                    id="parent_id" 
                    name="parent_id">
                <option value="">-- Pilih Parent Category (Opsional) --</option>
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
            <small class="form-text text-muted">Kosongkan jika ini adalah kategori utama</small>
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
                    Status Aktif
                </label>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <small class="form-text text-muted">Centang untuk mengaktifkan category ini</small>
        </div>
    </div>
    
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
        </button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation enhancement
    const form = document.getElementById('categoryForm');
    const categoryInput = document.getElementById('category');
    
    // Real-time validation for category name
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
    
    // Form submission handling
    form.addEventListener('submit', function(e) {
        const categoryValue = categoryInput.value.trim();
        
        if (categoryValue.length < 3) {
            e.preventDefault();
            alert('Category name must be at least 3 characters long');
            categoryInput.focus();
            return false;
        }
        
        // Confirm submission
        const method = form.querySelector('input[name="_method"]');
        const action = (!method || method.value === 'POST') ? 'menambahkan' : 'mengupdate';
        if (!confirm(`Apakah Anda yakin ingin ${action} category ini?`)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-text {
    font-size: 0.875rem;
}

.text-danger {
    color: #dc3545 !important;
}

.form-check {
    padding-left: 1.5rem;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.btn {
    margin-right: 0.5rem;
}

.invalid-feedback {
    display: block;
    font-size: 0.875rem;
    color: #dc3545;
    margin-top: 0.25rem;
}

.is-invalid {
    border-color: #dc3545;
}
</style>
