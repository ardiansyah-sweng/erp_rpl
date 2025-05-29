@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Data Supplier PIC (Dummy)</h1>

    <table class="min-w-full border border-gray-300 bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="px-4 py-2 border">Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dummySupplierPICs as $pic)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ $pic['name'] }}</td>
                <td class="px-4 py-2 border">{{ $pic['email'] }}</td>
                <td class="px-4 py-2 border">{{ $pic['phone'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
