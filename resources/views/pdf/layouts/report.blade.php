<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Report')</title>
    <style>
        @font-face {
            font-family: 'Open Sans';
            src: url({{ storage_path('fonts/OpenSans-Regular.ttf') }}) format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        body { 
            font-family: 'Open Sans', sans-serif;
            margin: 3cm 2cm 2cm 2cm;
        }
        .header { 
            position: fixed;
            top: -2cm;
            left: 0;
            right: 0;
            text-align: center;
            padding: 20px;
        }
        .footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            page-break-inside: auto;
        }
        tr { 
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        th { 
            background-color: #f8f9fa;
            font-weight: bold;
        }
        h1 { 
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .meta-info {
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        @yield('header')
    </div>
    
    <div class="content">
        @yield('content')
    </div>
    
    <div class="footer">
        Page {PAGE_NUM} of {PAGE_COUNT}
        @yield('footer')
    </div>
</body>
</html>