<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class SupplierMaterialController extends Controller
{
    public function getSupplierMaterial()
    {
        $model = new SupplierMaterial();
        $materials = $model->getSupplierMaterial();

        return view('supplier.material.list', ['materials' => $materials]);
    }
    public function getSupplierMaterialFiltered(Request $request)
    {
        if ($request->export == 'pdf') {
            return $this->cetakPDFByFilter($request);
        }

        $query = SupplierMaterial::query();
        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('company_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_name', 'LIKE', '%' . $search . '%');
            });
        });

        $query->when($request->start_date, function ($q, $startDate) {
            return $q->whereDate('created_at', '>=', $startDate);
        });

        $query->when($request->end_date, function ($q, $endDate) {
            return $q->whereDate('created_at', '<=', $endDate);
        });

        $materials = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        return view('supplier.material.list', ['materials' => $materials]);
    }

    // 2. Cetak PDF massal berdasarkan filter aktif
    public function cetakPDFByFilter(Request $request)
    {
        $query = SupplierMaterial::query();

        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('company_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_name', 'LIKE', '%' . $search . '%');
            });
        });

        $query->when($request->start_date, function ($q, $startDate) {
            return $q->whereDate('created_at', '>=', $startDate);
        });

        $query->when($request->end_date, function ($q, $endDate) {
            return $q->whereDate('created_at', '<=', $endDate);
        });

        $materials = $query->orderBy('created_at', 'asc')->get();

        if ($materials->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk dicetak.');
        }

        $uniqueSuppliers = $materials->pluck('supplier_id')->unique();

        if ($uniqueSuppliers->count() === 1) {
            $final_supplier_id = $uniqueSuppliers->first();
            $supplierName = $materials->first()->company_name;
        } else {
            $final_supplier_id = 'LAP-FILTER';
            $keteranganFilter = [];

            if ($request->search) {
                $keteranganFilter[] = "Pencarian: '" . $request->search . "'";
            }

            if ($request->start_date && $request->end_date) {
                $tglMulai = date('d/m/Y', strtotime($request->start_date));
                $tglAkhir = date('d/m/Y', strtotime($request->end_date));
                $keteranganFilter[] = "Periode: $tglMulai s/d $tglAkhir";
            } elseif ($request->start_date) {
                $tglMulai = date('d/m/Y', strtotime($request->start_date));
                $keteranganFilter[] = "Sejak: $tglMulai";
            } elseif ($request->end_date) {
                $tglAkhir = date('d/m/Y', strtotime($request->end_date));
                $keteranganFilter[] = "Hingga: $tglAkhir";
            }

            if (!empty($keteranganFilter)) {
                $supplierName = "Laporan Material Kolektif (" . implode(' | ', $keteranganFilter) . ")";
            } else {
                $supplierName = "Laporan Keseluruhan Material Supplier";
            }
        }

        $pdf = Pdf::loadView('supplier.material.pdf', [
            'materials' => $materials,
            'supplierName' => $supplierName,
            'supplier_id' => $final_supplier_id
        ]);

        return $pdf->stream('data_material_' . $final_supplier_id . '.pdf');
    }
    public function getSupplierMaterialById($id)
    {
        $model = new SupplierMaterial();
        $material = $model->getSupplierMaterialById($id);

        if (!$material) {
            abort(404, 'Supplier material not found');
        }

        return view('supplier.material.detail', ['material' => $material]);
    }

    // Validasi data supplier material
    public function addSupplierMaterial(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => 'required|string|size:6',
            'company_name'  => 'required|string|max:255',
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|integer|min:0',
            'created_at'    => 'nullable|date',
            'updated_at'    => 'nullable|date',
        ]);
        SupplierMaterial::addSupplierMaterial((object)$validated);
        return redirect()->back()->with('success', 'Data supplier product berhasil divalidasi!');
    }

    public function updateSupplierMaterial(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id'    => 'required|string|max:50',
            'product_name'  => 'required|string|max:255',
            'base_price'    => 'required|integer|min:0'
        ]);

        $validated['updated_at'] = now();

        $model = new SupplierMaterial();
        $result = $model->updateSupplierMaterial($id, $validated);

        if ($result) {
            return redirect()->back()->with('success', 'Data supplier material berhasil diperbarui!');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui data supplier material!');
    }

    #cetak pdf
    public function cetakPDF($supplier_id)
    {
        $materials = SupplierMaterial::where('supplier_id', $supplier_id)->get();

        if ($materials->isEmpty()) {
            return redirect()->back()->with('error', 'Data supplier tidak ditemukan.');
        }

        $supplierName = $materials->first()->company_name;

        $pdf = Pdf::loadView('supplier.material.pdf', compact('materials', 'supplierName', 'supplier_id'));
        return $pdf->stream('data_material_' . $supplier_id . '.pdf');
    }

    public function getSupplierMaterialByProductType($supplier_id, $product_type)
    {
        // Validasi hanya menerima product_type tertentu
        if (!in_array($product_type, ['HFG', 'FG', 'RM'])) {
            return response()->json(['error' => 'Invalid product type'], 400);
        }

        $results = DB::table('supplier_product')
            ->join('products', DB::raw("SUBSTRING_INDEX(supplier_product.product_id, '-', 1)"), '=', 'products.product_id')
            ->join('item', 'products.product_id', '=', 'item.product_id')
            ->where('supplier_product.supplier_id', $supplier_id)
            ->where('products.product_type', $product_type)
            ->select(
                'supplier_product.supplier_id',
                'supplier_product.company_name',
                'supplier_product.product_id',
                'products.product_name',
                'products.product_type',
                'supplier_product.base_price',
                'item.item_name',
                'item.measurement_unit',
                'item.stock_unit'
            )
            ->get();

        return response()->json($results);
    }

    public function searchSupplierMaterial(Request $request)
    {

        $keyword = $request->input('keyword');

        $materials = SupplierMaterial::searchSupplierMaterial($keyword);

        // Cek apakah hasil pagination kosong
        if ($materials->isEmpty()) {
            session()->flash('error', 'Data tidak ditemukan atau tidak ada hasil.');
        }

        // 4. Return ke View dengan membawa data materials & keyword
        return view('supplier.material.list', [
            'materials' => $materials,
            'keyword'   => $keyword
        ]);
    }

    public function getSupplierMaterialByCategory($category, $supplier)
    {
        $results = DB::table('supplier_product')
            ->where('supplier_id', $supplier)
            ->where('product_id', 'LIKE', $category . '%')
            ->select(
                'supplier_id',
                'company_name',
                'product_id',
                'product_name',
                'base_price'
            )
            ->get();

        return response()->json($results);
    }
}
