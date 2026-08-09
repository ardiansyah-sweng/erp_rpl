<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseOrderExport implements FromCollection, WithHeadings, WithMapping
{
    protected $purchaseOrders;

    // Menerima data dari Controller
    public function __construct($purchaseOrders)
    {
        $this->purchaseOrders = $purchaseOrders;
    }

    // Mengembalikan koleksi data
    public function collection()
    {
        return $this->purchaseOrders;
    }

    // Mengatur judul kolom paling atas di Excel
    public function headings(): array
    {
        return [
            'PO Number',
            'Supplier ID',
            'Tanggal Order',
            'Total (Rp)',
            'Status'
        ];
    }

    // Memetakan / memasukkan data ke baris sesuai urutan kolom
    public function map($po): array
    {
        return [
            $po->po_number,
            $po->supplier_id,
            $po->order_date,
            $po->total,
            $po->status
        ];
    }
}