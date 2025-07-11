<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
<<<<<<< HEAD
use Barryvdh\DomPDF\Facade\Pdf;
=======
use App\Helpers\EncryptionHelper;
>>>>>>> origin/development

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
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

<<<<<<< HEAD
public function generateProductPDF()
{
    $products = Product::getAllProductsForPDF();

    $pdf = Pdf::loadView('pdf.product_list', compact('products'));
    return $pdf->stream('daftar_produk.pdf');
}

public function previewProductPDF()
{
    $products = Product::getAllProductsForPDF();
    return view('product.preview-pdf', compact('products'));
}
=======

    public function searchProduct($keyword)
    {
        $products = Product::where('product_id', 'LIKE', "%{$keyword}%")
            ->orWhere('product_name', 'LIKE', "%{$keyword}%")
            ->orWhere('product_type', 'LIKE', "%{$keyword}%")
            ->orWhereRaw('CAST(product_category AS CHAR) LIKE ?', ["%{$keyword}%"])
            ->orWhere('product_description', 'LIKE', "%{$keyword}%")
            ->paginate(10);

        return view('product.list', compact('products'));
    }

>>>>>>> origin/development
}
