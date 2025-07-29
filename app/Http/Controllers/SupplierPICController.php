<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierPICController extends Controller
{
    public function exportPDF($supplier_id)
    {
        $supplierPICs = [
            ['name' => 'Ahmad Faiz', 'email' => 'faiz@example.com', 'phone' => '0812-3456-7890'],
            ['name' => 'Budi Santoso', 'email' => 'budi@example.com', 'phone' => '0821-1234-5678'],
        ];

        return Pdf::loadView('supplier.pic.pdf', compact('supplierPICs', 'supplier_id'))
                  ->stream("supplier_pic_$supplier_id.pdf");
    }
}
