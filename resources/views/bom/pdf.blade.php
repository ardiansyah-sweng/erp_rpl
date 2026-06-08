<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bill of Material (BOM)</title>
    <style>
        body {
            font-size: 10pt;
            margin: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .info {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h3>Laporan Bill of Material (BOM)</h3>
    <div class="info">
        <strong>Generated At:</strong> {{ now()->format('d-m-Y H:i:s') }}
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%">No</th>
                <th>ID BOM</th>
                <th>Nama BOM</th>
                <th>Measurement Unit</th>
                <th>Total Cost</th>
                <th class="text-center">Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($boms) && count($boms) > 0)
                @foreach($boms as $i => $bom)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $bom->bom_id }}</td>
                        <td>{{ $bom->bom_name }}</td>
                        <td>{{ $bom->measurement_unit }}</td>
                        <td>Rp{{ number_format($bom->total_cost, 0, ',', '.') }}</td>
                        <td class="text-center">
                            {{ $bom->active ? 'AKTIF' : 'NONAKTIF' }}
                        </td>
                        <td>{{ $bom->created_at ? $bom->created_at->format('d-m-Y') : '-' }}</td>
                    </tr>
                @endforeach
            @else
                <!-- Fallback Data Dummy Statis Jika Database Kosong -->
                <tr>
                    <td class="text-center">1</td>
                    <td>BOM001</td>
                    <td>Produk A</td>
                    <td>100 pcs</td>
                    <td>Rp200.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>08-06-2024</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>BOM002</td>
                    <td>Produk B</td>
                    <td>50 Kg</td>
                    <td>Rp245.000</td>
                    <td class="text-center">NONAKTIF</td>
                    <td>05-06-2024</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>BOM003</td>
                    <td>Produk C</td>
                    <td>30 Kg</td>
                    <td>Rp115.000</td>
                    <td class="text-center">NONAKTIF</td>
                    <td>11-06-2025</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>BOM004</td>
                    <td>Produk D</td>
                    <td>1 TON</td>
                    <td>Rp985.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>01-01-2025</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td>BOM005</td>
                    <td>Produk E</td>
                    <td>1.2 TON</td>
                    <td>Rp1.225.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>01-04-2025</td>
                </tr>
                <tr>
                    <td class="text-center">6</td>
                    <td>BOM006</td>
                    <td>Produk F</td>
                    <td>3 Kwintal</td>
                    <td>Rp950.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>30-05-2025</td>
                </tr>
                <tr>
                    <td class="text-center">7</td>
                    <td>BOM007</td>
                    <td>Produk G</td>
                    <td>1 Kwintal</td>
                    <td>Rp350.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>30-11-2025</td>
                </tr>
                <tr>
                    <td class="text-center">8</td>
                    <td>BOM008</td>
                    <td>Produk H</td>
                    <td>1 Kwintal</td>
                    <td>Rp150.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>30-05-2025</td>
                </tr>
                <tr>
                    <td class="text-center">9</td>
                    <td>BOM009</td>
                    <td>Produk I</td>
                    <td>70 Liter</td>
                    <td>Rp850.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>31-05-2025</td>
                </tr>
                <tr>
                    <td class="text-center">10</td>
                    <td>BOM010</td>
                    <td>Produk J</td>
                    <td>3.5 Kwintal</td>
                    <td>Rp550.000</td>
                    <td class="text-center">AKTIF</td>
                    <td>30-03-2025</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
