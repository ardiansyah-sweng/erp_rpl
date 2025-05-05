<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    {{-- Tambahkan sidebar di sini kalau ada --}}
    @include('layouts.sidebar')

    <div class="p-4">
        @yield('content')
    </div>

</body>
</html>
