@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-semibold mb-4">Daftar Supplier</h1>

    <table class="min-w-full border border-gray-300 bg-white shadow rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Company Name</th>
                <th class="px-4 py-2 border">Address</th>
                <th class="px-4 py-2 border">Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border">{{ $supplier->id }}</td>
                <td class="px-4 py-2 border">{{ $supplier->company_name }}</td>
                <td class="px-4 py-2 border">{{ $supplier->address }}</td>
                <td class="px-4 py-2 border">{{ $supplier->phone_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
    