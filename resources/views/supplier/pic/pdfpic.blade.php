<!DOCTYPE html>
<html>
<head>
    <title>Data Semua PIC Supplier</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Data Semua PIC dari Semua Supplier</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Supplier ID</th>
                <th>Nama Supplier</th>
                <th>Nama PIC</th>
                <th>Email</th>
                <th>Telephone</th>
                <th>Durasi Penugasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pics as $index => $pic)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pic->supplier_id }}</td>
                    <td>{{ $pic->supplier ? $pic->supplier->company_name : 'N/A' }}</td>
                    <td>{{ $pic->name }}</td>
                    <td>{{ $pic->email }}</td>
                    <td>{{ $pic->phone_number }}</td>
                    <td>
                        @php
                            $duration = json_decode(\App\Models\SupplierPic::assignmentDuration($pic));
                        @endphp
                        @if(is_object($duration))
                            {{ $duration->years }} tahun, {{ $duration->months }} bulan, {{ $duration->days }} hari
                        @else
                            Tanggal belum tersedia
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data PIC yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
