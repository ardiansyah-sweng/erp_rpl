@extends('layouts.app')

@section('title', 'Edit User')

@section('page-title')
    <h3 class="mb-0">Edit User</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Kelola User</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-pencil"></i> Form Edit User
                    </h3>
                </div>

                @include('users.form', [
                    'action' => route('users.update', $user->id),
                    'method' => 'PUT',
                    'submitText' => 'Update',
                    'user' => $user,
                    'roles' => $roles,
                ])
            </div>
        </div>
    </div>
@endsection
