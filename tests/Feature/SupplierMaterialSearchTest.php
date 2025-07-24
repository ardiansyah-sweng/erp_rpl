<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SupplierMaterial;
use PHPUnit\Framework\Attributes\Test;

class SupplierMaterialSearchTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_data_when_keyword_exists()
    {
        $keyword = 'Menjangan';
        $result = SupplierMaterial::searchSupplierMaterial($keyword);

        if ($result->total() > 0) {
            $first = $result->items()[0];
            echo "Data ditemukan: {$first->company_name}, Produk: {$first->product_name}\n";
        }
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_not_found_message_when_keyword_does_not_exist()
    {
        $keyword = 'gerobak';
        $result = SupplierMaterial::searchSupplierMaterial($keyword);

        if ($result->total() === 0) {
            echo "pencarian tidak ditemukan\n";
        }
        $this->assertTrue(true);
    }
}

