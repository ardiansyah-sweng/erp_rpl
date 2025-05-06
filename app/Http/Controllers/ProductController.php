<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductList()
    {
        $products = Product::getAllProducts();
        return view('product.list', compact('products'));
    }

    public function getProductById($id)
<<<<<<< HEAD
{
    $products = [
        1 => [
            'id' => 1,
            'product_id' => 'KAOS',
            'product_name' => 'Kaos T-Shirt',
            'product_type' => 'Finished',
            'category' => ['category' => 'Pakaian'],
            'product_description' => 'Kaos T-Shirt',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ],
        2 => [
            'id' => 2,
            'product_id' => 'TOPI',
            'product_name' => 'Topi',
            'product_type' => 'Finished',
            'category' => ['category' => 'Aksesoris'],
            'product_description' => 'Topi',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ],
        3 => [
            'id' => 3,
            'product_id' => 'TASS',
            'product_name' => 'Tas',
            'product_type' => 'Finished',
            'category' => ['category' => 'Aksesoris'],
            'product_description' => 'Tas',
            'created_at' => '2025-03-12 19:48:13',
            'updated_at' => '2025-03-12 19:48:13'
        ]
    ];

    if (!isset($products[$id])) {
        abort(404, 'Product not found.');
    }

    $productData = $products[$id];
    $productData['category'] = (object)$productData['category'];
    $product = (object)$productData;

    return view('product.detail', compact('product'));
}
=======
    {

        $products = [
            1 => [
                'id' => 1,
                'product_id' => 'KAOS',
                'product_name' => 'Kaos T-Shirt',
                'product_type' => 'Finished',
                'category' => ['category' => 'Pakaian'],
                'product_description' => 'Kaos T-Shirt',
                'created_at' => '2025-03-12 19:48:13',
                'updated_at' => '2025-03-12 19:48:13'
            ],
            2 => [
                'id' => 2,
                'product_id' => 'TOPI',
                'product_name' => 'Topi',
                'product_type' => 'Finished',
                'category' => ['category' => 'Aksesoris'],
                'product_description' => 'Topi',
                'created_at' => '2025-03-12 19:48:13',
                'updated_at' => '2025-03-12 19:48:13'
            ],
            3 => [
                'id' => 3,
                'product_id' => 'TASS',
                'product_name' => 'Tas',
                'product_type' => 'Finished',
                'category' => ['category' => 'Aksesoris'],
                'product_description' => 'Tas',
                'created_at' => '2025-03-12 19:48:13',
                'updated_at' => '2025-03-12 19:48:13'
            ]
        ];
>>>>>>> 3fc794a9414fee8bf32d4fd490b4adfb110d070f

}
<<<<<<< HEAD
=======

        $product = (new Product())->getProductById($id);

        if (!$product) {
            return abort(404, 'Product tidak ditemukan');
        }
       return view('product.detail', compact('product'));
    }
}

>>>>>>> 3fc794a9414fee8bf32d4fd490b4adfb110d070f
