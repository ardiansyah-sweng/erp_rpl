<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\EncryptionHelper;


class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function generatePDF()
    {
        // Ambil semua data tanpa pagination
        $products = Product::getAllProducts(); // <= inilah bedanya

        // Buat PDF dari view
        $pdf = Pdf::loadView('product.pdf', compact('products'));

        // Tampilkan PDF di browser
        return $pdf->stream('daftar_produk.pdf');
    }


    public function getProductById($id)
    {
        $productId = EncryptionHelper::decrypt($id);
        $product = (new Product())->getProductById($productId);

        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }
       return view('product.detail', compact('product'));
    }


    // $productData = $products[$id];
    // $productData['category'] = (object)$productData['category'];

    public function printProductsByType($type)
    {
        $productType = ProductType::from($type);
        $products = Product::getProductByType($type)
            ->load(['category'])
            ->each(function ($product) {
                $product->items_count = Item::where('sku', 'LIKE', $product->product_id . '%')->count();
            });
        
        $pdf = PDF::loadView('product.pdf.by-type', [
            'products' => $products,
            'type' => $productType->label()
        ]);
        
        return $pdf->download('products-' . strtolower($type) . '.pdf');
    }
    // $product = (object)$productData;

    // return view('product.detail', compact('product'));


    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|string|unique:products,product_id',
            'product_name' => 'required|string',
            'product_type' => 'required|string',
            'product_category' => 'required|string',
            'product_description' => 'nullable|string',
        ]);

        Product::addProduct($validatedData);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }
    public function updateProduct(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'product_name' => 'required|string|max:35',
            'product_type' =>  'required|string|max:12',
            'product_category' => 'required|integer',
            'product_description' => 'nullable|string|max:255',
        ]);

        $Updateproduct = Product::updateProduct($id, $request->only(['product_name','product_type','product_category','product_description']));

        return $Updateproduct;
    }


    public function searchProduct($keyword)
    {
        $products = Product::getProductByKeyword($keyword);
        return view('product.list', compact('products'));
    }
    public function getProductByCategory($product_category)
    {
        $products = Product::getProductByCategory($product_category);

        // PERBAIKAN: cek apakah tidak ada data
        if ($products->total() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk untuk kategori tersebut.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berdasarkan kategori ditemukan.',
            'data' => $products,
        ]);
    }

}
