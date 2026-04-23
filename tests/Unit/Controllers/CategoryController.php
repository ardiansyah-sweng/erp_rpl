<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan library ini sudah terpasang

class CategoryController extends Controller
{
    public function exportPdf()
    {
        // 1. Ambil data menggunakan fungsi yang dibuat Hery
        // Asumsi: fungsi getCategory() mengembalikan koleksi data kategori
        $categories = Category::getCategory(); 

        // 2. Siapkan view yang akan diubah jadi PDF
        $pdf = Pdf::loadView('category.report_pdf', compact('categories'));

        // 3. Download atau tampilkan PDF-nya
        return $pdf->stream('laporan-kategori.pdf');
    }
}