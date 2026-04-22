<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Merk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>ERP RPL - LAPORAN DATA MERK</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Merk</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($merks as $key => $merk)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $merk->merk }}</td> {{-- Sesuaikan dengan konstanta MerkColumns::MERK --}}
                <td>
                    <span class="{{ $merk->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $merk->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>