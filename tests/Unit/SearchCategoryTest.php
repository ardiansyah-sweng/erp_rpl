<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Category;

class SearchCategoryTest extends TestCase
{
    #[Test]
    public function it_can_search_existing_category()
    {
        $keyword = 'produk'; // Sesuaikan dengan keyword yang kamu tahu ada di tabel categories

        $results = Category::searchCategory($keyword);

        // Pastikan hasil tidak kosong
        $this->assertNotEmpty($results);

        // Setiap hasil harus mengandung keyword (tidak case-sensitive)
        foreach ($results as $category) {
            $this->assertStringContainsStringIgnoringCase($keyword, $category->category);
        }
    }

    #[Test]
    public function it_returns_empty_when_no_match_found()
    {
        $results = Category::searchCategory('tidak-ada-kategori-ini');

        // Pastikan hasil kosong
        $this->assertTrue($results->isEmpty());
    }
}
