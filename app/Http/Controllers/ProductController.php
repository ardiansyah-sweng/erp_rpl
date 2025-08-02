<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\EncryptionHelper;
use App\Enums\ProductType;


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
        if ($type === 'ALL') {
            // Get all products
            $products = Product::with('category')->get();
            $typeLabel = 'Semua Tipe';
        } else {
            // Get the enum case based on the type parameter
            $productType = ProductType::tryFrom($type);
            if (!$productType) {
                abort(404, 'Invalid product type');
            }

            // Get products of the specified type
            $products = Product::getProductByType($type)
                ->load(['category']);
            $typeLabel = $productType->value;
        }


        // Load the PDF view
        $pdf = PDF::loadView('product.pdf', [
            'products' => $products,
            'type' => $typeLabel
        ]);

        // Stream the PDF to the browser
        return $pdf->stream("products_{$type}.pdf");
    }

    public function showAddProductForm()
    {
        // Ambil semua data kategori dari database
        $categories = Category::all();
        
        // Kirim data kategori ke view
        return view('product.add', compact('categories'));
    }

        // Load the PDF view
        $pdf = PDF::loadView('product.pdf', [
            'products' => $products,
            'type' => $typeLabel
        ]);

        // Stream the PDF to the browser
        return $pdf->stream("products_{$type}.pdf");
    }


    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|string|unique:products,product_id',
            'product_name' => 'required|string',
            'product_type' => 'required|string',
            'product_category' => 'required|integer|exists:category,id',
            'product_description' => 'nullable|string',
        ]);

        Product::addProduct($validatedData);

        return redirect()->route('product.list')->with('success', 'Produk berhasil ditambahkan.');
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

    public function getProductByType($type)
    {
        $products = Product::getProductByType($type);

        if ($products->isEmpty()) {
            return response()->json([
                'message' => "Tidak ada produk dengan tipe: {$type}"
            ], 404);
        }

        return response()->json([
            'message' => 'Data produk berhasil ditemukan',
            'data' => $products
        ]);
    }

}