@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('page-title')
    <h3 class="mb-0 me-2">Log Aktivitas</h3>
    <a href="{{ route('activity-logs.export-pdf', request()->query()) }}" class="btn btn-primary btn-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
    </a>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            Riwayat Aktivitas <br>
            Total Log : <strong>{{ $logs->total() }}</strong>
        </h3>
    </div>
    <div class="card-body">
        {{-- Filter & Search Form --}}
        <form action="{{ route('activity-logs.index') }}" method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" id="search" class="form-control form-control-sm"
                           placeholder="Cari deskripsi, user, atau ID..."
                           value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="module" class="form-label">Modul</label>
                    <select name="module" id="module" class="form-select form-select-sm">
                        <option value="">-- Semua Modul --</option>
                        @foreach($modules as $key => $label)
                            <option value="{{ $key }}" {{ ($module ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="action" class="form-label">Aksi</label>
                    <select name="action" id="action" class="form-select form-select-sm">
                        <option value="">-- Semua Aksi --</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}" {{ ($action ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Tabel Log Aktivitas --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th style="width: 160px;">Waktu</th>
                        <th>User</th>
                        <th style="width: 90px;">Aksi</th>
                        <th style="width: 140px;">Modul</th>
                        <th>Deskripsi</th>
                        <th style="width: 120px;">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td class="text-center">{{ $logs->firstItem() + $index }}</td>
                        <td class="text-center">
                            <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                        </td>
                        <td>{{ $log->user_name ?? 'System' }}</td>
                        <td class="text-center">
                            @if($log->action === 'create')
                                <span class="badge text-bg-success">Create</span>
                            @elseif($log->action === 'update')
                                <span class="badge text-bg-primary">Update</span>
                            @elseif($log->action === 'delete')
                                <span class="badge text-bg-danger">Delete</span>
                            @else
                                <span class="badge text-bg-secondary">{{ ucfirst($log->action) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge text-bg-info">{{ $modules[$log->module] ?? ucfirst($log->module) }}</span>
                        </td>
                        <td>{{ $log->description }}</td>
                        <td class="text-center"><small>{{ $log->ip_address ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- Pagination --}}
    <div class="card-footer clearfix">
        {{ $logs->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
