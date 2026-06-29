@extends('layouts.app')

@section('title', 'Category Detail')

@section('page-title')
<h1 class="mb-0">Category Detail</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Information</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th style="width: 200px;">ID</th>
                                <td>{{ $category->id }}</td>
                            </tr>
                            <tr>
                                <th>Category Name</th>
                                <td>{{ $category->category }}</td>
                            </tr>
                            <tr>
                                <th>Parent Category</th>
                                <td>
                                    @if($category->parent)
                                        <a href="{{ route('categories.show', $category->parent->id) }}">
                                            {{ $category->parent->category }}
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">No Parent</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $category->created_at ? $category->created_at->format('d-m-Y H:i:s') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $category->updated_at ? $category->updated_at->format('d-m-Y H:i:s') : 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            @if($category->children->count() > 0)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sub Categories ({{ $category->children->count() }})</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($category->children as $child)
                            <li class="list-group-item">
                                <a href="{{ route('categories.show', $child->id) }}">
                                    {{ $child->category }}
                                    @if(!$child->is_active)
                                        <span class="badge bg-danger float-end">Inactive</span>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
