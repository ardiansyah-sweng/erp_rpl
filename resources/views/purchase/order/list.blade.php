<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Daftar Purchase Order</title>
    <style>
        .hover-row:hover {
            background-color: #f3f4f6;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-6">
            
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Daftar <a class="underline decoration-gray-800"><span class="text-transparent bg-clip-text bg-gradient-to-tr to-cyan-500 from-blue-600">Purchase Order.</span></a></h1>
            
        </div>

        <div class="flex items-center mb-4">
            <input type="text" placeholder="Cari PO atau supplier..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button class="ml-2 bg-blue-100 text-blue-500 px-4 py-2 rounded-lg hover:bg-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a8 8 0 106.32 3.906l4.387 4.387a1 1 0 01-1.414 1.414l-4.387-4.387A8 8 0 0010 2zM4 10a6 6 0 1111.32 3.906l-4.387 4.387a1 1 0 01-1.414-1.414l4.387-4.387A6 6 0 014 10z" clip-rule="evenodd" />
                </svg>
                Filter
            </button>
        </div>

        @if(session('message'))
            <p class="text-red-500 mb-4">{{ session('message') }}</p>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border ">
                <thead>
                    <tr class="bg-blue-500 text-white uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No. PO</th>
                        <th class="py-3 px-6 text-left">Supplier</th>
                        <th class="py-3 px-6 text-left">Total</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-black text-sm font-light">
                    @isset($purchaseOrders)
                        @forelse($purchaseOrders as $po)
                            <tr class="border-b text" onclick="showDetails('{{ $po->po_number }}')">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $po->po_number }}</td>
                                <td class="py-3 px-6 text-left">{{ $po->supplier_id }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ number_format($po->total, 0, ',', '.') }}</td>
                                <td class="py-3 px-6 text-left">{{ $po->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-3 px-6 text-center">Tidak ada data purchase order.</td>
                            </tr>
                        @endforelse
                    @endisset
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $purchaseOrders->links('pagination::tailwind') }}
        </div>
    </div>
</body>
</html>