@extends('layouts.app')

@section('title', 'Edit Cabang')

@section('page-title')
<h3 class="mb-0">Edit Cabang</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
<li class="breadcrumb-item"><a href="/branch/list">Cabang</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Formulir Edit Cabang</h3>
                    </div>
                    @include('branches.form', [
                        'action' => route('branches.update', $branch->id),
                        'method' => 'PUT',
                        'branch' => $branch
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
