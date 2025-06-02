@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Bill of Material</h3>
    <form>
        <!-- Informasi B.O.M -->
        <div class="row">
            <div class="col-md-6">
                <label>No. B.O.M</label>
                <input type="text" class="form-control" value="{{ $material->kode }}" readonly>
            </div>
            <!-- Lanjutkan field lain sesuai kebutuhan -->
        </div>

        <!-- Table Item -->
        <h5 class="mt-4">Daftar Bahan Baku</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th><th>Nama Item</th><th>Qty</th><th>Satuan</th><th>Standard Cost</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($material->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->nama_item }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>{{ $detail->satuan }}</td>
                    <td>{{ number_format($detail->standard_cost, 0, ',', '.') }}</td>
                    <td>
                        <a href="#">✎</a>
                        <a href="#">🗑</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>
@endsection
