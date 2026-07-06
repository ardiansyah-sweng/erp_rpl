@extends('layouts.app')

@section('title', 'Detail Merk')

@section('page-title')
    <h3 class="mb-0">Detail Merk</h3>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merk.index') }}">Merk</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="bi bi-info-circle"></i> Detail Merk: {{ $merk->merk }}
                                    </h3>
                                    <div class="card-tools">
                                        {!! $merk->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>' !!}
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-borderless table-striped">
                                                <tr>
                                                    <td class="fw-bold" style="width: 30%;">ID:</td>
                                                    <td>{{ $merk->id }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Nama Merk:</td>
                                                    <td class="fs-5 fw-semibold">{{ $merk->merk }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Status:</td>
                                                    <td>
                                                        {!! $merk->is_active ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Aktif</span>' : '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Tidak Aktif</span>' !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Dibuat:</td>
                                                    <td>
                                                        {{ $merk->created_at ? $merk->created_at->format('d/m/Y H:i:s') : '-' }}
                                                        @if($merk->created_at)
                                                            <br><small class="text-muted">({{ $merk->created_at->diffForHumans() }})</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Terakhir Diupdate:</td>
                                                    <td>
                                                        {{ $merk->updated_at ? $merk->updated_at->format('d/m/Y H:i:s') : '-' }}
                                                        @if($merk->updated_at)
                                                            <br><small class="text-muted">({{ $merk->updated_at->diffForHumans() }})</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Additional Information Section -->
                                    <hr class="my-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="bi bi-graph-up"></i> Informasi Tambahan
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="card bg-primary text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-calendar-plus fs-2"></i>
                                                            <h6 class="card-title mt-2">Usia Data</h6>
                                                            <p class="card-text">
                                                                {{ $merk->created_at ? $merk->created_at->diffForHumans() : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-info text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-pencil-square fs-2"></i>
                                                            <h6 class="card-title mt-2">Update Terakhir</h6>
                                                            <p class="card-text">
                                                                {{ $merk->updated_at ? $merk->updated_at->diffForHumans() : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card bg-{{ $merk->is_active ? 'success' : 'secondary' }} text-white">
                                                        <div class="card-body text-center">
                                                            <i class="bi bi-{{ $merk->is_active ? 'check-circle' : 'x-circle' }} fs-2"></i>
                                                            <h6 class="card-title mt-2">Status</h6>
                                                            <p class="card-text">
                                                                {{ $merk->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('merks.index') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('merks.edit', $merk->id) }}" class="btn btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus merk <strong>"{{ $merk->merk }}"</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <form action="{{ route('merk.destroy', $merk->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Ya, Hapus!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
