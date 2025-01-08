<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\SupplierProduct;

use Faker\Factory as Faker;

class InitialStockSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all('product_id');

        foreach ($products as $data)
        {
            # inisialisasi jumlah stok awal
            // $numOfstock = $this->faker->numberBetween(1, 10000);
            // Product::where('product_id', $data->product_id)
            //             ->update(['current_stock' => $numOfstock]);

            # dapatkan seluruh supplier dengan produk saat ini
            // $shuffledSupplierProduct = SupplierProduct::where('product_id', $data->product_id)->pluck('supplier_id')->shuffle();

            #ambil sebagian supplier
            // $countShuffledSupplierProduct = $shuffledSupplierProduct->count();
            // $supplierProduct = $shuffledSupplierProduct->take($this->faker->numberBetween(1, $countShuffledSupplierProduct));

            #update current_stock


            #TODO - nanti digunakan kembali untuk histori purchase order
            // if ($supplierProduct->count() > 1)
            // {
            //     $proportions = $this->generateProportions($supplierProduct->count());
            
            //     // Ulangi jika ada elemen yang bernilai 0.0
            //     while (in_array(0.0, $proportions, true))
            //     {
            //         $proportions = $this->generateProportions($supplierProduct->count());
            //     }
            
            //     // Sesuaikan panjang $proportions dengan jumlah supplier
            //     $proportions = array_slice($proportions, 0, $supplierProduct->count());
            
            //     foreach ($supplierProduct as $key => $supplierId)
            //     {
            //         $supplierStock = round($proportions[$key] * $numOfstock);

            //     }

            // } else {
            //     Product::where('product_id', $data->product_id)
            //     ->update(['current_stock' => $supplierProduct]);
            // }
        }
    }

    public function generateProportions(int $numElements): array {
        // Generate nilai random awal untuk tiap elemen
        $randomValues = [];
        for ($i = 0; $i < $numElements; $i++) {
            $randomValues[] = mt_rand(1, 100); // Bilangan bulat acak
        }
    
        // Hitung total dari random values
        $total = array_sum($randomValues);
    
        // Normalisasi ke dalam proporsi decimal
        $proportions = array_map(function ($value) use ($total) {
            return round($value / $total, 1); // Bulatkan hingga 1 desimal
        }, $randomValues);
    
        // Penyesuaian untuk memastikan total menjadi 1
        $proportions[count($proportions) - 1] += round(1 - array_sum($proportions), 1);
    
        return $proportions;
    }
}
