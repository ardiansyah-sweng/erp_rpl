@extends('layouts.app')

@section('title', 'Detail Merk')

@section('page-title')
    <h3 class="mb-0">Detail Merk</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merks.index') }}">Merk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Informasi Merk
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">Id</th>
                                <td>{{ $merk->id ?? 'Tidak ada data' }}</td>
                            </tr>
                            <tr>
                                <th>Merk</th>
                                <td>{{ $merk->merk ?? 'Tidak ada data' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function () {
        $('[data-widget="pushmenu"]').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-collapse');
        });
    });
    </script>
@endpush
