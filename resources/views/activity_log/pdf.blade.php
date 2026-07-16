<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Aktivitas</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }
        .badge-create { background-color: #198754; }
        .badge-update { background-color: #0d6efd; }
        .badge-delete { background-color: #dc3545; }
        .badge-module { background-color: #0dcaf0; color: #000; }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Log Aktivitas</h1>
        <p>ERP RPL UAD — Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if($search || $module || $action)
    <div class="filter-info">
        <strong>Filter aktif:</strong>
        @if($search) Pencarian: "{{ $search }}" | @endif
        @if($module) Modul: {{ $modules[$module] ?? $module }} | @endif
        @if($action) Aksi: {{ $actions[$action] ?? $action }} @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 110px;">Waktu</th>
                <th style="width: 90px;">User</th>
                <th style="width: 55px;">Aksi</th>
                <th style="width: 90px;">Modul</th>
                <th>Deskripsi</th>
                <th style="width: 80px;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->user_name ?? 'System' }}</td>
                <td class="text-center">
                    @if($log->action === 'create')
                        <span class="badge badge-create">Create</span>
                    @elseif($log->action === 'update')
                        <span class="badge badge-update">Update</span>
                    @elseif($log->action === 'delete')
                        <span class="badge badge-delete">Delete</span>
                    @endif
                </td>
                <td class="text-center">
                    <span class="badge badge-module">{{ $modules[$log->module] ?? ucfirst($log->module) }}</span>
                </td>
                <td>{{ $log->description }}</td>
                <td class="text-center">{{ $log->ip_address ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data log aktivitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total: {{ count($logs) }} log aktivitas |
        Dicetak oleh: {{ auth()->user()->name ?? 'System' }}
    </div>
</body>
</html>
