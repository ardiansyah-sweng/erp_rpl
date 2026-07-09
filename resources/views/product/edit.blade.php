<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3 class="mb-4">Edit Produk</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product.updateProduct', $product->product_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_id" class="form-label">Product ID</label>
            <input type="text" id="product_id" class="form-control" value="{{ $product->product_id }}" readonly>
        </div>

        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" id="product_name" name="product_name" class="form-control" value="{{ old('product_name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="product_type" class="form-label">Product Type</label>
            @php
                $selectedType = old('product_type', $product->type instanceof \App\Enums\ProductType ? $product->type->value : $product->type);
            @endphp
            <select id="product_type" name="product_type" class="form-select" required>
                <option value="FG" {{ $selectedType === 'FG' ? 'selected' : '' }}>Finished Good</option>
                <option value="RM" {{ $selectedType === 'RM' ? 'selected' : '' }}>Raw Material</option>
                <option value="HFG" {{ $selectedType === 'HFG' ? 'selected' : '' }}>Half Finished Goods</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="product_category" class="form-label">Product Category</label>
            <select id="product_category" name="product_category" class="form-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (int) old('product_category', $product->category) === (int) $category->id ? 'selected' : '' }}>
                        {{ $category->category }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="product_description" class="form-label">Product Description</label>
            <textarea id="product_description" name="product_description" class="form-control" rows="3">{{ old('product_description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="jumlah_item" class="form-label">Jumlah Item</label>
            <input type="number" id="jumlah_item" class="form-control" value="{{ old('jumlah_item', $product->items_count ?? 0) }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('product.list') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
