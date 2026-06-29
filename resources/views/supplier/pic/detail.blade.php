@extends('layouts.app')

@section('title', 'Detail PIC Supplier')

@section('page-title')
<h3 class="mb-0">Detail PIC Supplier</h3>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Detail PIC Supplier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-xl-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
            </div>
            <div class="card-body">

                @php
                    $id = $pic->id;
                    $mod = $id % 6;
                    $filename = ($mod === 0 || $mod === 1) ? 'avatar.png' : "avatar{$mod}.png";
                    $avatarPath = asset("assets/dist/assets/img/{$filename}");
                @endphp

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 30%">ID Supplier</th>
                            <td>{{ $pic->supplier_id }}</td>
                        </tr>
                        <tr>
                            <th>Nama Supplier</th>
                            <td>{{ $supplier->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Nama PIC</th>
                            <td>{{ $pic->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $pic->email }}</td>
                        </tr>
                        <tr>
                            <th>Telephone</th>
                            <td>{{ $pic->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Assignment Date</th>
                            <td>{{ $pic->assigned_date }}</td>
                        </tr>
                        <tr>
                            <th>Foto</th>
                            <td>
                                <img src="{{ $avatarPath }}" style="width: 100px;">
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $pic->active == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{ route('supplier.pic.list') }}" class="btn btn-secondary mt-3">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>
@endsection
