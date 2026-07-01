@extends('layouts.app')

@section('title', 'Edit Category')

@section('page-title')
<h3 class="mb-0">Filled Form Category</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Edit Category</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <!-- Filled Form Supplier -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(isset($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endif
                @if($category)
                <form method="POST" action="{{ route('categories.update', $category->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Category ID</label>
                        <input type="number" class="form-control" value="{{ $category->id }}" disabled>
                        <input type="hidden" name="id" value="{{ $category->id }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $category->category) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent ID</label>
                        <input type="number" name="parent_id" class="form-control" value="{{ old('parent_id', $category->parent_id) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Active</label>
                        <input type="boolean" name="active" class="form-control" value="{{ old('active', $category->active) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ url()->current() }}" class="btn btn-secondary">Batal</a>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
